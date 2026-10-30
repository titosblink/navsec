<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LeaveEntitlement extends Model {
    protected $guarded = ['id'];
    public static function defaultEntitlements(): array {
        return ['annual'=>30,'medical'=>14,'study'=>30,'compassionate'=>7,'maternity'=>84,'paternity'=>7,'other'=>7];
    }
    public static function getEntitlement(int $officerId, int $year, string $type): int {
        $ent = static::where('officer_id',$officerId)->where('year',$year)->where('leave_type',$type)->first();
        return $ent?->entitled_days ?? static::defaultEntitlements()[$type] ?? 30;
    }
}
