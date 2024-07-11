<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'step',
        'contract_id',
        'vigente'
      
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }
}
