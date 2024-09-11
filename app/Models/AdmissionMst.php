<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionMst extends Model
{
    use HasFactory;
    protected $table = 'online_admission_mst';
    protected $primaryKey = 'adm_applicant_id';
    protected $fillable = [
        'adm_applicant_id', 'name', 'father_name', 'student_cnic', 'father_cnic',
        'date_of_birth', 'gender', 'nationality', 'religion', 'city',
        'postal_address', 'email', 'st_mobile_phone', 'fr_mobile_phone',
        'accommodation', 'emg_cont_pname', 'emg_cont_mno', 'relation', 'image_name','cnic_front_side','cinc_back_side'
    ];
}
