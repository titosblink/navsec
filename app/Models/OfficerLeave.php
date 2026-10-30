<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OfficerLeave extends Model {
    protected $guarded = ['id'];
    protected $casts = ['start_date'=>'date','end_date'=>'date','approved_date'=>'date'];
    public function officer() { return $this->belongsTo(Officer::class)->withTrashed(); }
    public static function types(): array { return ['annual','medical','study','compassionate','maternity','paternity','other']; }
}
