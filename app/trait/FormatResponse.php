<?php

namespace App\trait;

trait FormatResponse
{
     // Success Response
     protected function successResponse($data, $message = 'Success', $statusCode = 200)
     {
         return response()->json([
             'status' => true,
             'message' => $message,
             'data' => $data
         ], $statusCode);
     }
 
     // Error Response
     protected function errorResponse($message = 'Error', $errors = [], $statusCode = 400)
     {
         return response()->json([
             'status' => false,
             'message' => $message,
             'errors' => $errors
         ], $statusCode);
     }
}
