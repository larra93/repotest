<?php

// es cada daily generado por fecha para cada contrato, tener en cuenta que para cada revision hay que crear un nuevo daily, por lo que hay que agregar columna "revision" Y FALTA AGREGAR EL DAILYSHEET_ID
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dailys extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'state_id',
        'contract_id',
        'revision',
        'daily_structure_id',
      
    ];

    public function dailyStructure()
    {
        return $this->belongsTo(DailyStructure::class, 'daily_structure_id');
    }
    
}
