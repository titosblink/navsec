<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OfficerDocument extends Model {
    protected $guarded = ['id'];
    public function officer() { return $this->belongsTo(Officer::class); }
    public function fileUrl(): string { return asset('uploads/documents/' . $this->file_path); }
    public static function types(): array {
        return ['commission_cert'=>'Commission Certificate','academic_cert'=>'Academic Certificate',
                'posting_letter'=>'Posting Letter','medical_report'=>'Medical Report',
                'id_document'=>'ID Document','other'=>'Other'];
    }
}
