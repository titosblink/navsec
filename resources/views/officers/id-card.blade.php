<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer ID Card — {{ $officer->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { body { margin: 0; } .no-print { display: none !important; } }
        body { background: #f3f4f6; font-family: 'Segoe UI', sans-serif; }
        .card { width: 3.375in; min-height: 2.125in; }
    </style>
</head>
<body class="p-8">
    <div class="no-print mb-6 flex gap-3">
        <button onclick="window.print()" class="bg-[#0a1640] text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-[#071230]">
            <i class="fas fa-print mr-2"></i>Print ID Card
        </button>
        <a href="{{ route('officers.show',$officer) }}" class="border border-gray-300 text-gray-600 px-5 py-2 rounded-lg text-sm hover:bg-gray-50">
            ← Back to Profile
        </a>
    </div>

    {{-- Front --}}
    <div class="flex gap-6 flex-wrap">
    <div class="card bg-gradient-to-br from-[#0b1d55] to-[#071230] rounded-2xl p-4 shadow-2xl text-white relative overflow-hidden" style="width:3.375in;min-height:2.125in;">
        {{-- Watermark anchor --}}
        <div class="absolute opacity-5 right-2 bottom-2" style="font-size:6rem;">⚓</div>
        {{-- Header --}}
        <div class="flex items-center gap-2 mb-3 border-b border-white/20 pb-2">
            <div style="width:18px;height:18px;background:rgba(255,215,0,.9);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;">⚓</div>
            <div>
                <p class="text-xs font-bold tracking-widest" style="color:#ffd700;font-size:.55rem;">NIGERIAN NAVY</p>
                <p class="text-white/70 leading-tight" style="font-size:.45rem;">PERSONNEL MANAGEMENT SYSTEM</p>
            </div>
        </div>
        {{-- Body --}}
        <div class="flex gap-3">
            <div class="flex-shrink-0">
                @if($officer->photo)
                <img src="{{ $officer->photoUrl() }}" class="rounded-lg object-cover border-2 border-white/30" style="width:52px;height:62px;">
                @else
                <div class="rounded-lg border-2 border-white/30 flex items-center justify-center text-white font-bold" style="width:52px;height:62px;background:rgba(255,255,255,0.15);font-size:1.2rem;">
                    {{ strtoupper(substr($officer->name,0,2)) }}
                </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold truncate" style="font-size:.75rem;color:#ffd700;">{{ $officer->name }}</p>
                <p class="text-white/80 mt-0.5" style="font-size:.65rem;">{{ $officer->rank }}</p>
                <p class="font-mono text-white/60 mt-0.5" style="font-size:.6rem;">{{ $officer->svc_no }}</p>
                <div class="mt-1.5 space-y-0.5">
                    @foreach(['Branch'=>$officer->branch,'Ship'=>$officer->pres_ship,'Appointment'=>$officer->pre_appt] as $lbl=>$val)
                    @if($val)
                    <p class="text-white/60" style="font-size:.55rem;"><span class="text-white/40">{{ $lbl }}:</span> {{ $val }}</p>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        {{-- Footer --}}
        <div class="mt-2 pt-1 border-t border-white/20 flex justify-between items-center">
            <p class="text-white/40" style="font-size:.5rem;">Issued: {{ now()->format('d M Y') }}</p>
            <p class="text-white/40" style="font-size:.5rem;">CONFIDENTIAL</p>
        </div>
    </div>

    {{-- Back --}}
    <div class="card bg-white rounded-2xl p-4 shadow-2xl relative overflow-hidden border border-gray-200" style="width:3.375in;min-height:2.125in;">
        <div class="absolute opacity-5 right-0 bottom-0" style="font-size:6rem;color:#0b1d55;">⚓</div>
        <p class="text-xs font-bold text-[#0b1d55] border-b border-gray-200 pb-1 mb-3 tracking-wider">PERSONAL DETAILS</p>
        <div class="space-y-1">
            @foreach(['Date of Birth'=>$officer->dt_birth?->format('d M Y'),'Blood Group'=>$officer->blood_grup,'Religion'=>$officer->religion,'State of Origin'=>$officer->state_orig,'NOK'=>$officer->next_kin,'NOK Tel'=>$officer->nok_tel_no] as $lbl=>$val)
            @if($val)
            <div class="flex gap-2 text-xs">
                <span class="text-gray-400 w-24 flex-shrink-0">{{ $lbl }}</span>
                <span class="text-gray-800 font-medium">{{ $val }}</span>
            </div>
            @endif
            @endforeach
        </div>
        @if($officer->nok_photo)
        <div class="absolute bottom-4 right-4">
            <img src="{{ $officer->nokPhotoUrl() }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200">
            <p class="text-gray-400 text-center" style="font-size:.45rem;">NOK Photo</p>
        </div>
        @endif
        <div class="absolute bottom-2 left-4">
            <p class="text-gray-300" style="font-size:.45rem;">NN Personnel Management System</p>
        </div>
    </div>
    </div>
</body>
</html>
