<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Test;
use App\trait\FormatResponse;
use Exception;
use Illuminate\Http\Request;

class TestController extends Controller
{
    use FormatResponse;

    public function listTests(Request $request) {
        $tests = Test::paginate(10);
        $data = Helpers::paginate($tests);
        return $this->successResponse($data, 'List courses successfully');
    }

    
    public function createTest(Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'min_pass_scroce' => 'required',
            'level' => 'required',
            'duration' => 'required'
        ]);

        $test = new Test($validated);
        $test->save();


        return $this->successResponse($test, 'Create test successfully');
      
    }

    public function getTest(int $id) {
        $test = Test::with("questions.questionAnswer")->find($id);
        if(!$test) {
            return $this->errorResponse('Test not found', [], 404);
        }

        return $this->successResponse($test, 'Get test successfully');
    }

    public function listResults(Request $request) {
        try {
            $tests = Test::whereHas("student_test_attemps")->with("student_test_attemps.student")->paginate($request->size ?? 10);
            $data = Helpers::paginate($tests);
            return $this->successResponse($data, 'List tests successfully');           
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        }
    }


}
