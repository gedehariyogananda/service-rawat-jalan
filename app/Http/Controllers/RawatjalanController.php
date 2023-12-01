<?php

namespace App\Http\Controllers;

use App\Models\DetailKunjungan;
use App\Models\Poli;
use App\Models\Prescriptions;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RawatjalanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private DetailKunjungan $detailKunjungan;
    private $dataDokter;
    private $dataPerawat;
    private $allDataPenanganan;
    private $dataPoli;
    private $dataRoom;

    public function __construct(DetailKunjungan $detailKunjungan)
    {
        $this->detailKunjungan = $detailKunjungan;
        $this->dataDokter = User::where('roles', '=', 'dokter')->get();
        $this->dataPerawat = User::where('roles', '=', 'perawat')->get();
        $this->allDataPenanganan = User::where('roles', '=', 'dokter')->orWhere('roles', '=', 'perawat')->get();
        $this->dataPoli = Poli::all();
        $this->dataRoom = Room::all();
    }

    // ----------------------------------------------- Page Detail Kunjungan ---------------------------------------------------

    // get data all (get)
    public function index()
    {
        try {
            $rawatjalan = $this->detailKunjungan->with('kunjungan', 'pasien', 'room', 'user', 'poli')->get();

            $mappedData = $rawatjalan->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'nama' => $detail->kunjungan->pasien ? $detail->kunjungan->pasien->nama : null,
                    'jadwal' => $detail->kunjungan ? date('d F Y', strtotime($detail->kunjungan->tanggal_kunjungan)) : '-',
                    'room' => $detail->room ? $detail->room->name_room : null,
                    'penanganan' => $detail->user ? $detail->user->roles  . ' ' . $detail->user->name : null,
                    'poli' => $detail->poli ? $detail->poli->name_poli : null,
                    'diagnosa' => $detail->diagnosa,
                    'resep' => $detail->resep,
                    'nota_apotek' => $detail->apotek_id,
                    'pembayaran' => $detail->pembayaran,
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil Get',
                'data' => $mappedData,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    // get data kunjungan (get)
    public function addDataKunjungan($id)
    {
        try {
            $data = $this->detailKunjungan->find($id);
            $dataNama = $data->kunjungan->pasien->nama;

            if ($dataNama) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil ditambahkan',
                    'data' => [
                        'nama' => $dataNama,
                        'dokter' => $this->dataDokter,
                        'perawat' => $this->dataPerawat,
                        'poli' => $this->dataPoli,
                        'room' => $this->dataRoom
                    ]
                ], 202);
            } else {
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'Data gagal Get',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    // add data submisson kunjungan (patch)
    public function addDataSubmission($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required'],
                'user_id' => ['required'],
                'room_id' => ['required'],
                'poli_id' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'error' => $validator->errors(),
                ], 400);
            }

            $submission = $this->detailKunjungan->where('id', $request->id)->update($request->all());

            $theData = $this->detailKunjungan->where('id', $request->id)->first();

            if ($submission) {
                return response()->json([
                    'status' => $submission,
                    'message' => 'Data berhasil ditambahkan',
                    'data' => $theData,
                ], 202);
            } else {
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'Data gagal ditambahkan',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error' => $th->getMessage(),
            ], 404);
        }
    }

    // add resep (patch)
    public function addDiagnosaResep($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'diagnosa' => ['required'],
                'resep' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'error' => $validator->errors(),
                ], 400);
            }

            $submission = $this->detailKunjungan->where('id', $id)->update($request->all());

            $theData = $this->detailKunjungan->where('id', $id)->first();

            if ($submission) {
                return response()->json([
                    'status' => $submission,
                    'message' => 'Data diagnosa dan resep berhasil ditambahkan',
                    'data' => $theData,
                ], 202);
            } else {
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'Data gagal ditambahkan',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error' => $th->getMessage(),
            ], 404);
        }
    }

    // get page update kunjungan (get)
    public function getUpdateKunjungan($id)
    {
        try {
            $data = $this->detailKunjungan->with('kunjungan', 'pasien', 'room', 'user', 'poli')->find($id);
            $dataYangMenangani = $data->user_id;

            $dokterID = range(5, 17);

            if (in_array($dataYangMenangani, $dokterID)) {
                $dataYangMenangani = $this->dataDokter;
            } else {
                $dataYangMenangani = $this->allDataPenanganan;
            }

            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data succes get',
                    'data' => [
                        'nama' => $data->kunjungan->pasien->nama,
                        'yangMenangani' => $dataYangMenangani,
                        'dataPoli' => $this->dataPoli,
                        'dataRoom' => $this->dataRoom,
                        'dataDiagnosa' => $data->diagnosa,
                        'dataResep' => $data->resep,
                        'dokterNama' =>  $data->user->roles . " - " . $data->user->name,
                        'dokterId' => $data->user->id,
                        'roomNama' => $data->room->name_room,
                        'roomId' => $data->room->id,
                        'poliNama' => $data->poli->name_poli,
                        'poliId' => $data->poli->id,
                    ]
                ], 202);
            } else {
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'Data gagal Get',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error' => $th->getMessage(),
            ], 404);
        }
    }

    // update kunjungan (patch)
    public function updateKunjungan($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => ['min:1'],
                'diagnosa' => ['min:1'],
                'resep' => ['min:1'],
                'poli_id' => ['min:1'],
                'room_id' => ['min:1'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'error' => $validator->errors(),
                ], 400);
            }

            $submission = $this->detailKunjungan->where('id', $id)->update($request->all());

            $theData = $this->detailKunjungan->where('id', $id)->first();

            if ($submission) {
                return response()->json([
                    'status' => $submission,
                    'message' => 'Data berhasil terupdate',
                    'data' => $theData,
                ], 202);
            } else {
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'Data gagal ditambahkan',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error' => $th->getMessage(),
            ], 404);
        }
    }

    // delete kunjungan (delete)
    public function deleteKunjungan($id)
    {
        try {
            $data = $this->detailKunjungan->find($id);
            $data->delete();

            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil dihapus',
                ], 202);
            } else {
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'Data gagal dihapus',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error' => $th->getMessage(),
            ], 404);
        }
    }

    public function getKunjunganBelumPemeriksaan()
    {
        try {
            $rawatjalan = $this->detailKunjungan->with('kunjungan', 'pasien', 'room', 'user', 'poli')
                ->where('diagnosa', '=', null)->get();

            $mappedData = $rawatjalan->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'nama' => $detail->kunjungan->pasien ? $detail->kunjungan->pasien->nama : null,
                    'jadwal' => $detail->kunjungan ? date('d F Y', strtotime($detail->kunjungan->tanggal_kunjungan)) : '-',
                    'room' => $detail->room ? $detail->room->name_room : null,
                    'penanganan' => $detail->user ? $detail->user->roles  . ' ' . $detail->user->name : null,
                    'poli' => $detail->poli ? $detail->poli->name_poli : null,
                    'diagnosa' => $detail->diagnosa,
                    'resep' => $detail->resep,
                    'nota_apotek' => $detail->apotek_id,
                    'pembayaran' => $detail->pembayaran,
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data belum pemeriksaan berhasil Get',
                'data' => $mappedData,
            ], 202);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function getKunjunganSudahPemeriksaan()
    {
        try {
            $rawatjalan = $this->detailKunjungan->with('kunjungan', 'pasien', 'room', 'user', 'poli')
                ->where('diagnosa', '!=', null)->get();

            $mappedData = $rawatjalan->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'nama' => $detail->kunjungan->pasien ? $detail->kunjungan->pasien->nama : null,
                    'jadwal' => $detail->kunjungan ? date('d F Y', strtotime($detail->kunjungan->tanggal_kunjungan)) : '-',
                    'room' => $detail->room ? $detail->room->name_room : null,
                    'penanganan' => $detail->user ? $detail->user->roles  . ' ' . $detail->user->name : null,
                    'poli' => $detail->poli ? $detail->poli->name_poli : null,
                    'diagnosa' => $detail->diagnosa,
                    'resep' => $detail->resep,
                    'nota_apotek' => $detail->apotek_id,
                    'pembayaran' => $detail->pembayaran,
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data sudah pemeriksaaan berhasil di get',
                'data' => $mappedData,
            ], 202);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    //------------------------------------------ Page Set Pemeriksaan ---------------------------------------------------

    public function getSetPemeriksaan()
    {
        try {
            $rawatjalan = $this->detailKunjungan->with('kunjungan', 'pasien', 'room', 'user', 'poli')
                ->where('diagnosa', '!=', null)->get();

            $mappedData = $rawatjalan->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'nama' => $detail->kunjungan->pasien->nama,
                    'diagnosa' => $detail->diagnosa,
                    'apotek_id' => $detail->apotek_id,
                    'status_pemeriksaan' => $detail->apotek_id ? 'Complete Data' : 'Not Complete',
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data sudah pemeriksaaan berhasil di get',
                'data' => $mappedData,
            ], 202);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function setPemeriksaan($id)
    {
        try {
            $visitDetail = $this->detailKunjungan->where('id', $id)->first();

            $prescriptionDetails = $this->getPrescriptionDetail($visitDetail->apotek_id, $visitDetail->id);
            $prescription = $this->getPrescription($id);

            $patient = $visitDetail->kunjungan->pasien;
            $doctor = $visitDetail->user;

            if ($visitDetail) {
                return response()->json([
                    'visitDetail' => $visitDetail,
                    'prescriptionDetails' => $prescriptionDetails,
                    'patient' => $patient,
                    'doctor' => $doctor,
                    'prescription' => $prescription,
                    'totalPrice' =>  $this->getTotalBill($prescription->id),
                ]);
            } else {
                return response()->json([
                    'message' => 'Failed to get data',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    //------------------------------------------ utils Service Pharmacy ---------------------------------------------------

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

    private function getTotalBill($id)
    {
        $prescriptionDetails = Prescriptions::where('id', $id)->first()->prescriptionDetail();
        $bill = 0;
        foreach ($prescriptionDetails as $prescriptionDetail) {
            $bill += $prescriptionDetail->drug()->price * $prescriptionDetail->quantity;
        }
        return $bill;
    }
}
