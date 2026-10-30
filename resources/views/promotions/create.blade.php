@extends('layouts.app')
@section('title','Promote Officer')
@section('header','Promote — ' . $officer->rank . ' ' . $officer->name)
@section('header-actions')
<a href="{{ route('officers.show',$officer) }}" class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Profile
</a>
@endsection
@section('content')
<form method="POST" action="{{ route('promotions.store',$officer) }}" class="max-w-lg">
    @csrf
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-lg mb-2">
            <div class="w-12 h-12 rounded-full bg-[#0b1d55] flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr($officer->name,0,2)) }}
            </div>
            <div>
                <p class="font-semibold text-gray-800">{{ $officer->name }}</p>
                <p class="text-sm text-gray-500">Current rank: <strong class="text-[#0b1d55]">{{ $officer->rank }}</strong> · Svc: {{ $officer->svc_no }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Rank</label>
                <input type="text" value="{{ $officer->rank }}" readonly class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Promote To <span class="text-red-500">*</span></label>
                <select name="to_rank" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="">— Select —</option>
                    @foreach($ranks as $r)
                    @if($r !== $officer->rank)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Effective Date <span class="text-red-500">*</span></label>
            <input type="date" name="effective_date" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Approved By</label>
            <input type="text" name="approved_by" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" placeholder="e.g. Chief of Naval Staff">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Authority / Letter Ref</label>
            <input type="text" name="authority" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" placeholder="e.g. NAVHQ/N1/1234/2026">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none"></textarea>
        </div>
    </div>
    <div class="mt-4 flex gap-3">
        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
            <i class="fas fa-star mr-2"></i>Confirm Promotion
        </button>
        <a href="{{ route('officers.show',$officer) }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50">Cancel</a>
    </div>
</form>
@endsection
