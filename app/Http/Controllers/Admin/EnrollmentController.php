<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\trait\FormatResponse;
use Exception;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    use FormatResponse;

    public function listEnrollment(Request $request) {
        try {
            $courses = Course::whereHas("enrollments")->with("enrollments.student")->paginate($request->size ?? 10);
            $data = Helpers::paginate($courses);
            return $this->successResponse($data, 'List tests successfully');           
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        }
    }
}
