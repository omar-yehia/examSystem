<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DB;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function getExamStatus($exam_id,$student_id=0){
        if(Auth()->user()->isAdmin()!=1){
            $student_id=Auth()->user()->id;
        }

        $exam=DB::table('exams')->where('id',$exam_id)->first();
        if(!$exam){return ['return'=>-1,'code'=>1,'status'=>'invalid'];}
        $hasStarted=DB::table('student_exam')->where([
            'student_id'=>$student_id,
            'exam_id'=>$exam_id
        ])->first();
        if(!$hasStarted){return ['return'=>0,'code'=>2,'exam_status'=>'not started'];}
        if($hasStarted->end_time){return ['return'=>0,'code'=>3,'exam_status'=>'ended'];}
        $timeRemaining = ((60*$exam->time)+strtotime($hasStarted->start_time))-time();
        if($timeRemaining<=0){return ['return'=>0,'code'=>3,'exam_status'=>'time over'];}
        return ['return'=>1,'code'=>3,'exam_status'=>'ongoing','timeRemaining'=>$timeRemaining];
    }
}
