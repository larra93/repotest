<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_contract', 'NSAP', 'DEN', 'project', 'API', 'CC','start_date', 'end_date', 'id_company', 'created_by',
        'is_revisor_pyc_required', 'is_revisor_cc_required', 'is_revisor_other_area_required'
    ];

    // public function users()
    // {
    //     return $this->belongsToMany(User::class)->withPivot('role_id')->withTimestamps();
    // }

    public function users()
    {
        return $this->belongsToMany(User::class, 'contract_user')->withPivot('role_id')->withTimestamps();
    }
}
