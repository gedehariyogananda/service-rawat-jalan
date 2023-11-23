<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    // protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'id_detail_kunjungan', 'catatan', 'receipt_file_path'
    ];

    

    public function dkunjungan()
    {
        return $this->belongsTo(DetailKunjungan::class, 'id_detail_kunjungan');
    }


}
