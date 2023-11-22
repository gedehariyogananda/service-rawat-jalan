<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionDetails extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function drug()
    {
        return $this->belongsTo(Drugs::class, 'drug_id')->first();
    }
    
    public function prescription()
    {
        return $this->belongsTo(Prescriptions::class, 'prescription_id')->first();
    }
}
