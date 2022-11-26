<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use Session;
class AdminController extends Controller
{
    public function adminHome(){
        $students=DB::table('users')->where('type',0)->paginate(10);
        return view('admin.home')->with([
            'students'=>$students,
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
        $question->answers=DB::table('model_answers')->where('question_id',$question->id)->get();
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
    public function student($id){
        $student=DB::table('users')->where(['type'=>0,'id'=>$id])->first();
        if(!$student){return redirect()->back()->with('error','invalid student');}
        $student->exams=DB::table('exams')
            ->select('exams.*','exams.id as examid','student_exam.*')
            ->join('student_exam','student_exam.exam_id','=','exams.id')
            ->where('student_id',$id)
            ->paginate(10);
        foreach ( $student->exams as $exam) {
            $exam->status=$this->getExamStatus($exam->examid,$id)['exam_status'];
        }

        return view('admin.student')->with([
            'student'=>$student
        ]);
    }

    public function deleteAnswer($id){
        DB::table('model_answers')->where('id',$id)->delete();
        return redirect()->back()->with('success','answer deleted successfully!');
    }
    public function deleteQuestion($id){
        DB::table('questions')->where('id',$id)->delete();
        return redirect()->back()->with('success','question deleted successfully!');
    }
    public function deleteExam($id){
        DB::table('exams')->where('id',$id)->delete();
        return redirect()->back()->with('success','exam deleted successfully!');
    }
    public function togglePublishExam($id){
        $updated=DB::table('exams')->where('id',$id)->update(['is_published'=>DB::raw('not is_published')]);
        return redirect()->back()->with('success','exam status was changed successfully!');
    }
    
}

