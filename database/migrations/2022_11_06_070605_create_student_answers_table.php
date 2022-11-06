<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_answers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('exam_id');
            $table->index('exam_id');
            $table->foreign('exam_id',)->references('id')->on('exams')->onDelete('cascade');
            
            $table->bigInteger('student_id');
            $table->index('student_id');
            $table->foreign('student_id',)->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('question_id');
            $table->index('question_id');
            $table->foreign('question_id',)->references('id')->on('questions')->onDelete('cascade');

            $table->bigInteger('answer_id');
            $table->index('answer_id');
            $table->foreign('answer_id',)->references('id')->on('questions')->onDelete('cascade');

            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_answers');
    }
}
