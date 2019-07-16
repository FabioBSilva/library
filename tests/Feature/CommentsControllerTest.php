<?php

namespace Tests\Feature;

use App\Book;
use App\Comment;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentsControllerTest extends TestCase
{
    public function testcreatComment()
    {
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $token = $user->createToken('Token')->accessToken;
        $book = Book::create([
            'name' => 'php orientado',
            'description' => 'biblia',
            'author' => 'Fabio',
            'release_date' => '2019-05-15',
            'user_id' => $user->id
        ]);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->json('post', '/api/v1/book/' . $book->id . '/comment', [
            'comment' => 'otimo livro para iniciantes'
        ]);
        $response->assertStatus(200);

        $comment = Comment::where('comment', '=', 'otimo livro para iniciantes')->get();

        $this->assertNotNull($comment);

        $user->delete();

    }

    public function testaddPositiveComment()
    {
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $book = Book::create([
            'name' => 'php orientado',
            'description' => 'biblia',
            'author' => 'Fabio',
            'release_date' => '2019-05-15',
            'user_id' => $user->id
        ]);
        $comment = Comment::create([
            'comment' => 'otimo livro para iniciantes',
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);

        $response = $this->json('post', '/api/v1/comment/' . $comment->id . '/up');
        $response->assertStatus(200);

        $comment = Comment::find($comment->id);
        $this->assertEquals(1, $comment->positive_rep);

        $user->delete();
    }
    public function testaddNegativeComment(){
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
        $comment = Comment::create([
            'comment'=>'otimo livro para iniciantes',
            'user_id' => $user->id,
            'book_id'=> $book->id
        ]);
        $response=$this->json('post', '/api/v1/comment/' . $comment->id . '/down');
        $response->assertStatus(200);

        $comment = Comment::find($comment->id);
        $this->assertEquals(1, $comment->negative_rep);

        $user->delete();
    }
    public function testdeleteComment()
    {
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $token = $user->createToken('Token')->accessToken;
        $book = Book::create([
            'name' => 'php orientado',
            'description' => 'biblia',
            'author' => 'Fabio',
            'release_date' => '2019-05-15',
            'user_id' => $user->id
        ]);
        $comment = Comment::create([
            'comment' => 'otimo livro para iniciantes',
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->json('delete', '/api/v1/comment/' . $comment->id);
        $response->assertStatus(200);
        $comment = Comment::find($comment->id);
        $this->assertNull($comment);

        $user->delete();
    }

    public function testeditComment(){
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
        $comment = Comment::create([
            'comment'=>'otimo livro para iniciantes',
            'user_id' => $user->id,
            'book_id' => $book->id

        ]);
        $response=$this->withHeaders(['Authorization'=>'Bearer ' . $token])->json('put', '/api/v1/comment/' . $comment->id, [
            'comment'=>'livro ruim']);
        $response->assertStatus(200);
        $comment=Comment::find($comment->id);
        $this->assertNotNull($comment);
        $this->assertEquals('livro ruim', $comment->comment);

        $user->delete();
    }
}

