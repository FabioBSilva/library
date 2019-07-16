<?php

namespace Tests\Feature;

use App\Book;
use App\Category;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BooksControllerTest extends TestCase
{

    public function testcreatBook(){
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $token = $user->createToken('Token')->accessToken;
        $category = Category::create([
            'name' => 'Educacional'
        ]);
        $response=$this->withHeaders(['Authorization'=>'Bearer ' . $token])->json('post', '/api/v1/book',[
            'name'=>'php orientado',
            'description'=>'biblia do php',
            'author'=>'Fabio',
            'release_date'=>'2019-06-15',
            'categories' => [$category->id]
        ]);
        $response->assertStatus(200);
        $book=Book::where('name','=','php orientado')->first();
        $this->assertNotNull($book);
        $category->delete();
        $user->delete();
    }
    public function testgetBooks(){
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $book = Book::create([
            'name'=>'php orientado',
            'description'=>'biblia',
            'author'=>'Fabio',
            'release_date'=>'2019-05-15',
            'user_id' => $user->id
        ]);
        $response=$this->json('get', '/api/v1/books');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'books');
        $user->delete();
    }
    public function testgetBook(){
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $book = Book::create([
            'name'=>'php orientado',
            'description'=>'biblia',
            'author'=>'Fabio',
            'release_date'=>'2019-05-15',
            'user_id' => $user->id
        ]);
        $response=$this->json('get', '/api/v1/book/' . $book->id);
        $response->assertStatus(200);
        $response->assertJson(['book' => [
            'name' => 'php orientado'
        ]]);

        $user->delete();
    }
    public function testbookComments(){
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $book = Book::create([
            'name'=>'php orientado',
            'description'=>'biblia',
            'author'=>'Fabio',
            'release_date'=>'2019-05-15',
            'user_id' => $user->id
        ]);
        //criar, deletar, alterar tem q ser funçao, e para pegar os dados tem q ser variavel
        $comment = $book->comments()->create([
            'comment'=>'Livro bom',
            'user_id'=>$user->id,
            'book_id'=>$book->id
        ]);
        $response=$this->json('get', '/api/v1/book/' . $book->id . '/comments');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'comments.comments');
        $user->delete();
    }
    public function testeditBook(){
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $token = $user->createToken('Token')->accessToken;
        $book = Book::create([
            'name'=>'php orientado',
            'description'=>'biblia',
            'author'=>'Fabio',
            'release_date'=>'2019-05-15',
            'user_id' => $user->id
        ]);
        $response=$this->withHeaders(['Authorization'=>'Bearer ' . $token])->json('put', '/api/v1/book/' . $book->id,[
            'name'=>'php orientado a objetos',
        ]);
        $response->assertStatus(200);
        $book=Book::find($book->id);
        $this->assertEquals('php orientado a objetos', $book->name);

        $user->delete();
    }
    public function testdeleteBook(){
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $token = $user->createToken('Token')->accessToken;
        $book = Book::create([
            'name'=>'php orientado',
            'description'=>'biblia',
            'author'=>'Fabio',
            'release_date'=>'2019-05-15',
            'user_id' => $user->id
        ]);
        $response=$this->withHeaders(['Authorization'=>'Bearer ' . $token])->json('delete', '/api/v1/book/' . $book->id);
        $response->assertStatus(200);
        $book=Book::find($book->id);
        $this->assertNull($book);


        $user->delete();
    }

}
