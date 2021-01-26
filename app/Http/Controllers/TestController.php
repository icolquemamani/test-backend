<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Problem;
use Exception;

class TestController extends Controller
{
    public function problemSolve(Request $request, $id) {
        $problem = Problem::getInstance();
        $input = $request->input('input');

        try {
            return $problem->solve($input, $id);
        } catch (Exception $ex) {
            return response()->json(['error'=> true, 'message' => $ex->getMessage()], 400);
        }
    }
}
