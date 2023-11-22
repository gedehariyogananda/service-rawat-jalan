<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescriptions extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function prescriptionDetail()
    {
        return $this->hasMany(PrescriptionDetails::class, 'prescription_id')->get();
    }
}
