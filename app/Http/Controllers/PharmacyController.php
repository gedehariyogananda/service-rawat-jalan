<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DetailKunjungan;
use App\Models\Drugs;
use App\Models\Keuangan;
use App\Models\PrescriptionDetails;
use App\Models\Prescriptions;
use Illuminate\Support\Str;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;

class PharmacyController extends Controller
{

    //------------------------------------------ view section ---------------------------------------------------
    public function listMedicine()
    {
        $drugs = Drugs::all();
        return response()->json(['drugs' => $drugs]);
    }

    public function detailMedicine($id)
    {
        $drug = Drugs::find($id);

        if (!$drug) {
            return response()->json(['message' => 'Obat tidak ditemukan'], 404);
        }

        return response()->json(['drug' => $drug]);
    }

    public function getPrescriptionApi(HttpRequest $request)
    {
        $visitDetailId = $request->query('visitDetailId');

        if (!$visitDetailId) {
            return response()->json(['error' => 'visitDetailId is required'], 400);
        }

        $visitDetail = DetailKunjungan::where('id', $visitDetailId)->first();

        if (!$visitDetail) {
            return response()->json(['error' => 'Visit detail not found'], 404);
        }

        $prescriptionDetails = $this->getPrescriptionDetail($visitDetail->apotek_id, $visitDetail->id);
        $prescription = $this->getPrescription($visitDetailId);

        $patient = $visitDetail->kunjungan->pasien;

        $totalPrice = $this->getTotalBill($prescription->id);

        return response()->json([
            'visitDetail' => $visitDetail,
            'prescriptionDrugs' => $prescriptionDetails,
            'patient' => $patient,
            'prescription' => $prescription,
            'totalPrice' => $totalPrice,
        ]);
    }


    public function getPrescriptionDetailApi(HttpRequest $request)
    {
        $prescriptionId = $request->query('prescriptionId');
        $visitId = $request->query('visitId');

        if (!$prescriptionId || !$visitId) {
            return response()->json(['error' => 'Both prescriptionId and visitId are required'], 400);
        }

        $drugs = Drugs::where('stock', '!=', 0)->get();

        $prescriptionDetails = $this->getPrescriptionDetail($prescriptionId, null);

        return response()->json([
            'prescriptionDetails' => $prescriptionDetails,
            'availableDrugs' => $drugs,
            'prescriptionId' => $prescriptionId,
            'visitId' => $visitId
        ]);
    }


    //------------------------------------------ destroy section ---------------------------------------------------
    public function destroyPrescriptionDetail($id)
    {
        Prescriptions::destroy($id);
    }

    public function destroyDrug(HttpRequest $request)
    {
        $drug = Drugs::find($request->id);

        if (!$drug) {
            return response()->json(['message' => 'Obat tidak ditemukan'], 404);
        }

        $drug->delete();

        return response()->json(['message' => 'Obat berhasil dihapus']);
    }

    public function destroyDrugInPrescription(HttpRequest $request)
    {
        $prescriptionDrug = PrescriptionDetails::find($request->prescriptionDrugId);

        if (!$prescriptionDrug) {
            return response()->json(['error' => 'Prescription drug not found'], 404);
        }

        $this->operateDrugStock($prescriptionDrug->drug_id, '+', $prescriptionDrug->quantity);

        $prescriptionDrug->delete();

        return response()->json(['message' => 'Prescription drug deleted successfully']);
    }

    //------------------------------------------ create section ---------------------------------------------------
    public function createDrug(HttpRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|numeric',
            'guide' => 'required|string',
            'sideEffect' => 'required|string',
            'stock' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $drug = Drugs::create([
            'name' => $request->name,
            'price' => $request->price,
            'name_code' => Str::uuid(),
            'how_to_use' =>  $request->guide,
            'side_effect' =>  $request->sideEffect,
            'stock' => $request->stock
        ]);

        return response()->json(['message' => 'Drug created successfully', 'data' => $drug], 201);
    }

    public function createDrugInPrescription(HttpRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'prescriptionId' => 'required|exists:prescriptions,id',
            'medicineId' => 'required|exists:drugs,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $quantity = $this->getAvailableDrugs($request->quantity, $request->medicineId);

        $prescriptionDetail = PrescriptionDetails::create([
            'prescription_id' => $request->prescriptionId,
            'drug_id' => $request->medicineId,
            'quantity' => $quantity,
        ]);

        return response()->json(['message' => 'Prescription drug created successfully', 'data' => $prescriptionDetail], 201);
    }

    //------------------------------------------ update section ---------------------------------------------------
    public function updateDrug(HttpRequest $request)
    {
        if (!$request->id) {
            return response()->json(['error' => 'Id need to be filled'], 404);
        }

        $drug = Drugs::find($request->id);

        if (!$drug) {
            return response()->json(['error' => 'Drug not found'], 404);
        }

        $updateData = [];

        if ($request->has('name')) {
            $updateData['name'] = $request->name;
        }

        if ($request->has('price')) {
            $updateData['price'] = $request->price;
        }

        if ($request->has('guide')) {
            $updateData['how_to_use'] = $request->guide;
        }

        if ($request->has('sideEffect')) {
            $updateData['side_effect'] = $request->sideEffect;
        }

        if (!empty($updateData)) {
            $drug->update($updateData);
        }

        return response()->json(['message' => 'Drug updated successfully', 'data' => $drug]);
    }


    public function updateDrugStock(HttpRequest $request)
    {
        if (!$request->id) {
            return response()->json(['error' => 'Id need to be filled'], 404);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:drugs,id',
            'operation' => 'required|in:+,-',
            'stock' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        return $this->operateDrugStock($request->id, $request->operation, $request->stock);
    }


    public function updateDrugInPrescription(HttpRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'prescriptionDetailId' => 'required',
            'drugId' => 'required|exists:drugs,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $isChangeLarger = $request->currentQuantity < $request->quantity;
        $value = $this->takeTheDifferentValue($request->quantity, $request->currentQuantity);

        $this->operateDrugStock($request->drugId, $isChangeLarger ? '-' : '+', $value);

        PrescriptionDetails::where('id', $request->prescriptionDetailId)->update([
            'quantity' => $request->quantity,
        ]);

        return response()->json(['message' => 'Prescription drug updated successfully']);
    }

    public function checkoutPrescription(HttpRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'prescriptionId' => 'required',
            'visitId' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $prescriptionId = $request->prescriptionId;
        $visitId = $request->visitId;

        $this->processInTransaction($this->getTotalBill($prescriptionId), $visitId);

        Prescriptions::find($prescriptionId)->update([
            'is_taken' => 1
        ]);

        return response()->json(['message' => 'Prescription checked out successfully']);
    }

    //------------------------------------------ utils section ---------------------------------------------------

    private function getPrescriptionDetail($prescriptionId, $visitDetailId)
    {
        $prescription = $prescriptionId ?
            Prescriptions::where('id', $prescriptionId)->first() :
            $this->initiatePrescription($visitDetailId);
        return $prescription->prescriptionDetail();
    }

    private function initiatePrescription($visitId)
    {
        $prescription = Prescriptions::create([
            'invoice_code' => Str::uuid()
        ]);
        $this->updateVisitPrescriptionId($visitId, $prescription->id);
        return $prescription;
    }

    private function updateVisitPrescriptionId($visitId, $prescriptionId)
    {
        DetailKunjungan::where('id', $visitId)->update([
            'apotek_id' => $prescriptionId
        ]);
    }

    private function getPrescription($id)
    {
        $prescriptionId = DetailKunjungan::where('id', $id)->first()->apotek_id;
        return Prescriptions::where('id', $prescriptionId)->first();
    }

    private function getAvailableDrugs($qtyRequest, $id)
    {
        $stock = Drugs::where('id', $id)->first()->stock;
        $isEnough = $stock > $qtyRequest;

        Drugs::where('id', $id)->update([
            'stock' => $isEnough ? ($stock - $qtyRequest) : 0
        ]);

        return $isEnough ? $qtyRequest : $stock;
    }

    private function operateDrugStock($id, $operation, $value)
    {
        $drug = Drugs::find($id);

        if (!$drug) {
            return response()->json(['error' => 'Drug not found'], 404);
        }

        $currentStock = $drug->stock;
        $currentPrice = $drug->price;

        $newStock = ($operation === '+') ? $currentStock + $value : $currentStock - $value;

        if ($newStock < 0) {
            return response()->json(['error' => 'insufficient stock'], 400);
        }

        $drug->update(['stock' => $newStock]);

        if ($operation == '+') {
            $this->updatePengeluaran($currentPrice * $value);
        }
        return response()->json(['message' => 'Drug stock operated successfully', 'data' => $drug]);
    }

    private function takeTheDifferentValue($value1, $value2)
    {
        $value = $value1 - $value2;
        return ($value < 0) ? (-1 * $value) : $value;
    }

    //------------------------------------------ payment section ---------------------------------------------------

    private function getTotalBill($id)
    {
        $prescriptionDetails = Prescriptions::where('id', $id)->first()->prescriptionDetail();
        $bill = 0;
        foreach ($prescriptionDetails as $prescriptionDetail) {
            $bill += $prescriptionDetail->drug()->price * $prescriptionDetail->quantity;
        }
        return $bill;
    }

    private function processInTransaction($totalBill, $visitId)
    {
        $process = DetailKunjungan::find($visitId);

        if ($process->pembayaran == null) {

            $process->pembayaran = 0 + $totalBill;
        } else {
            $process->pembayaran = $process->pembayaran + $totalBill;
        }

        $process->save();
    }

    private function updatePengeluaran($duit)
    {
        $keuangan = new Keuangan();

        $keuangan->pemasukan = 0;
        $keuangan->pengeluaran = $duit;
        $keuangan->tanggal_arsip = Date::now();
        $keuangan->save();
    }
}
