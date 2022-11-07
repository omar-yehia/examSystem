<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function() {
    Route::get('/home', 'HomeController@home')->name('home');
    Route::get('/exams', 'HomeController@studentExams')->name('studentExams');
    Route::post('/startExam', 'HomeController@startExam')->name('startExam');
    Route::post('/submitAnswers', 'HomeController@submitAnswers')->name('submitAnswers');

    
});


Route::group(['prefix' => 'admin','middleware' => ['admin']], function() {
    Route::get('/', 'AdminController@adminHome')->name('adminHome');
    Route::get('/exams', 'AdminController@exams')->name('exams');
    Route::get('/exam/{id}', 'AdminController@exam')->name('exam');
    Route::post('/saveExam', 'AdminController@saveExam')->name('saveExam');
    
    Route::get('/question/{id}', 'AdminController@question')->name('question');
    Route::post('/saveQuestion', 'AdminController@saveQuestion')->name('saveQuestion');

    Route::get('/answer/{id}', 'AdminController@answer')->name('answer');
    Route::post('/saveAnswer', 'AdminController@saveAnswer')->name('saveAnswer');
    Route::get('/student/{id}', 'AdminController@student')->name('student');

    
    
});