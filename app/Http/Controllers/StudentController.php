<?php

namespace App\Http\Controllers;

use App\trait\FormatResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    use FormatResponse;

    public function getLoggedInUser(Request $request) {
        return $this->successResponse($request->user(), 'Get logged in student successfully');

    }
}
