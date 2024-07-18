<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'field_type',
        'step',
        'daily_sheet_id',
    ];

    public function dailySheet()
    {
        return $this->belongsTo(DailySheet::class);
    }

    public function dropdown_lists()
    {
        return $this->hasMany(DropdownLists::class);
    }
}
