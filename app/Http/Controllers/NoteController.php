<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Note;
use App\trait\FormatResponse;
use Exception;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    use FormatResponse;

    public function listNotes(Request $request) {
        $notes = Note::all();
        return $this->successResponse($notes, 'List notes successfully');
    }

    public function createNote(Request $request, int $id) {
        try {
            $validated = $request->validate([
                'content' => 'required',
                'color_code' => 'required',
                'noted_time' => 'required',
            ]);
    
            $lesson = Lesson::find($id);
    
            if(!$lesson) {
                return $this->errorResponse('Lesson not found', [], 404);
            }
    
            $note = new Note($validated);
            $note->lesson_id = $lesson->id;
            $note->save();
    
            return $this->successResponse($note, 'Create note successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        }
    

    }

    public function deleteNote(int $id) {
        $note = Note::find($id);

        if(!$note) {
            return $this->errorResponse('Note not found', [], 404);
        }

        $note->delete();

        return $this->successResponse(null, 'Delete note successfully');
    }
}
