<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Subject; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; 

class JournalController extends Controller
{
    /**
     * Menampilkan form input jurnal
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        
        // Daftar Kelas Manual
        $allClasses = [];
        foreach (['7', '8', '9'] as $tingkat) {
            $limit = ($tingkat == '9') ? 'G' : 'H';
            for ($i = ord('A'); $i <= ord($limit); $i++) {
                $allClasses[] = $tingkat . chr($i);
            }
        }
        
        $data = [
            'user'           => $user,
            'subjects'       => Subject::all(), 
            'availableClasses' => $allClasses,
            'selectedKelas'    => $request->query('kelas', []) 
        ];

        return view('journal.create', $data);
    }

    /**
     * Menampilkan detail jurnal (Fitur Tambahan)
     */
    public function show($id)
    {
        $journal = Journal::where('teacher_id', Auth::id())->findOrFail($id);
        return response()->json($journal);
    }

    /**
     * Menyimpan data jurnal ke database dan Google Sheets
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date'           => 'required|date',
            'subject'        => 'required|array', 
            'kokurikuler_type' => 'nullable|array',
            'lesson_hours'     => 'required|array',
            'class_name'       => 'required|array',
            'class_name.*'     => 'string',
            'content'          => 'required|string',
            'notes_checklist'  => 'nullable|array',
            'notes_text'       => 'nullable|string',
        ]);

        $subjectValue = in_array('Tidak ada Jam Mengajar', $request->subject) 
                        ? 'Tidak ada Jam Mengajar' 
                        : implode(', ', $request->subject);
        
        $kokurikulerData = "";
        if (in_array('Ko-Kurikuler', $request->subject) && $request->filled('kokurikuler_type')) {
            $kokurikulerData = implode(', ', $request->kokurikuler_type);
        }

        $checklistString = $request->filled('notes_checklist') ? implode(', ', $request->notes_checklist) : '';
        $notesText = $request->notes_text ?? '';
        
        $notesParts = [];
        if ($checklistString) $notesParts[] = $checklistString;
        if ($notesText) $notesParts[] = $notesText;
        
        $finalNotesValue = implode(' | ', $notesParts);

        $allClassesString = implode(', ', $request->class_name);

        Journal::create([
            'teacher_id'     => Auth::id(),
            'class_name'     => $allClassesString, 
            'date'           => $request->date,
            'content'        => $request->content,
            'notes'          => $finalNotesValue, 
            'status'         => 'approved'
        ]);

        $url = "https://script.google.com/macros/s/AKfycbwPQKj6uD0IvER2iyWAC2PRxhMh-Wzu83_bPDGTmPbFOORxwMTna-_-dWZFbB0svNFjjQ/exec"; 
        try {
            Http::timeout(5)->post($url, [
                'action'        => 'add',
                'date'          => $request->date,
                'class_name'    => $allClassesString,
                'subject'       => $subjectValue,
                'kokurikuler'   => $kokurikulerData,
                'content_materi'=> $request->content,
                'notes'         => $finalNotesValue
            ]);
        } catch (\Exception $e) {
        }

        return redirect()->route('dashboard')->with('success', 'Jurnal berhasil disimpan!');
    }

    /**
     * Fungsi untuk menghapus data jurnal
     */
    public function destroy($id)
    {
        $journal = Journal::findOrFail($id);
        
        $url = "https://script.google.com/macros/s/AKfycbwPQKj6uD0IvER2iyWAC2PRxhMh-Wzu83_bPDGTmPbFOORxwMTna-_-dWZFbB0svNFjjQ/exec";
        try {
            Http::timeout(5)->post($url, [
                'action'     => 'delete',
                'date'       => $journal->date,
                'class_name' => $journal->class_name
            ]);
        } catch (\Exception $e) {}

        $journal->delete();

        return redirect()->route('dashboard')->with('success', 'Jurnal berhasil dihapus!');
    }
}