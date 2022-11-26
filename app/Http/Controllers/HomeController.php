<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use DB;
class HomeController extends Controller
{
    public function home(){
        return view('home');
    }
    public function studentExams(Request $request){
        $student_id=Auth()->user()->id;
        $allExams=DB::table('exams')->where('is_published',1)->paginate(10);
        foreach ($allExams as $exam) {
            $exam->score=0;
            $exam->status='';
            $hasStarted=DB::table('student_exam')->where([
                'student_id'=>$student_id,
                'exam_id'=>$exam->id,
            ])->first();
            if($hasStarted){
                $exam->score=$hasStarted->score;
                $exam->status='ongoing';
                $timeRemaining = ((60*$exam->time)+strtotime($hasStarted->start_time))-time();
                $hasEnded=$hasStarted->end_time;
                if($hasEnded || $timeRemaining<=0){
                    $exam->status='ended';
                }
            }
        }
        return view('student.exams')->with([
            'allExams'=>$allExams
        ]);
    }
    
    public function startExam(Request $request){
        $student_id=Auth()->user()->id;
        $exam=DB::table('exams')->where('id',$request->exam_id)->first();
        if(!$exam){return redirect()->back()->with('error',"invalid exam");}

        $sessionExamId=session('exam_id');
        $sessionExamStatus=$this->getExamStatus($sessionExamId);
        if($sessionExamStatus['return']==1 && $sessionExamId!=$exam->id){return redirect()->back()->with('error',"please finish ongoing exams first");}

        session()->forget('exam_id');
        session()->put('exam_id',$exam->id);

        $hasStarted=DB::table('student_exam')->where([
            'student_id'=>$student_id,
            'exam_id'=>$exam->id
        ])->first();

        if(!$hasStarted){
            $startExam=DB::table('student_exam')->insert([
                'exam_id'=>$exam->id,
                'student_id'=>$student_id,
                'start_time'=>date('Y-m-d H:i:s'),
            ]);
            $timeRemaining = ((60*$exam->time)+time())-time();
        }else{
            $timeRemaining = ((60*$exam->time)+strtotime($hasStarted->start_time))-time();
        }

        if($timeRemaining<=0){
            return redirect()->back()->with('error',"time ended");
        }
        return $this->takeExam($request->exam_id,$timeRemaining);
    }
    public function takeExam($exam_id,$timeRemaining){
        $student_id=Auth()->user()->id;
        $exam=DB::table('exams')->where('id',$exam_id)->first();
        if(!$exam){return redirect()->back()->with('error',"invalid exam");}
        $exam->questions=DB::table('questions')->where('exam_id',$exam_id)->get();
        foreach ($exam->questions as $question) {
            $question->answers=DB::table('model_answers')->where('question_id',$question->id)->get();
        }
        $exam->timeRemaining=$timeRemaining;
        return view('student.takeExam')->with([
            'exam' =>$exam
        ]);
    }
    public function submitAnswers(Request $request){
        $inputs=$request->all();
        $student_id=Auth()->user()->id;
        $exam_id=session('exam_id');
        $examStatus=$this->getExamStatus($exam_id);
        if($examStatus['return']==-1){return redirect()->route('studentExams')->with('error',"invalid exam");}
        if($examStatus['return']==0){return redirect()->route('studentExams')->with('error',"exam is not running");}
        $exam=DB::table('exams')->where('id',$exam_id)->first();

        $studentAnswers=[];
        foreach ($inputs as $key => $value) {
            $question=explode("question_",$key);
            if(count($question)!=2){continue;}
            $question_id=$question[1];
            $studentAnswers[$question_id]=$value;
        }
        $score=0;
        $questions=DB::table('questions')->where('exam_id',$exam_id)->get();
        foreach ($questions as $question) {
            $modelAnswer=DB::table('model_answers')->where([
                'question_id'=>$question->id,
                'is_correct'=>1,
            ])->first();

            if($modelAnswer && $modelAnswer->id==$studentAnswers[$question->id]){
                $score+=$question->score;
            }
        }

        DB::table('student_exam')->where([
            'exam_id'=>$exam_id,
            'student_id'=>$student_id,
        ])->update([
            'end_time'=>date('Y-m-d H:i:s'),
            'score'=>$score,
        ]);
        return redirect()->route('studentExams')->with('success',"Your score is $score/$exam->total_score");

    }
}
