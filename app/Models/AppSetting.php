<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $table    = 'app_settings';
    protected $guarded  = ['id'];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $row = static::where('key', $key)->first();
            return $row ? $row->value : $default;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting_{$key}");
    }

    public static function minYearsInRank(): array
    {
        $ranks = ['MIDN','AG/SLT','SLT','LT','LT/CDR','CDR','CAPT','CDRE','RADM','VADM'];
        $result = [];
        foreach ($ranks as $rank) {
            $result[$rank] = (int) static::get("min_years_{$rank}", 3);
        }
        return $result;
    }
}
