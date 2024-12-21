<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Module;
use App\trait\FormatResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    use FormatResponse;
    
    public function listLessons(int $moduleId)
    {

        // Find the module by its ID and load its lessons
        $module = Module::with('lessons')->find($moduleId);
    
        if(!$module) {
            return $this->errorResponse('Module not found', [], 404);
        }
   
        $lessons = $module->lessons;
    
        // Return the lessons
        return $this->successResponse($lessons, 'List lessons successfully');
    }

    public function createLesson(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'required',
            'video' => 'required|file|mimes:mp4,mkv,avi', // Max size 10MB
        ]);

        $module = Module::find($id);
        if(!$module) {
            return $this->errorResponse('Module not found', [], 404);
        }

        $path = $request->file('video')->store('videos', 'public');
        $videoUrl = asset("storage/{$path}");

        $lesson = new Lesson($validated);
        $lesson->video_url = $videoUrl;
        $lesson->module_id = $module->id;
        $lesson->save();


        return $this->successResponse($lesson, 'Create lesson successfully');
    }

    public function deleteLesson(int $id) {
        $lesson = Lesson::find($id);

        if(!$lesson) {
            return $this->errorResponse('Lesson not found', [], 404);
        }

        $lesson->delete();

        return $this->successResponse(null, 'Delete lesson successfully');
    }
}
