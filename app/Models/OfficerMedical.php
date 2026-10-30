<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OfficerMedical extends Model {
    protected $guarded = ['id'];
    protected $casts = ['exam_date'=>'date','next_exam_date'=>'date'];
    public function officer() { return $this->belongsTo(Officer::class); }
}
