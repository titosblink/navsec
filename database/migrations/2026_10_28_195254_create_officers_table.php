<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('officers', function (Blueprint $table) {
            $table->id();
            // Status & serving
            $table->string('servng_rtd', 10)->nullable()->comment('S=Serving, R=Retired');
            // Identity
            $table->string('rank', 20)->nullable();
            $table->string('initials', 20)->nullable();
            $table->string('name')->nullable();
            $table->string('svc_no', 30)->unique()->nullable();
            // Course & conversion
            $table->string('course', 50)->nullable();
            $table->string('course_cod', 20)->nullable();
            $table->string('tel_no', 30)->nullable();
            $table->string('conversion', 50)->nullable();
            $table->string('conve_code', 20)->nullable();
            $table->string('sep_code', 20)->nullable();
            $table->date('dolp')->nullable()->comment('Date of Last Promotion');
            // Ship/Command
            $table->string('pres_ship', 100)->nullable();
            $table->string('ship_code', 20)->nullable();
            $table->string('command', 100)->nullable();
            $table->string('cmd_code', 20)->nullable();
            // Current appointment
            $table->string('pre_appt', 100)->nullable();
            $table->string('appt_code', 20)->nullable();
            $table->date('appt_date')->nullable();
            $table->date('date_joined_ship')->nullable();
            // Seniority
            $table->string('r_code', 20)->nullable();
            $table->date('snrty_date')->nullable();
            // Branch / Specialisation
            $table->string('branch', 50)->nullable();
            $table->string('bran_code', 20)->nullable();
            $table->string('spl', 50)->nullable();
            $table->string('spl_code', 20)->nullable();
            $table->string('sub_spl', 50)->nullable();
            $table->string('s_spl_code', 20)->nullable();
            $table->string('qual', 100)->nullable();
            // Commission
            $table->string('type_comm', 50)->nullable();
            $table->string('comm_code', 20)->nullable();
            $table->date('doe')->nullable()->comment('Date of Entry');
            $table->date('dt_birth')->nullable();
            $table->date('dt_cdt_trg_started')->nullable();
            $table->date('date_comm')->nullable()->comment('Date Commissioned');
            // Rank promotion dates
            $table->date('mid_ship')->nullable();
            $table->date('ag_slt')->nullable();
            $table->date('slt')->nullable();
            $table->date('lt')->nullable();
            $table->date('ltcdr')->nullable();
            $table->date('cdr')->nullable();
            $table->date('capt')->nullable();
            $table->date('cdre')->nullable();
            $table->date('radm')->nullable();
            $table->date('vadm')->nullable();
            $table->date('adm')->nullable();
            $table->integer('per_year')->nullable();
            // Family
            $table->string('names1', 100)->nullable()->comment('Spouse name');
            $table->string('child_1', 100)->nullable();
            $table->string('child_2', 100)->nullable();
            $table->string('child_3', 100)->nullable();
            $table->string('child_4', 100)->nullable();
            // Personal
            $table->string('sel_code', 20)->nullable();
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->string('blood_grup', 5)->nullable();
            $table->string('state_code', 20)->nullable();
            $table->string('state_orig', 50)->nullable();
            $table->string('geo_p_zone', 50)->nullable();
            $table->string('religion', 30)->nullable();
            $table->string('home_town', 100)->nullable();
            $table->string('lga', 100)->nullable();
            // Contact
            $table->text('pre_addr')->nullable()->comment('Present Address');
            $table->string('e_mail_add', 100)->nullable();
            // Next of Kin
            $table->string('next_kin', 100)->nullable();
            $table->string('relatnship', 50)->nullable();
            $table->text('nok_add')->nullable();
            $table->string('nok_tel_no', 30)->nullable();
            $table->string('nok_email', 100)->nullable();
            // Alternate NOK
            $table->string('alt_nok', 100)->nullable();
            $table->string('alt_nokrel', 50)->nullable();
            $table->text('alt_nok_add')->nullable();
            $table->string('alt_phone', 30)->nullable();
            $table->string('alt_email', 100)->nullable();
            // Records / Status
            $table->string('status', 30)->nullable();
            $table->date('rod')->nullable()->comment('Run Out Date');
            $table->text('exam_fail')->nullable();
            $table->text('dis_cases')->nullable();
            $table->text('d_ment')->nullable();
            $table->integer('sea_time')->nullable()->comment('Sea time in months');
            $table->text('med_cases')->nullable();
            $table->string('r_cieling', 20)->nullable();
            $table->date('term_leave')->nullable();
            $table->date('retired_dt')->nullable();
            // Mission
            $table->string('msion_area', 100)->nullable();
            $table->date('msion_date')->nullable();
            $table->string('msion_recd', 50)->nullable();
            // Remarks
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officers');
    }
};
