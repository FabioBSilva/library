<?php

namespace App\Http\Controllers;

use App\Book;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BooksController extends Controller
{
    public function createBook(Request $request){
        $user=Auth::user();
        $this->validate($request,[
            'name'=>'required|max:100|string',
            'description'=>'required|string|min:10',
            'author'=>'max:150|string',
            'release_date'=>'date',
            'categories' => 'array|required',
            'categories.*' => 'exists:categories,id' //o ponto asterisco valida cada posiçao do array categories
            ]);

       try{
           $book = Book::create([

               'name'=>$request['name'],
               'description'=>$request['description'],
               'author'=>$request['author'],
               'release_date'=>$request['release_date'],
               'user_id'=>$user->id
           ]);
           //Adiciona as categorias do livro
           foreach($request['categories'] as $category) {
               $book->categories()->attach($category);
           }

           //Retorna as categorias
           $book->categories;

           return response()->json(['success'=>'Book created','book'=>$book], 200);
       }catch(\Exception $exception){
           return response()->json(['error'=>'Couldn\'t create book'], 500);
       }
    }
    public function getBooks(){
        $books=Book::all();
        foreach($books as $book){
            $book->categories;
        }
        return response()->json(['books'=>$books],200);
    }
    public function getBook($idBook){
        try{
            $books=Book::findOrFail($idBook);
                $books->categories;

            return response()->json(['book'=>$books],200);
        }catch(\Exception $exception){
            return response()->json(['error'=>'Couldn\'t find book'], 404);
        }
    }
    public function bookComments($idBook){
        try{
            $books=Book::findOrFail($idBook);
            $books->comments;
            return response()->json(['comments'=>$books], 200);
        }catch(\Exception $exception){
            return response()->json(['error'=>'Couldn\'t find book'], 404);
        }
    }
    public function editBook($idBook,Request $request){
        $user = Auth::user();

        $this->validate($request,[
            'name'=>'max:100|string',
            'description'=>'string|min:10', //
            'author'=> 'max:150|string',
            'release_date'=>'date',
            'categories'=>'array',
            'categories.*'=>'exists:categories,id'
        ]);
        try{
            $books=Book::findOrFail($idBook);

            if($user->id == $books->user_id) {
                try{
                    if(isset($request['categories'])) {
                        //Remove as categorias antigas
                        $books->categories()->detach();

                        //Adiciona as categorias novas
                        $books->categories()->attach($request->categories);
                    }
                    $books->update($request->only(['name','description','author','release_date']));
                    $books = Book::find($books->id);
                    $books->categories;
                    return response()->json(['book'=>$books], 200);
                }catch(\Exception $exception){
                    return response()->json(['error'=>'Couldn\'t update book'], 500);
                }
            } else {
                return response()->json(['error' => 'Book doesn\'t belong to user'], 403);
            }
        }catch(\Exception $exception){
            return response()->json(['error'=>'Couldn\'t find book'], 404);
        }
    }
    public function deleteBook($idBook){
        $user=Auth::user();

       try{
           $books=Book::findOrFail($idBook);
           if($user->id == $books->user_id)
               try{
                   $books->delete();
               }catch(\Exception $exception){
                   return response()->json(['error'=>'Couldn\'t delete book'], 500);
               }else{
               return response()->json(['error'=>'Book doesn\'t belong to user'], 403);
           }
       }catch(\Exception $exception){
           return response()->json(['error'=>'Couldn\'t find book'], 404);
       }
    }

}
