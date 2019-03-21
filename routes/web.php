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

Route::post('/budget', 'BudgetController@save')->name('save-budget');
Route::delete('/budget', 'BudgetController@delete')->name('delete-budget');
Route::post('/expense', 'BudgetController@addExpense')->name('add-expense');
Route::delete('/expense', 'BudgetController@deleteExpense')->name('delete-expense');
Route::post('/update-bound', 'BudgetController@updateBound')->name('update-bound');
Route::post('/category', 'HomeController@saveCategory')->name('save-category');
Route::delete('/category', 'HomeController@deleteCategory')->name('delete-category');
