<?php

namespace App\Http\Controllers\admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\trait\FormatResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use FormatResponse;

    public function listCourses(Request $request) {
        $courses = Course::paginate($request->size ?? 10);
        $data = Helpers::paginate($courses);
        return $this->successResponse($data, 'List courses successfully');
    }

    public function createCoure(Request $request) {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'level' => 'required|numeric|between:0,4',
        ]);


        $course = Course::create([
            "title" => $request->title,
            "description" => $request->description,
            "level" => $request->level
        ]);

        return $this->successResponse($course, 'Create courses successfully');

    }

    public function getCourse(int $id) {
        $course = Course::with("modules.lessons")->find($id);
        if(!$course) {
            return $this->errorResponse('Course not found', [], 404);
        }

        return $this->successResponse($course, 'Get course successfully');
    }

    public function updateCourse(Request $request, $id) {
        $validated = $request->validate([
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'level' => 'sometimes|numeric|between:0,4',
        ]);

        $course = Course::find($id);

        if(!$course) {
            return $this->errorResponse('Course not found', [], 404);
        }

        $course->update($validated);
        return $this->successResponse($course, 'Update course successfully');
    }

    public function deleteCourse(int $id) {
        $course = Course::find($id);

        if(!$course) {
            return $this->errorResponse('Course not found', [], 404);
        }

        $course->delete();
        return $this->successResponse(null, 'Delete course successfully');
    }
}
