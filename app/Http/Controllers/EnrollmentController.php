<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\trait\FormatResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    use FormatResponse;

    public function registerCourse(Request $request) {
        $validated = $request->validate([
            'course_id' => 'required',
            'student_id' => 'required',
        ]);

        $course = Course::find($request->course_id);

        if(!$course) {
            return $this->errorResponse('Course not found', [], 404);
        }

        $enrollment = Enrollment::where("course_id", $request->course_id)
                        ->where("student_id", $request->student_id)->first();
        if($enrollment) {
            return $this->errorResponse('This course has already registed', [], 400);
        }

        $enrollment = new Enrollment($validated);
        $enrollment->save();


        return $this->successResponse($enrollment, 'Register course successfully');
    }
}
