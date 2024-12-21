<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\StudentLesson;
use App\trait\FormatResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    use FormatResponse;
    
    public function createStudentLesson(Request $request) {
        try {
            $loggedInUser = $request->user();
            $validated = $request->validate([
                'lesson_id' => 'required',
            ]);

            $lesson = Lesson::find($request->lesson_id);

            if(!$lesson) {
                return $this->errorResponse('Lesson not found', [], 404);
            }

            $enrolledCourseIds = Course::query()
            ->join('enrollments', 'enrollments.course_id', '=', 'courses.id')
            ->where('enrollments.student_id', $loggedInUser->id)->pluck('id')->toArray();

            if (in_array($lesson->module->course->id, $enrolledCourseIds)) {
                return $this->errorResponse('already existing', [], 400);

            }

            $studentLesson = new StudentLesson($validated);
            $studentLesson->student_id = $loggedInUser->id;
            $studentLesson->save();

            return $this->successResponse($studentLesson, 'Create student lesson successfully');

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 400);
        }
    }

    public function addOrRemoveFavorite(Request $request) {
        try {
            $loggedInUser = $request->user();

            $studentLesson = StudentLesson::where("student_id", $loggedInUser->id)->where("lesson_id", $request->lesson_id)->first();

            if(!$studentLesson) {
                return $this->errorResponse('Student lesson not found', [], 404);
            }
            $newFavoriteStatus = !$studentLesson->is_favorited;

            DB::table('student_lessons')
                ->where('student_id', $loggedInUser->id)
                ->where('lesson_id', $request->lesson_id)
                ->update([
                    'is_favorited' => $newFavoriteStatus,
                    'updated_at' => now(),
                ]);
            return $this->successResponse([
                'lesson_id' => $request->lesson_id,
                'is_favorited' => $newFavoriteStatus,
            ], $newFavoriteStatus == 1 ? "Thêm video yêu thích thành công": "Hủy yêu thích video thành công");

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 400);
        }
    }

    public function updateToComplete(Request $request) {
        try {
            $loggedInUser = $request->user();

            $studentLesson = StudentLesson::where("student_id", $loggedInUser->id)->where("lesson_id", $request->lesson_id)->first();

            if(!$studentLesson) {
                return $this->errorResponse('Student lesson not found', [], 404);
            }

            DB::table('student_lessons')
                ->where('student_id', $loggedInUser->id)
                ->where('lesson_id', $request->lesson_id)
                ->update([
                    'is_completed' => true,
                    'updated_at' => now(),
                ]);
            return $this->successResponse([
                'lesson_id' => $request->lesson_id,
            ], 'Student lesson updated successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 400);
        }
    }

    public function listFavoriteLesson(Request $request) {
        try {
            $loggedInUser = $request->user();
            
            $lessons = Lesson::whereHas('student_lesson', function ($query) use ($loggedInUser) {
                $query->where("student_id", $loggedInUser->id)->where('is_favorited', true);
            })->with("module.course")->get();

            return $this->successResponse($lessons, 'List favorited lessons succezssfully');

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 400);
        }
    }

    public function getLesson(Request $request, int $id) {
        try {
            $loggedInUser = $request->user();
            $lesson = Lesson::find($id);
            if(!$lesson) {
                return $this->errorResponse('Lesson not found', [], 404);
            }
    
            $existingStudentLesson = StudentLesson::where("student_id", $loggedInUser->id)->where("lesson_id", $lesson->id)->first();
    
            if (!$existingStudentLesson) {
                $studentLesson = new StudentLesson();
                $studentLesson->lesson_id = $lesson->id;
                $studentLesson->student_id = $loggedInUser->id;
                $studentLesson->save();
            }
    
            return $this->successResponse($lesson, 'Get lesson successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 400);
        }

    }
    
}
