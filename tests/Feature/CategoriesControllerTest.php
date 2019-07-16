<?php

namespace Tests\Feature;

use App\Book;
use App\Category;
use App\User;
use Illuminate\Filesystem\Cache;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesControllerTest extends TestCase
{
    public function testcreatCategory(){
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $token = $user->createToken('Token')->accessToken;


        $response=$this->withHeaders(['Authorization'=>'Bearer ' . $token])->json('post', '/api/v1/category',[
            'name'=>'Romance'
        ]);
        $response->assertStatus(200);

        $user->delete();

        $category = Category::where('name','=','Romance')->first();

        $category->delete();

    }
    public function testshowCategories(){
        $categorie = Category::create([
            'name'=>'romance',
            ]);
        $response=$this->json('get','/api/v1/categories');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'categories');
        $categorie->delete();
    }
    public function testshowBooksCategory(){
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $category = Category::create([
            'name'=>'comedia',
        ]);
        $book = Book::create([
            'name' => 'Php orientado',
            'description' => 'teste',
            'user_id' => $user->id
        ]);
        $book->categories()->attach($category->id);

//        $books=Book::all();
//        foreach($books as $book){
//            $book->categories;
//        }
        $response=$this->json('get','/api/v1/categories/' . $category->id . '/books');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'books');
        $this->assertNotNull($category);

        $user->delete();
        $category->delete();
    }
}
