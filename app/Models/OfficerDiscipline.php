<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OfficerDiscipline extends Model {
    protected $guarded = ['id'];
    protected $casts = ['date_of_offence'=>'date','date_of_trial'=>'date'];
    public function officer() { return $this->belongsTo(Officer::class); }
}
