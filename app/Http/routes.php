<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::patch('Classlessons/update/{id}','LessonController@update');

Route::get('Classlessons/lessons','LessonController@lessons');
Route::post('Classlessons/store','LessonController@store');
Route::get('Classlessons/lessonplan/{id}','LessonController@lessonplan');
Route::get('Classlessons/index','LessonController@index');

Route::resource('Classlessons', 'LessonController');



Route::get('/', 'HomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('graphscores/student','GraphController@index');
Route::get('graphscores/teacher','GraphController@index');
Route::post('graphscores/teacher', 'GraphController@graph');


Route::get('quiz/listtests','QuizController@listtests');
Route::get('quiz/finished','QuizController@result');
Route::get('quiz/{id}','QuizController@index');
Route::post('quiz/{id}', 'QuizController@next');

