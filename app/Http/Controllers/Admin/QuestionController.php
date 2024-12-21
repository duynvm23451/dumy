<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Test;
use App\trait\FormatResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    use FormatResponse;

    public function createQuestion(Request $request, int $id) {
        $validated = $request->validate([
            'title' => 'required',
        ]);

        $test = Test::find($id);

        if(!$test) {
            return $this->errorResponse('Test not found', [], 404);
        }

        $question = new Question($validated);
        $question->test_id = $test->id;
        $question->save();


        return $this->successResponse($question, 'Create question successfully');

    }

    public function deleteQuestion(int $id) {
        $question = Question::find($id);

        if(!$question) {
            return $this->errorResponse('Question not found', [], 404);
        }

        $question->delete();

        return $this->successResponse(null, 'Delete question successfully');
    }
}
