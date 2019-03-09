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

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/add-expense', 'HomeController@addExpense')->name('add-expense');
Route::delete('/expense', 'HomeController@deleteExpense')->name('delete-expense');
Route::post('/update-bound', 'HomeController@updateBound')->name('update-bound');
Route::post('/category', 'HomeController@saveCategory')->name('save-category');
Route::delete('/category', 'HomeController@deleteCategory')->name('delete-category');
Route::post('/budget', 'HomeController@saveBudget')->name('save-budget');
Route::delete('/budget', 'HomeController@deleteBudget')->name('delete-budget');
