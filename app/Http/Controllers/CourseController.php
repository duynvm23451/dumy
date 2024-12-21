<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\StudentLesson;
use App\trait\FormatResponse;
use Exception;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use FormatResponse;

    public function listRegistedCourse(Request $request) {
        try {
            $loggedInUser = $request->user();
        
            $courses = Course::query()
            ->join('enrollments', 'enrollments.course_id', '=', 'courses.id')
            ->where('enrollments.student_id', $loggedInUser->id)
            ->select('courses.*') // Ensure only course data is retrieved
            ->get();

            return $this->successResponse($courses, 'List registerd courses successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 400);
        }
 
    }

    
    public function listUnregistedCourse(Request $request) {
        try {
            $loggedInUser = $request->user();
        
            $courses = Course::whereDoesntHave("enrollments", function ($query) use ($loggedInUser) {
                $query->where("student_id", $loggedInUser->id);
            })->get();

            return $this->successResponse($courses, 'List unregisterd courses successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 400);
        }
 
    } 

    public function getCourse(int $id) {
        $course = Course::with("modules.lessons")->find($id);
        if(!$course) {
            return $this->errorResponse('Course not found', [], 404);
        }

        return $this->successResponse($course, 'Get course successfully');
    }

}
