<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficerCourse extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date',
    ];

    public function officer()
    {
        return $this->belongsTo(Officer::class)->withTrashed();
    }

    public static function types(): array
    {
        return ['OLC', 'JD', 'SD', 'NWC', 'NIPS', 'STC', 'LC', 'PRO', 'OTHER'];
    }
}
