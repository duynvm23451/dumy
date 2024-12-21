<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\admin\CourseController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\QuestionAnswerController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\CourseController as ControllersCourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonController as ControllersLessonController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\StudentController as ControllersStudentController;
use App\Http\Controllers\TestController as ControllersTestController;
use App\Models\QuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get("/user", [ControllersStudentController::class, "getLoggedInUser"]);
  
    // Course
    Route::post("/enrollCourse", [EnrollmentController::class, "registerCourse"]);
    Route::get("/listRegistedCourses", [ControllersCourseController::class, "listRegistedCourse"]);
    Route::get("/listUnregisterdCourses", [ControllersCourseController::class, "listUnregistedCourse"]);
    Route::get("/courses/{id}", [ControllersCourseController::class, "getCourse"]);

    // Test
    Route::get("/tests", [ControllersTestController::class, "listTests"]);
    Route::get("/tests/{id}", [ControllersTestController::class, "getTest"]);
    Route::post("/submitTest", [ControllersTestController::class, "submitTest"]);
    Route::get("/testResults", [ControllersTestController::class, "getTestResult"]);

    // Lesson
    Route::post("/studentLessons", [ControllersLessonController::class, "createStudentLesson"]);
    Route::put("/studentLessons/favorite", [ControllersLessonController::class, "addOrRemoveFavorite"]);
    Route::put("/studentLessons/complete", [ControllersLessonController::class, "updateToComplete"]);
    Route::get("/listFavoritedLessons", [ControllersLessonController::class, "listFavoriteLesson"]);
    Route::get("/lessons/{id}", [ControllersLessonController::class, "getLesson"]);

    // Note
    Route::get("/notes", [NoteController::class, "listNotes"]);
    Route::post("/lessons/{id}/notes", [NoteController::class, "createNote"]);
    Route::delete("/notes/{id}", [NoteController::class, "deleteNote"]);

});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::post("/login", [AdminAuthController::class, "login"]);

    Route::middleware('auth:sanctum')->group(function () {
      Route::get('/admin', function(Request $request) {
        return $request->user();
      });

      //Students
      Route::get("/students", [StudentController::class, "listStudents"]);

      // Courses
      Route::get("/courses", [CourseController::class, "listCourses"]);
      Route::post("/courses", [CourseController::class, "createCoure"]);
      Route::get("/courses/{id}", [CourseController::class, "getCourse"]);
      Route::put('/courses/{id}', [CourseController::class, 'updateCourse']);  // Update course
      Route::delete('/courses/{id}', [CourseController::class, 'deleteCourse']);


      //Modules
      Route::get('/courses/{id}/modules', [ModuleController::class, 'listModules']);
      Route::post('/courses/{id}/modules', [ModuleController::class, 'createModule']);
      Route::put('/modules/{id}', [ModuleController::class, 'updateModule']);
      Route::delete('/modules/{id}', [ModuleController::class, 'deleteCourse']);

      //Lessons
      Route::get('/modules/{id}/lessons', [LessonController::class, 'listLessons']);
      Route::post('/modules/{id}/lessons', [LessonController::class, 'createLesson']);
      Route::delete('/lessons/{id}', [LessonController::class, 'deleteLesson']);

      //Tests
      Route::get('/tests', [TestController::class, "listTests"]);
      Route::post('/tests', [TestController::class, "createTest"]);
      Route::get('tests/{id}', [TestController::class, "getTest"]);
      
      //Questions
      Route::post('/tests/{id}/questions', [QuestionController::class, "createQuestion"]);
      Route::delete('/questions/{id}', [QuestionController::class, 'deleteQuestion']);


      //QuestionAnswers
      Route::post('/questions/{id}/questionAnswers', [QuestionAnswerController::class, "createQuestionAnswer"]);

    });
});

// AUTHENTICATION
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);


Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);