<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\trait\FormatResponse;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    use FormatResponse;

    public function listModules(Request $request, int $id) {
        $course = Course::with('modules')->find($id);
        if(!$course) {
            return $this->errorResponse('Course not found', [], 404);
        }
        $modules = $course->modules;
        return $this->successResponse($modules, 'List modules successfully');
    }

    public function createModule(Request $request, int $id) {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $course = Course::find($id);

        if(!$course) {
            return $this->errorResponse('Course not found', [], 404);
        }

        $module = new Module($validated);
        $module->course_id = $course->id;
        $module->save();


        return $this->successResponse($module, 'Create module successfully');

    }

    public function updateModule(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $module = Module::find($id);
        if(!$module) {
            return $this->errorResponse('Module not found', [], 404);
        }

        $module->update($validated);


        return $this->successResponse($module, 'Update module successfully');
    }

    public function deleteCourse(int $id) {
        $module = Module::find($id);

        if(!$module) {
            return $this->errorResponse('Module not found', [], 404);
        }

        $module->delete();

        return $this->successResponse(null, 'Delete module successfully');
    }
}
