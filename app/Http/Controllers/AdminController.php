<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use Session;
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
    public function exams(Request $request){
        $exams=DB::table('exams')->get();
        return view('admin.exams')->with([
            'exams'=>$exams
        ]);
    }
    public function saveExam(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'total_score' => 'required',
            'time' => 'required',
        ]);
        if($validator->fails()){ return redirect()->back()->with('error',$validator->errors()->first()); }

        $inserted=DB::table('exams')->insert([
            'title'=>$request->title,
            'total_score'=>$request->total_score,
            'time'=>$request->time,

        ]);
        if($inserted){return redirect()->back()->with('success','added successfully!');}
        return redirect()->back()->with('error','was not added successfully!');
    }
    
}
