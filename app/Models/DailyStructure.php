<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyStructure extends Model
{
    use HasFactory;

    protected $table = 'daily_structure';
    protected $fillable = [
        'contract_id',
        'vigente'
      
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function dailySheets()
    {
        return $this->hasMany(DailySheet::class);
    }
}
