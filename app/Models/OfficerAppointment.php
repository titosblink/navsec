<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficerAppointment extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'date_from' => 'date',
        'date_to'   => 'date',
        'is_current' => 'boolean',
    ];

    public function officer()
    {
        return $this->belongsTo(Officer::class)->withTrashed();
    }
}
