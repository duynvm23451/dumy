<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\StudentTestAttempt;
use App\Models\Test;
use App\trait\FormatResponse;
use Exception;
use Illuminate\Http\Request;

class TestController extends Controller
{
    use FormatResponse;

    public function listTests(Request $request) {
        try {
            $tests = Test::orderBy("level")->paginate($request->size ?? 10);
            $data = Helpers::paginate($tests);
            return $this->successResponse($data, 'List tests successfully');            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 404);

        }

    }

    public function getTest(int $id) {
        $test = Test::with("questions.questionAnswer")->find($id);
        if(!$test) {
            return $this->errorResponse('Test not found', [], 404);
        }

        return $this->successResponse($test, 'Get test successfully');
    }

    public function submitTest(Request $request) {
        try {
            $loggedInUser = $request->user();

            $validated = $request->validate([
                'test_id' => 'required',
                'score_achieved' => 'required',
                'completed_time' => 'required',
            ]);

            $test = Test::find($request->test_id);
            if(!$test) {
                return $this->errorResponse('Test not found', [], 404);
            }

            $testAttempt = new StudentTestAttempt($validated);
            $testAttempt->student_id = $loggedInUser->id;
            $testAttempt->save();

            return $this->successResponse($testAttempt, 'Get test successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        }
    }

    public function getTestResult(Request $request) {
        try {
            $loggedInUser = $request->user();
            $tests = Test::whereHas("student_test_attemps", function ($query) use ($loggedInUser) {
                $query->where("student_id", $loggedInUser->id);
            })
            ->with(["student_test_attemps" => function ($query) use ($loggedInUser) {
                $query->where("student_id", $loggedInUser->id);
            }])->get();

            return $this->successResponse($tests, 'List tests successfully');            

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        }
    }
}
