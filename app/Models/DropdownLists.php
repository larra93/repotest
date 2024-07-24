<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropdownLists extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'field_id'
    ];

    public function Field()
    {
        return $this->belongsTo(Field::class);
    }

}
