<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // Seed default min-years-in-rank values
        $defaults = [
            ['key'=>'min_years_MIDN',   'value'=>'1',  'group'=>'promotion', 'label'=>'Midshipman'],
            ['key'=>'min_years_AG/SLT', 'value'=>'1',  'group'=>'promotion', 'label'=>'Acting Sub-Lieutenant'],
            ['key'=>'min_years_SLT',    'value'=>'3',  'group'=>'promotion', 'label'=>'Sub-Lieutenant'],
            ['key'=>'min_years_LT',     'value'=>'5',  'group'=>'promotion', 'label'=>'Lieutenant'],
            ['key'=>'min_years_LT/CDR', 'value'=>'5',  'group'=>'promotion', 'label'=>'Lieutenant Commander'],
            ['key'=>'min_years_CDR',    'value'=>'5',  'group'=>'promotion', 'label'=>'Commander'],
            ['key'=>'min_years_CAPT',   'value'=>'5',  'group'=>'promotion', 'label'=>'Captain'],
            ['key'=>'min_years_CDRE',   'value'=>'3',  'group'=>'promotion', 'label'=>'Commodore'],
            ['key'=>'min_years_RADM',   'value'=>'3',  'group'=>'promotion', 'label'=>'Rear Admiral'],
            ['key'=>'min_years_VADM',   'value'=>'3',  'group'=>'promotion', 'label'=>'Vice Admiral'],
            ['key'=>'app_name',         'value'=>'Nigerian Navy Personnel Management System', 'group'=>'general', 'label'=>'Application Name'],
            ['key'=>'retirement_age',   'value'=>'60', 'group'=>'general',   'label'=>'Mandatory Retirement Age'],
        ];

        foreach ($defaults as $row) {
            DB::table('app_settings')->insert(array_merge($row, [
                'created_at' => now(), 'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
