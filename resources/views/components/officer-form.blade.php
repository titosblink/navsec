{{-- Multi-step officer form. Expects: $officer (nullable), $ranks, $branches, $states, $ships --}}
@php $o = $officer ?? null; @endphp

<div id="officer-wizard">

{{-- ── Step progress bar ── --}}
<div class="bg-white rounded-xl shadow-sm mb-4 px-6 py-4 no-print">
    <div class="flex items-center justify-between relative">
        <div class="absolute left-0 right-0 top-4 h-0.5 bg-gray-200 z-0 mx-8"></div>
        <div id="wizard-progress-line" class="absolute left-0 top-4 h-0.5 bg-blue-600 z-0 ml-8 transition-all duration-500" style="width:0%"></div>

        @php
        $steps = [
            ['icon'=>'fa-id-badge',      'label'=>'Identity'],
            ['icon'=>'fa-calendar-days', 'label'=>'Service Dates'],
            ['icon'=>'fa-star',          'label'=>'Promotions'],
            ['icon'=>'fa-ship',          'label'=>'Posting'],
            ['icon'=>'fa-user',          'label'=>'Personal'],
            ['icon'=>'fa-heart',         'label'=>'Family & NOK'],
            ['icon'=>'fa-clipboard',     'label'=>'Records'],
        ];
        @endphp

        @foreach($steps as $i => $step)
        <div class="wizard-step-dot flex flex-col items-center z-10 cursor-pointer" data-step="{{ $i + 1 }}" onclick="goToStep({{ $i + 1 }})">
            <div class="w-8 h-8 rounded-full flex items-center justify-center border-2 transition-all duration-300
                        {{ $i === 0 ? 'bg-blue-600 border-blue-600' : 'bg-white border-gray-300' }}"
                 id="step-dot-{{ $i + 1 }}">
                <i class="fas {{ $step['icon'] }} text-xs {{ $i === 0 ? 'text-white' : 'text-gray-400' }}" id="step-dot-icon-{{ $i + 1 }}"></i>
            </div>
            <span class="text-xs mt-1.5 font-medium hidden md:block transition-colors
                         {{ $i === 0 ? 'text-blue-600' : 'text-gray-400' }}"
                  id="step-label-{{ $i + 1 }}">{{ $step['label'] }}</span>
        </div>
        @endforeach
    </div>
    <div class="flex items-center justify-between mt-3">
        <p class="text-xs text-gray-500" id="step-counter">Step 1 of 7</p>
        <p class="text-xs font-semibold text-blue-700" id="step-title-display">Identity & Branch</p>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     STEP 1 — Identity & Branch
══════════════════════════════════════════════════════ --}}
<div class="wizard-panel" data-panel="1">
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center"><i class="fas fa-id-badge text-blue-600 text-sm"></i></div>
        <div><h3 class="font-semibold text-gray-800 text-sm">Identity & Branch</h3><p class="text-xs text-gray-400">Core identification and branch details</p></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">

        {{-- Officer photo --}}
        <div class="md:col-span-3 mb-2">
            <label class="flabel">Officer Photo</label>
            <div class="flex items-center gap-5">
                <div class="w-24 h-24 rounded-xl overflow-hidden border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center flex-shrink-0 cursor-pointer relative group"
                     onclick="document.getElementById('photo-input').click()">
                    @if($o?->photo)
                    <img id="photo-preview" src="{{ $o->photoUrl() }}" class="w-full h-full object-cover">
                    @else
                    <img id="photo-preview" src="" class="w-full h-full object-cover hidden">
                    @endif
                    <div id="photo-placeholder" class="{{ $o?->photo ? 'hidden' : '' }} text-center text-gray-400 p-2">
                        <i class="fas fa-camera text-2xl mb-1 block"></i>
                        <span class="text-xs">Upload</span>
                    </div>
                    <div class="absolute inset-0 bg-black/40 hidden group-hover:flex items-center justify-center rounded-xl">
                        <i class="fas fa-camera text-white text-xl"></i>
                    </div>
                </div>
                <div>
                    <input type="file" id="photo-input" name="photo" accept="image/*" class="hidden"
                           onchange="previewImage(this,'photo-preview','photo-placeholder')">
                    <button type="button" onclick="document.getElementById('photo-input').click()"
                            class="bg-blue-50 text-blue-700 border border-blue-200 text-sm px-4 py-2 rounded-lg hover:bg-blue-100 transition-colors font-medium">
                        <i class="fas fa-upload mr-2"></i>{{ $o?->photo ? 'Change Photo' : 'Choose Photo' }}
                    </button>
                    <p class="text-xs text-gray-400 mt-1.5">JPG, PNG or WEBP · Max 2MB</p>
                    @if($o?->photo)
                    <p class="text-xs text-green-600 mt-1"><i class="fas fa-check-circle mr-1"></i>Photo uploaded</p>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <label class="flabel">Service No <span class="text-red-500">*</span></label>
            <input type="text" name="svc_no" value="{{ old('svc_no', $o?->svc_no) }}" required class="finput" placeholder="e.g. NN/0001">
        </div>
        <div>
            <label class="flabel">Rank <span class="text-red-500">*</span></label>
            <select name="rank" required class="finput select2">
                <option value="">— Select Rank —</option>
                @foreach($ranks as $r)<option value="{{ $r }}" {{ old('rank',$o?->rank)==$r?'selected':'' }}>{{ $r }}</option>@endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="flabel">Full Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $o?->name) }}" required class="finput" placeholder="SURNAME Firstname Middlename">
        </div>
        <div>
            <label class="flabel">Serving / Retired</label>
            <select name="servng_rtd" class="finput">
                <option value="S" {{ old('servng_rtd',$o?->servng_rtd??'S')=='S'?'selected':'' }}>Serving</option>
                <option value="R" {{ old('servng_rtd',$o?->servng_rtd)=='R'?'selected':'' }}>Retired</option>
            </select>
        </div>
        <div>
            <label class="flabel">Branch</label>
            <select name="branch" class="finput select2">
                <option value="">— Select Branch —</option>
                @foreach($branches as $code => $name)
                <option value="{{ $code }}" {{ old('branch',$o?->branch)===$code?'selected':'' }}>{{ $code }} — {{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="flabel">Specialisation</label><input type="text" name="spl" value="{{ old('spl',$o?->spl) }}" class="finput"></div>
        <div><label class="flabel">Sub-Specialisation</label><input type="text" name="sub_spl" value="{{ old('sub_spl',$o?->sub_spl) }}" class="finput"></div>
        <div><label class="flabel">Qualification</label><input type="text" name="qual" value="{{ old('qual',$o?->qual) }}" class="finput"></div>
        <div><label class="flabel">Telephone</label><input type="text" name="tel_no" value="{{ old('tel_no',$o?->tel_no) }}" class="finput"></div>
    </div>
</div>
</div>

{{-- ══════════════════════════════════════════════════════
     STEP 2 — Service & Commission Dates
══════════════════════════════════════════════════════ --}}
<div class="wizard-panel hidden" data-panel="2">
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center"><i class="fas fa-calendar-days text-green-600 text-sm"></i></div>
        <div><h3 class="font-semibold text-gray-800 text-sm">Service & Commission Dates</h3><p class="text-xs text-gray-400">Entry and commission details</p></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
        <div>
            <label class="flabel">Type of Commission</label>
            <select name="type_comm" class="finput">
                <option value="">— Select —</option>
                @foreach(['RCC','DRC','DSSC','SD','Concessional'] as $tc)
                <option value="{{ $tc }}" {{ old('type_comm',$o?->type_comm)===$tc?'selected':'' }}>{{ $tc }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="flabel">Course Number</label>
            <input type="number" name="course_cod" value="{{ old('course_cod',$o?->course_cod) }}"
                   class="finput" placeholder="e.g. 42" min="1" step="1"
                   oninput="this.value=this.value.replace(/[^0-9]/g,'')">
        </div>
        <div><label class="flabel">Date of Birth</label><input type="date" name="dt_birth" value="{{ old('dt_birth',$o?->dt_birth?->format('Y-m-d')) }}" class="finput"></div>
        <div><label class="flabel">Date of Entry</label><input type="date" name="doe" value="{{ old('doe',$o?->doe?->format('Y-m-d')) }}" class="finput"></div>
        <div><label class="flabel">Date Commissioned</label><input type="date" name="date_comm" value="{{ old('date_comm',$o?->date_comm?->format('Y-m-d')) }}" class="finput"></div>
        <div><label class="flabel">Seniority Date</label><input type="date" name="snrty_date" value="{{ old('snrty_date',$o?->snrty_date?->format('Y-m-d')) }}" class="finput"></div>
        <div><label class="flabel">Date of Last Promotion (DOLP)</label><input type="date" name="dolp" value="{{ old('dolp',$o?->dolp?->format('Y-m-d')) }}" class="finput"></div>
    </div>
</div>
</div>

{{-- ══════════════════════════════════════════════════════
     STEP 3 — Rank Promotion Dates
══════════════════════════════════════════════════════ --}}
<div class="wizard-panel hidden" data-panel="3">
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center"><i class="fas fa-star text-amber-600 text-sm"></i></div>
        <div><h3 class="font-semibold text-gray-800 text-sm">Rank Promotion Dates</h3><p class="text-xs text-gray-400">Date reached each rank</p></div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6">
        @foreach(['mid_ship'=>'Midshipman','ag_slt'=>'Ag/SLT','slt'=>'SLT','lt'=>'LT','ltcdr'=>'LT/CDR','cdr'=>'CDR','capt'=>'CAPT','cdre'=>'CDRE','radm'=>'RADM','vadm'=>'VADM','adm'=>'ADM'] as $field => $label)
        <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
            <label class="flabel !mb-1">{{ $label }}</label>
            <input type="date" name="{{ $field }}" value="{{ old($field,$o?->{$field}?->format('Y-m-d')) }}" class="finput !py-1.5">
        </div>
        @endforeach
    </div>
</div>
</div>

{{-- ══════════════════════════════════════════════════════
     STEP 4 — Current Ship / Appointment
══════════════════════════════════════════════════════ --}}
<div class="wizard-panel hidden" data-panel="4">
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center"><i class="fas fa-ship text-blue-600 text-sm"></i></div>
        <div><h3 class="font-semibold text-gray-800 text-sm">Current Ship / Appointment</h3><p class="text-xs text-gray-400">Present posting details</p></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
        <div class="md:col-span-2">
            <label class="flabel">Present Ship / Unit / Establishment</label>
            <select name="pres_ship" class="finput select2">
                <option value="">— Select Ship / Unit —</option>
                @foreach($ships as $ship)
                <option value="{{ $ship->name }}" {{ old('pres_ship',$o?->pres_ship)===$ship->name?'selected':'' }}>
                    {{ $ship->name }}@if($ship->command) ({{ $ship->command }})@endif
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="flabel">Command</label>
            <select name="command" class="finput">
                <option value="">— Select Command —</option>
                @foreach(['NHQ','WNC','ENC','CNC','NAVTRAC','LOG COMMAND','NAVDOC','SOC'] as $cmd)
                <option value="{{ $cmd }}" {{ old('command',$o?->command)===$cmd?'selected':'' }}>{{ $cmd }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="flabel">Present Appointment</label>
            <input type="text" name="pre_appt" value="{{ old('pre_appt',$o?->pre_appt) }}" class="finput">
        </div>
        <div>
            <label class="flabel">Appointment Date</label>
            <input type="date" name="appt_date" value="{{ old('appt_date',$o?->appt_date?->format('Y-m-d')) }}" class="finput">
        </div>
        <div>
            <label class="flabel">Date Joined Ship</label>
            <input type="date" name="date_joined_ship" value="{{ old('date_joined_ship',$o?->date_joined_ship?->format('Y-m-d')) }}" class="finput">
        </div>
    </div>
</div>
</div>

{{-- ══════════════════════════════════════════════════════
     STEP 5 — Personal Information
══════════════════════════════════════════════════════ --}}
<div class="wizard-panel hidden" data-panel="5">
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center"><i class="fas fa-user text-purple-600 text-sm"></i></div>
        <div><h3 class="font-semibold text-gray-800 text-sm">Personal Information</h3><p class="text-xs text-gray-400">Demographics and contact details</p></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
        <div>
            <label class="flabel">Gender</label>
            <select name="gender" class="finput">
                <option value="">Select</option>
                <option value="M" {{ old('gender',$o?->gender)=='M'?'selected':'' }}>Male</option>
                <option value="F" {{ old('gender',$o?->gender)=='F'?'selected':'' }}>Female</option>
            </select>
        </div>
        <div>
            <label class="flabel">Blood Group</label>
            <select name="blood_grup" class="finput">
                <option value="">Select</option>
                @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                <option value="{{ $bg }}" {{ old('blood_grup',$o?->blood_grup)==$bg?'selected':'' }}>{{ $bg }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="flabel">Religion</label>
            <select name="religion" class="finput">
                <option value="">Select</option>
                @foreach(['Christianity','Islam','Traditional','Other'] as $rel)
                <option value="{{ $rel }}" {{ old('religion',$o?->religion)==$rel?'selected':'' }}>{{ $rel }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="flabel">State of Origin</label>
            <select name="state_orig" id="state_orig_select" class="finput" onchange="loadLgas(this.value)">
                <option value="">— Select State —</option>
                @foreach($states as $s)<option value="{{ $s }}" {{ old('state_orig',$o?->state_orig)==$s?'selected':'' }}>{{ $s }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="flabel">LGA <span class="text-xs text-gray-400 font-normal">(loads after state is selected)</span></label>
            <select name="lga" id="lga_select" class="finput">
                <option value="">— Select State first —</option>
                @if(old('lga', $o?->lga))
                <option value="{{ old('lga', $o?->lga) }}" selected>{{ old('lga', $o?->lga) }}</option>
                @endif
            </select>
        </div>
        <div>
            <label class="flabel">Geo-Political Zone</label>
            <select name="geo_p_zone" class="finput">
                <option value="">Select</option>
                @foreach(['North Central','North East','North West','South East','South South','South West'] as $z)
                <option value="{{ $z }}" {{ old('geo_p_zone',$o?->geo_p_zone)==$z?'selected':'' }}>{{ $z }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="flabel">Home Town</label><input type="text" name="home_town" value="{{ old('home_town',$o?->home_town) }}" class="finput"></div>
        <div><label class="flabel">Email Address</label><input type="email" name="e_mail_add" value="{{ old('e_mail_add',$o?->e_mail_add) }}" class="finput"></div>
        <div class="md:col-span-3">
            <label class="flabel">Present Address</label>
            <textarea name="pre_addr" rows="2" class="finput">{{ old('pre_addr',$o?->pre_addr) }}</textarea>
        </div>
    </div>
</div>
</div>

{{-- ══════════════════════════════════════════════════════
     STEP 6 — Family & Next of Kin
══════════════════════════════════════════════════════ --}}
<div class="wizard-panel hidden" data-panel="6">
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
        <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center"><i class="fas fa-heart text-pink-600 text-sm"></i></div>
        <div><h3 class="font-semibold text-gray-800 text-sm">Family & Next of Kin</h3><p class="text-xs text-gray-400">Spouse, children, and emergency contacts</p></div>
    </div>
    <div class="p-6 space-y-5">

        {{-- Family --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-3"><p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Family</p></div>
            <div><label class="flabel">Spouse Name</label><input type="text" name="names1" value="{{ old('names1',$o?->names1) }}" class="finput"></div>
            @foreach([1,2,3,4] as $n)
            <div><label class="flabel">Child {{ $n }}</label><input type="text" name="child_{{ $n }}" value="{{ old('child_'.$n,$o?->{'child_'.$n}) }}" class="finput"></div>
            @endforeach
        </div>

        {{-- Primary NOK --}}
        <div class="border-t border-gray-100 pt-5 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-3"><p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Next of Kin (Primary)</p></div>

            {{-- NOK photo --}}
            <div class="md:col-span-3 mb-1">
                <label class="flabel">NOK Photo</label>
                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 rounded-xl overflow-hidden border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center flex-shrink-0 cursor-pointer relative group"
                         onclick="document.getElementById('nok-photo-input').click()">
                        @if($o?->nok_photo)
                        <img id="nok-photo-preview" src="{{ $o->nokPhotoUrl() }}" class="w-full h-full object-cover">
                        @else
                        <img id="nok-photo-preview" src="" class="w-full h-full object-cover hidden">
                        @endif
                        <div id="nok-photo-placeholder" class="{{ $o?->nok_photo ? 'hidden' : '' }} text-center text-gray-400 p-2">
                            <i class="fas fa-camera text-xl mb-0.5 block"></i>
                            <span class="text-xs">Upload</span>
                        </div>
                        <div class="absolute inset-0 bg-black/40 hidden group-hover:flex items-center justify-center rounded-xl">
                            <i class="fas fa-camera text-white"></i>
                        </div>
                    </div>
                    <div>
                        <input type="file" id="nok-photo-input" name="nok_photo" accept="image/*" class="hidden"
                               onchange="previewImage(this,'nok-photo-preview','nok-photo-placeholder')">
                        <button type="button" onclick="document.getElementById('nok-photo-input').click()"
                                class="bg-pink-50 text-pink-700 border border-pink-200 text-sm px-4 py-2 rounded-lg hover:bg-pink-100 transition-colors font-medium">
                            <i class="fas fa-upload mr-2"></i>{{ $o?->nok_photo ? 'Change NOK Photo' : 'Upload NOK Photo' }}
                        </button>
                        <p class="text-xs text-gray-400 mt-1.5">JPG, PNG or WEBP · Max 2MB</p>
                    </div>
                </div>
            </div>

            <div><label class="flabel">Name</label><input type="text" name="next_kin" value="{{ old('next_kin',$o?->next_kin) }}" class="finput"></div>
            <div><label class="flabel">Relationship</label><input type="text" name="relatnship" value="{{ old('relatnship',$o?->relatnship) }}" class="finput"></div>
            <div><label class="flabel">Tel No</label><input type="text" name="nok_tel_no" value="{{ old('nok_tel_no',$o?->nok_tel_no) }}" class="finput"></div>
            <div><label class="flabel">Email</label><input type="email" name="nok_email" value="{{ old('nok_email',$o?->nok_email) }}" class="finput"></div>
            <div class="md:col-span-2"><label class="flabel">Address</label><textarea name="nok_add" rows="2" class="finput">{{ old('nok_add',$o?->nok_add) }}</textarea></div>
        </div>

        {{-- Alternate NOK --}}
        <div class="border-t border-gray-100 pt-5 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-3"><p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Next of Kin (Alternate)</p></div>
            <div><label class="flabel">Name</label><input type="text" name="alt_nok" value="{{ old('alt_nok',$o?->alt_nok) }}" class="finput"></div>
            <div><label class="flabel">Relationship</label><input type="text" name="alt_nokrel" value="{{ old('alt_nokrel',$o?->alt_nokrel) }}" class="finput"></div>
            <div><label class="flabel">Tel No</label><input type="text" name="alt_phone" value="{{ old('alt_phone',$o?->alt_phone) }}" class="finput"></div>
            <div><label class="flabel">Email</label><input type="email" name="alt_email" value="{{ old('alt_email',$o?->alt_email) }}" class="finput"></div>
            <div class="md:col-span-2"><label class="flabel">Address</label><textarea name="alt_nok_add" rows="2" class="finput">{{ old('alt_nok_add',$o?->alt_nok_add) }}</textarea></div>
        </div>

    </div>
</div>
</div>

{{-- ══════════════════════════════════════════════════════
     STEP 7 — Service Records & Remarks
══════════════════════════════════════════════════════ --}}
<div class="wizard-panel hidden" data-panel="7">
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center"><i class="fas fa-clipboard text-red-600 text-sm"></i></div>
        <div><h3 class="font-semibold text-gray-800 text-sm">Service Records & Remarks</h3><p class="text-xs text-gray-400">Administrative and separation records</p></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
        <div><label class="flabel">Run Out Date (ROD)</label><input type="date" name="rod" value="{{ old('rod',$o?->rod?->format('Y-m-d')) }}" class="finput"></div>
        <div><label class="flabel">Retirement Date</label><input type="date" name="retired_dt" value="{{ old('retired_dt',$o?->retired_dt?->format('Y-m-d')) }}" class="finput"></div>
        <div><label class="flabel">Terminal Leave Date</label><input type="date" name="term_leave" value="{{ old('term_leave',$o?->term_leave?->format('Y-m-d')) }}" class="finput"></div>
        <div><label class="flabel">Sea Time (months)</label><input type="number" name="sea_time" value="{{ old('sea_time',$o?->sea_time) }}" class="finput" min="0"></div>
        <div><label class="flabel">Rank Ceiling</label><input type="text" name="r_cieling" value="{{ old('r_cieling',$o?->r_cieling) }}" class="finput"></div>
        <div><label class="flabel">Mission Area</label><input type="text" name="msion_area" value="{{ old('msion_area',$o?->msion_area) }}" class="finput"></div>
        <div><label class="flabel">Mission Date</label><input type="date" name="msion_date" value="{{ old('msion_date',$o?->msion_date?->format('Y-m-d')) }}" class="finput"></div>
        <div class="md:col-span-3"><label class="flabel">Exam Failures</label><textarea name="exam_fail" rows="2" class="finput">{{ old('exam_fail',$o?->exam_fail) }}</textarea></div>
        <div class="md:col-span-3"><label class="flabel">Disciplinary Cases</label><textarea name="dis_cases" rows="2" class="finput">{{ old('dis_cases',$o?->dis_cases) }}</textarea></div>
        <div class="md:col-span-3"><label class="flabel">Medical Cases</label><textarea name="med_cases" rows="2" class="finput">{{ old('med_cases',$o?->med_cases) }}</textarea></div>
        <div class="md:col-span-3"><label class="flabel">Remarks</label><textarea name="remarks" rows="2" class="finput">{{ old('remarks',$o?->remarks) }}</textarea></div>
    </div>
</div>
</div>

</div>{{-- /wizard --}}

{{-- ── Navigation buttons ── --}}
<div class="flex items-center justify-between mt-5">
    <button type="button" id="btn-prev" onclick="changeStep(-1)"
            class="hidden items-center gap-2 border border-gray-300 text-gray-600 px-5 py-2.5 rounded-lg hover:bg-gray-50 font-medium transition-colors">
        <i class="fas fa-arrow-left text-sm"></i> Previous
    </button>
    <div class="flex-1"></div>
    <button type="button" id="btn-next" onclick="changeStep(1)"
            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
        Next <i class="fas fa-arrow-right text-sm"></i>
    </button>
    <button type="submit" id="btn-submit"
            class="hidden items-center gap-2 bg-[#0a1640] hover:bg-[#071230] text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
        <i class="fas fa-save mr-1"></i> Save Officer
    </button>
</div>

<style>
.flabel { display:block; font-size:.8rem; font-weight:600; color:#374151; margin-bottom:.3rem; }
.finput  { width:100%; border:1px solid #d1d5db; border-radius:.5rem; padding:.5rem .75rem; font-size:.875rem; color:#111827; background:#fff; transition:border-color .15s,box-shadow .15s; outline:none; }
.finput:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.15); }
.finput:hover { border-color:#9ca3af; }
</style>

{{-- ── Embedded LGA data + helpers ── --}}
<script>
const NIGERIA_LGAS = {!! json_encode(config('nigeria_lgas'), JSON_UNESCAPED_UNICODE) !!};
const SAVED_LGA    = {{ json_encode(old('lga', $o?->lga)) }};

function loadLgas(state) {
    const sel = document.getElementById('lga_select');
    sel.innerHTML = '';
    if (!state || !NIGERIA_LGAS[state]) {
        sel.innerHTML = '<option value="">— Select State first —</option>';
        return;
    }
    const placeholder = document.createElement('option');
    placeholder.value = ''; placeholder.textContent = '— Select LGA —';
    sel.appendChild(placeholder);
    NIGERIA_LGAS[state].forEach(lga => {
        const opt = document.createElement('option');
        opt.value = lga; opt.textContent = lga;
        if (lga === SAVED_LGA) opt.selected = true;
        sel.appendChild(opt);
    });
}

function previewImage(input, previewId, placeholderId) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById(previewId);
        const ph  = document.getElementById(placeholderId);
        img.src = e.target.result;
        img.classList.remove('hidden');
        if (ph) ph.classList.add('hidden');
    };
    reader.readAsDataURL(file);
}

document.addEventListener('DOMContentLoaded', function () {
    const stateSelect = document.getElementById('state_orig_select');
    if (stateSelect && stateSelect.value) loadLgas(stateSelect.value);
});
</script>

{{-- ── Wizard step navigation ── --}}
<script>
(function(){
    const TOTAL  = 7;
    const titles = ['Identity & Branch','Service & Commission Dates','Rank Promotion Dates',
                    'Current Ship / Posting','Personal Information','Family & Next of Kin','Service Records'];
    let current = 1;

    @if($errors->any())
    const errFields = {!! json_encode($errors->keys()) !!};
    const stepFields = {
        1: ['svc_no','rank','name','servng_rtd','branch','spl','sub_spl','qual','tel_no'],
        2: ['type_comm','dt_birth','doe','date_comm','snrty_date','dolp'],
        3: ['mid_ship','ag_slt','slt','lt','ltcdr','cdr','capt','cdre','radm','vadm','adm'],
        4: ['pres_ship','command','pre_appt','appt_date','date_joined_ship'],
        5: ['gender','blood_grup','religion','state_orig','lga','geo_p_zone','home_town','e_mail_add','pre_addr'],
        6: ['names1','child_1','child_2','child_3','child_4','next_kin','relatnship','nok_tel_no','nok_email','nok_add','alt_nok','alt_nokrel','alt_phone','alt_email','alt_nok_add'],
        7: ['rod','retired_dt','term_leave','sea_time','r_cieling','msion_area','msion_date','exam_fail','dis_cases','med_cases','remarks'],
    };
    for (let s = 1; s <= 7; s++) {
        if (errFields.some(f => stepFields[s]?.includes(f))) { current = s; break; }
    }
    @endif

    window.goToStep = function(n) {
        if (n < 1 || n > TOTAL) return;
        document.querySelectorAll('.wizard-panel').forEach(p => p.classList.add('hidden'));
        document.querySelector(`[data-panel="${n}"]`).classList.remove('hidden');

        for (let i = 1; i <= TOTAL; i++) {
            const dot  = document.getElementById(`step-dot-${i}`);
            const icon = document.getElementById(`step-dot-icon-${i}`);
            const lbl  = document.getElementById(`step-label-${i}`);
            if (i < n) {
                dot.style.cssText  = 'background:#16a34a;border-color:#16a34a;';
                icon.className     = 'fas fa-check text-white text-xs';
                if (lbl) lbl.style.color = '#16a34a';
            } else if (i === n) {
                dot.style.cssText  = 'background:#2563eb;border-color:#2563eb;';
                icon.className     = 'fas fa-circle text-white text-xs';
                if (lbl) { lbl.style.color = '#2563eb'; lbl.style.fontWeight = '700'; }
            } else {
                dot.style.cssText  = 'background:#fff;border-color:#d1d5db;';
                icon.className     = icon.className.replace(/text-(white|gray-400)/, 'text-gray-400');
                if (lbl) { lbl.style.color = '#9ca3af'; lbl.style.fontWeight = '500'; }
            }
        }

        const pct = ((n - 1) / (TOTAL - 1)) * 100;
        document.getElementById('wizard-progress-line').style.width = pct + '%';
        document.getElementById('step-counter').textContent        = `Step ${n} of ${TOTAL}`;
        document.getElementById('step-title-display').textContent  = titles[n - 1];

        document.getElementById('btn-prev').style.display   = n > 1     ? 'flex' : 'none';
        document.getElementById('btn-next').style.display   = n < TOTAL ? 'flex' : 'none';
        document.getElementById('btn-submit').style.display = n === TOTAL ? 'flex' : 'none';

        current = n;
        window.scrollTo({ top: 0, behavior: 'smooth' });
        if (typeof $ !== 'undefined') setTimeout(() => $('.select2').select2({ width: '100%' }), 50);
    };

    window.changeStep = function(dir) { goToStep(current + dir); };

    document.addEventListener('DOMContentLoaded', () => goToStep(current));

    // ── Unsaved changes warning (item 8) ──
    let formDirty = false;
    document.querySelectorAll('#officer-wizard input, #officer-wizard select, #officer-wizard textarea').forEach(el => {
        el.addEventListener('change', () => { formDirty = true; });
        el.addEventListener('input',  () => { formDirty = true; });
    });
    document.querySelector('form')?.addEventListener('submit', () => { formDirty = false; });
    window.addEventListener('beforeunload', e => {
        if (!formDirty) return;
        e.preventDefault();
        e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
    });
})();
</script>

{{-- ── Field helper hints (item 17) ── --}}
<style>
.field-hint { font-size: .7rem; color: #6b7280; margin-top: 2px; display: block; }
</style>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const hints = {
        'svc_no':      'Format: N/A/00001 or NN/0001',
        'name':        'Enter in CAPITALS: SURNAME Firstname Middlename',
        'dolp':        'Date of Last Promotion — used for eligibility calculations',
        'snrty_date':  'Used to order the official Seniority List',
        'dt_birth':    'Must be earlier than Date of Entry',
        'date_comm':   'Date formally commissioned as an officer',
        'course_cod':  'Course / intake number (numeric)',
        'e_mail_add':  'Official e-mail address',
    };
    for (const [name, hint] of Object.entries(hints)) {
        const el = document.querySelector(`[name="${name}"]`);
        if (el) {
            const span = document.createElement('span');
            span.className = 'field-hint';
            span.textContent = hint;
            el.parentNode.appendChild(span);
        }
    }
});
</script>
