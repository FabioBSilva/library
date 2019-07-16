<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//se nao precisar estar logado
Route::prefix('v1')->group(function(){
    Route::post('/user', 'UsersController@create');
    Route::get('/login','UsersController@login');
    Route::get('/user/{id}','UsersController@idUser')->where('id','[0-9]+');
    Route::get('/books','BooksController@getBooks');
    Route::get('/book/{id}','BooksController@getBook')->where('id','[0-9]+');
    Route::get('/book/{id}/comments','BooksController@bookComments')->where('id','[0-9]+');
    Route::get('/categories','CategoriesController@showCategories');
    Route::get('/categories/{id}/books','CategoriesController@showBooksCategory')->where('id','[0-9]+');
    Route::post('/comment/{id}/up','CommentsController@addPositiveComment')->where('id','[0-9]+');
    Route::post('/comment/{id}/down','CommentsController@addNegativeComment')->where('id','[0-9]+');

    //se estiver logado
    Route::middleware('auth:api')->group(function (){
        Route::get('/user','UsersController@show');
        Route::get('/user/books','UsersController@booksUser');
        Route::put('/user','UsersController@updateUser');
        Route::delete('/user','UsersController@deleteUser');
        Route::post('/book','BooksController@createBook');
        Route::put('/book/{id}','BooksController@editBook')->where('id','[0-9]+');
        Route::delete('/book/{id}','BooksController@deleteBook')->where('id','[0-9]+');
        Route::post('/category','CategoriesController@creatCategory');
        Route::post('/book/{id}/comment','CommentsController@creatComment')->where('id','[0-9]+');
        Route::put('/comment/{id}','CommentsController@editComment')->where('id','[0-9]+');
        Route::delete('/comment/{id}','CommentsController@deleteComment')->where('id','[0-9]+');


    });


});

