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
        'daily_structure_id',

      
    ];

    public function DailyStructure()
    {
        return $this->belongsTo(DailyStructure::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }
}
