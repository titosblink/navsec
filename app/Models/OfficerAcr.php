<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OfficerAcr extends Model {
    protected $guarded = ['id'];
    protected $casts = ['date_submitted' => 'date'];
    public function officer() { return $this->belongsTo(Officer::class); }
    public static function fitnessCategories(): array { return ['A1','A2','B1','B2','C','D']; }
}
