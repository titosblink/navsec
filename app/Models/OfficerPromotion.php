<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OfficerPromotion extends Model {
    protected $guarded = ['id'];
    protected $casts = ['effective_date' => 'date'];
    public function officer() { return $this->belongsTo(Officer::class); }
}
