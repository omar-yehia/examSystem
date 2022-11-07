<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_exam', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('exam_id');
            $table->index('exam_id');
            $table->foreign('exam_id',)->references('id')->on('exams')->onDelete('cascade');

            $table->bigInteger('student_id');
            $table->index('student_id');
            $table->foreign('student_id',)->references('id')->on('users')->onDelete('cascade');

            $table->integer('score')->default(0);
            $table->datetime('end_time')->nullable();
            $table->datetime('start_time')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_exam');
    }
}
