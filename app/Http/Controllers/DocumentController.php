<?php
namespace App\Http\Controllers;
use App\Models\Officer;
use App\Models\OfficerDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DocumentController extends Controller {
    public function create(Officer $officer) {
        return view('documents.create', ['officer' => $officer, 'types' => OfficerDocument::types()]);
    }
    public function store(Request $request, Officer $officer) {
        $request->validate(['title'=>'required','document_type'=>'required','file'=>'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx']);
        $file = $request->file('file');
        $fname = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $dest  = public_path('uploads/documents');
        File::ensureDirectoryExists($dest);
        $file->move($dest, $fname);
        OfficerDocument::create(['officer_id'=>$officer->id,'title'=>$request->title,'document_type'=>$request->document_type,'file_path'=>$fname,'original_name'=>$file->getClientOriginalName(),'file_size'=>$file->getSize(),'remarks'=>$request->remarks,'uploaded_by'=>auth()->id()]);
        return redirect()->route('officers.show', $officer)->with('success', 'Document uploaded.');
    }
    public function destroy(OfficerDocument $document) {
        $id = $document->officer_id;
        $path = public_path('uploads/documents/' . $document->file_path);
        if (File::exists($path)) File::delete($path);
        $document->delete();
        return redirect()->route('officers.show', $id)->with('success', 'Document deleted.');
    }
}
