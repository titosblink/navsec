<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a1a; }

    .header { background: #0b1d55; color: white; padding: 16px 24px; display: flex; align-items: center; gap: 16px; }
    .header-title { font-size: 15px; font-weight: bold; letter-spacing: 0.05em; }
    .header-sub   { font-size: 10px; opacity: 0.75; margin-top: 2px; }
    .gold-bar     { height: 3px; background: linear-gradient(90deg, #ffd700, #e8a000, #ffd700); }

    .body { padding: 20px 24px; }

    .profile-top { display: flex; align-items: flex-start; gap: 20px; margin-bottom: 20px; border-bottom: 2px solid #0b1d55; padding-bottom: 16px; }
    .photo { width: 90px; height: 90px; border: 2px solid #0b1d55; object-fit: cover; }
    .photo-placeholder { width: 90px; height: 90px; background: #e8edf5; border: 2px solid #0b1d55; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: bold; color: #0b1d55; }
    .officer-name { font-size: 18px; font-weight: bold; color: #0b1d55; }
    .rank-badge { display: inline-block; background: #0b1d55; color: white; font-size: 10px; font-weight: bold; padding: 2px 8px; border-radius: 3px; margin-top: 4px; }
    .svc-no { font-family: monospace; color: #555; margin-top: 4px; }
    .status-pill { display: inline-block; background: #dcfce7; color: #166534; padding: 2px 10px; border-radius: 10px; font-size: 9px; font-weight: bold; margin-top: 6px; }

    .section { margin-bottom: 14px; }
    .section-title { background: #f0f4ff; border-left: 3px solid #0b1d55; padding: 4px 10px; font-weight: bold; font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em; color: #0b1d55; margin-bottom: 8px; }
    .grid-2 { display: flex; flex-wrap: wrap; }
    .cell { width: 50%; padding: 2px 8px 6px 0; }
    .cell-label { font-size: 9px; color: #888; text-transform: uppercase; }
    .cell-value { font-size: 11px; color: #111; font-weight: 500; margin-top: 1px; }

    .promo-grid { display: flex; flex-wrap: wrap; gap: 6px; }
    .promo-chip { border: 1px solid #0b1d55; border-radius: 4px; padding: 4px 8px; text-align: center; min-width: 70px; }
    .promo-rank { font-weight: bold; font-size: 10px; color: #0b1d55; }
    .promo-date { font-size: 9px; color: #555; margin-top: 1px; }

    .footer { margin-top: 24px; border-top: 1px solid #ddd; padding-top: 8px; text-align: center; color: #999; font-size: 9px; }
</style>
</head>
<body>
<div class="gold-bar"></div>
<div class="header">
    <div>
        <div class="header-title">NIGERIAN NAVY</div>
        <div class="header-sub">Personnel Management System — Officer Profile</div>
    </div>
</div>

<div class="body">

    <div class="profile-top">
        @if($officer->photo && file_exists(public_path('uploads/officers/'.$officer->photo)))
        <img src="{{ public_path('uploads/officers/'.$officer->photo) }}" class="photo">
        @else
        <div class="photo-placeholder">{{ strtoupper(substr($officer->name ?? 'N', 0, 2)) }}</div>
        @endif
        <div>
            <div class="officer-name">{{ $officer->name }}</div>
            <div><span class="rank-badge">{{ $officer->rank }}</span></div>
            <div class="svc-no">{{ $officer->svc_no }}</div>
            <div class="status-pill">{{ $officer->servng_rtd === 'R' ? 'Retired' : 'Serving' }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Identity & Service</div>
        <div class="grid-2">
            <div class="cell"><div class="cell-label">Branch</div><div class="cell-value">{{ $officer->branch ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">Specialisation</div><div class="cell-value">{{ $officer->spl ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">Type of Commission</div><div class="cell-value">{{ $officer->type_comm ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">Date of Birth</div><div class="cell-value">{{ $officer->dt_birth?->format('d M Y') ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">Date of Entry</div><div class="cell-value">{{ $officer->doe?->format('d M Y') ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">Date Commissioned</div><div class="cell-value">{{ $officer->date_comm?->format('d M Y') ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">Seniority Date</div><div class="cell-value">{{ $officer->snrty_date?->format('d M Y') ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">Date of Last Promotion</div><div class="cell-value">{{ $officer->dolp?->format('d M Y') ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">Gender</div><div class="cell-value">{{ $officer->gender === 'M' ? 'Male' : ($officer->gender === 'F' ? 'Female' : '—') }}</div></div>
            <div class="cell"><div class="cell-label">Blood Group</div><div class="cell-value">{{ $officer->blood_grup ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">State of Origin</div><div class="cell-value">{{ $officer->state_orig ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">Sea Time</div><div class="cell-value">{{ $officer->sea_time ? $officer->sea_time.' months' : '—' }}</div></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Current Posting</div>
        <div class="grid-2">
            <div class="cell"><div class="cell-label">Present Ship / Unit</div><div class="cell-value">{{ $officer->pres_ship ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">Command</div><div class="cell-value">{{ $officer->command ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">Appointment</div><div class="cell-value">{{ $officer->pre_appt ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">Appt Date</div><div class="cell-value">{{ $officer->appt_date?->format('d M Y') ?: '—' }}</div></div>
        </div>
    </div>

    @php $promos = array_filter(['MIDN'=>$officer->mid_ship,'AG/SLT'=>$officer->ag_slt,'SLT'=>$officer->slt,'LT'=>$officer->lt,'LT/CDR'=>$officer->ltcdr,'CDR'=>$officer->cdr,'CAPT'=>$officer->capt,'CDRE'=>$officer->cdre,'RADM'=>$officer->radm,'VADM'=>$officer->vadm,'ADM'=>$officer->adm]); @endphp
    @if(count($promos))
    <div class="section">
        <div class="section-title">Promotion Dates</div>
        <div class="promo-grid">
            @foreach($promos as $rank => $date)
            <div class="promo-chip">
                <div class="promo-rank">{{ $rank }}</div>
                <div class="promo-date">{{ $date->format('d M Y') }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Contact Information</div>
        <div class="grid-2">
            <div class="cell"><div class="cell-label">Tel No</div><div class="cell-value">{{ $officer->tel_no ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">Email</div><div class="cell-value">{{ $officer->e_mail_add ?: '—' }}</div></div>
            <div class="cell" style="width:100%;"><div class="cell-label">Address</div><div class="cell-value">{{ $officer->pre_addr ?: '—' }}</div></div>
        </div>
    </div>

    @if($officer->next_kin)
    <div class="section">
        <div class="section-title">Next of Kin</div>
        <div class="grid-2">
            <div class="cell"><div class="cell-label">Name</div><div class="cell-value">{{ $officer->next_kin }}</div></div>
            <div class="cell"><div class="cell-label">Relationship</div><div class="cell-value">{{ $officer->relatnship ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">Tel No</div><div class="cell-value">{{ $officer->nok_tel_no ?: '—' }}</div></div>
            <div class="cell"><div class="cell-label">Email</div><div class="cell-value">{{ $officer->nok_email ?: '—' }}</div></div>
        </div>
    </div>
    @endif

    <div class="footer">
        Generated by Nigerian Navy Personnel Management System · {{ now()->format('d M Y H:i') }} · CONFIDENTIAL
    </div>
</div>
</body>
</html>
