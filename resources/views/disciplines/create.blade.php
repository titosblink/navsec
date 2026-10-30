@extends('layouts.app')
@section('title','Add Disciplinary Record')
@section('header','Disciplinary Record — ' . $officer->name)
@section('header-actions')
<a href="{{ route('officers.show',$officer) }}" class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Profile
</a>
@endsection
@section('content')
<form method="POST" action="{{ route('disciplines.store',$officer) }}" class="max-w-xl">
    @csrf
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Case No</label>
                <input type="text" name="case_no" value="{{ old('case_no') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" placeholder="e.g. NAVHQ/DISC/001/26">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Severity</label>
                <select name="severity" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="minor" {{ old('severity')==='minor'?'selected':'' }}>Minor</option>
                    <option value="major" {{ old('severity')==='major'?'selected':'' }}>Major</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Charge / Offence <span class="text-red-500">*</span></label>
            <input type="text" name="charge" value="{{ old('charge') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Offence</label>
                <input type="date" name="date_of_offence" value="{{ old('date_of_offence') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Trial</label>
                <input type="date" name="date_of_trial" value="{{ old('date_of_trial') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Outcome</label>
            <select name="outcome" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                <option value="">— Select —</option>
                @foreach(['Guilty','Not Guilty','Dismissed','Acquitted','Cautioned'] as $oc)
                <option value="{{ $oc }}" {{ old('outcome')===$oc?'selected':'' }}>{{ $oc }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Punishment Awarded</label>
            <input type="text" name="punishment" value="{{ old('punishment') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" placeholder="e.g. 30 days detention, forfeiture of 2 months pay">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">{{ old('remarks') }}</textarea>
        </div>
    </div>
    <div class="mt-4 flex gap-3">
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
            <i class="fas fa-save mr-2"></i>Save Record
        </button>
        <a href="{{ route('officers.show',$officer) }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50">Cancel</a>
    </div>
</form>
@endsection
