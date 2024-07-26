<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_id',
        'value',
        'daily_sheet_id',
        'row',
        'daily_id',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function dailySheet()
    {
        return $this->belongsTo(DailySheet::class);
    }

    public function daily()
    {
        return $this->belongsTo(Dailys::class);
    }
}
