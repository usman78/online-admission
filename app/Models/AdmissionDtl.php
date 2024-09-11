<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionDtl extends Model
{
    use HasFactory;
    protected $table = 'online_admission_dtl';
    protected $primaryKey = 'dtl_id';
    protected $fillable = [
        'dtl_id',
        'adm_applicant_id',
        'sr_no',
        'qualification',
        'roll_no',
        'institution',
        'obt_marks',
        'total_marks',
        'edu_document'
    ];
}
