<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OfficerQualification extends Model {
    protected $guarded = ['id'];
    public function officer() { return $this->belongsTo(Officer::class); }
    public static function types(): array {
        return ['degree'=>'Degree','diploma'=>'Diploma','certificate'=>'Certificate',
                'professional'=>'Professional','trade'=>'Trade'];
    }
}
