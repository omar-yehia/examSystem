<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class AdminController extends Controller
{
    public function adminHome(){
        $exams=DB::table('exams')->get();
        $results=DB::table('student_exam')->get();
        $results=$results->groupBy('student_id')->groupBy('exam_id');

        return view('admin.home')->with([
            'exams'=>$exams,
            'results'=>$results,
        ]);
    }
}
