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
        $allExams=DB::table('exams')->paginate(10);
        return view('admin.exams')->with([
            'allExams'=>$allExams
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
    public function exam($id){
        $exam=DB::table('exams')->where('id',$id)->first();
        if(!$exam){return redirect()->back()->with('error','invalid');}
        $exam->questions=DB::table('questions')->where('exam_id',$exam->id)->paginate(10);
        return view('admin.exam')->with([
            'exam'=>$exam
        ]);
    }
    public function saveQuestion(Request $request){
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            'content' => 'required',
            'score' => 'required',
        ]);
        if($validator->fails()){ return redirect()->back()->with('error',$validator->errors()->first()); }

        $inserted=DB::table('questions')->insert([
            'exam_id'=>$request->exam_id,
            'content'=>$request->content,
            'score'=>$request->score,
        ]);
        if($inserted){return redirect()->back()->with('success','added successfully!');}
        return redirect()->back()->with('error','was not added successfully!');
    }
    // ==============================
    public function question($id){
        $question=DB::table('questions')->where('id',$id)->first();
        if(!$question){return redirect()->back()->with('error','invalid');}
        $question->exam=DB::table('exams')->where('id',$question->exam_id)->first();
        $question->answers=DB::table('model_answers')->where('question_id',$question->id)->paginate(10);
        return view('admin.question')->with([
            'question'=>$question
        ]);
    }
    public function saveAnswer(Request $request){
        $validator = Validator::make($request->all(), [
            'question_id' => 'required',
            'content' => 'required',
            'is_correct' => 'nullable',
        ]);
        if($validator->fails()){ return redirect()->back()->with('error',$validator->errors()->first()); }

        $inserted=DB::table('model_answers')->insert([
            'question_id'=>$request->question_id,
            'content'=>$request->content,
            'is_correct'=>$request->is_correct?1:0,
        ]);
        if($inserted){return redirect()->back()->with('success','added successfully!');}
        return redirect()->back()->with('error','was not added successfully!');
    }

}
