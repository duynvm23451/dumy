<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\trait\FormatResponse;
use Illuminate\Http\Request;

class QuestionAnswerController extends Controller
{
    use FormatResponse;

    public function createQuestionAnswer(Request $request, int $id) {
        $validated = $request->validate([
            'answer' => 'required',
            'is_correct' => 'required'
        ]);

        $question = Question::find($id);

        if(!$question) {
            return $this->errorResponse('Question not found', [], 404);
        }

        $questionAnswer = new QuestionAnswer($validated);
        $questionAnswer->question_id = $question->id;
        $questionAnswer->save();

        return $this->successResponse($questionAnswer, 'Create question answer successfully');

    }
}
