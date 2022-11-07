<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class HomeController extends Controller
{
    public function home(){
        return view('home');
    }
    public function exams(Request $request){
        $allExams=DB::table('exams')->paginate(10);
        return view('student.exams')->with([
            'allExams'=>$allExams
        ]);
    }
    public function startExam(Request $request){
        $student_id=Auth()->user()->id;
        $startExam=DB::table('student_exams')->insert([
            'exam_id'=>$request->exam_id,
            'student_id'=>$student_id,
            'start_time'=>date('Y-m-d H:i:a'),
        ]);
        if($startExam){

        }
        return redirect()->route('takeExam')->with([
            'exam_id'=>$request->exam_id,
            'student_id'=>$request->student_id,
        ]);
    }
    public function takeExam(Request $request){
        $student_id=Auth()->user()->id;
        
        $hasStarted=DB::table('student_exams')->where([
            'student_id'=>$student_id,
            'exam_id'=>$request->exam_id
        ])->first();
        if(!$hasStarted){return redirect()->back()->with('error',"hasn't started exam yet");}
        $timeRemaining = time()-strtotime($hasStarted->start_time);
        if($timeRemaining<0){return redirect()->back()->with('error',"exam has finished");}

        return view('student.takeExam')->with([
            
        ]);
    }
    public function endExam(Request $request){
        $student_id=Auth()->user()->id;
        $request->student_id;
    }
}
