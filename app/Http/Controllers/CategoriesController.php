<?php

namespace App\Http\Controllers;

use App\Book;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    public function creatCategory(Request $request){

        $this->validate($request,[
            'name'=>'required|string'
        ]);

        $category = Category::create([

           'name'=>$request['name'],

        ]);
        return response()->json(['success'=>'Category created','category'=>$category], 200);

    }
    public function showCategories(){
        $categories=Category::all();
        return response()->json(['categories'=>$categories],200);
    }
    public function showBooksCategory($idCategories){
            try{
                $category = Category::findOrFail($idCategories);
                try{
                    $books = $category->books;
                    return response()->json(['books'=>$books], 200);
                }catch(\Exception $exception){
                    return response()->json(['error'=>'Books not found'], 404);
                }
            }catch(\Exception $exception){
                return response()->json(['error'=>'Category not found'], 404);
            }

    }

}
