<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\trait\FormatResponse;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    use FormatResponse;

    public function listStudents(Request $request) {
        $students = Student::paginate($request->size ?? 10);
        $data = Helpers::paginate($students);
        return $this->successResponse($data, 'List students successfully');
    }

}
