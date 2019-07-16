<?php

namespace Tests\Feature;

use App\Book;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersControllerTest extends TestCase
{

    public function testCreatUSer()
    {
        $response = $this->json('POST', 'api/v1/user', [
            'name'=> 'Teste',
            'email'=>'email@teste.com',
            'password'=>'12345678'
        ]);
        $response->assertStatus(200);

        User::where('email','email@teste.com')->first()->delete();
    }
    public function testLogin(){
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);

        $response = $this->json('GET', '/api/v1/login',[
            'email'=>'email@teste.com',
            'password'=>'12345678'
        ]);
        $response->assertStatus(200);
//        $this->assertNotNull($response->getContent()->user->access_token);
        $user->delete();
    }

    public function testShow(){
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $token = $user->createToken('Token')->accessToken;
        $response=$this->withHeaders(['Authorization'=>'Bearer ' . $token])->json('get', '/api/v1/user');
        $response->assertStatus(200);
        $user->delete();
    }
    public function testidUser(){
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $response=$this->json('get', '/api/v1/user/' . $user->id);
        $response->assertStatus(200);
        $this->assertArrayHasKey('user', $response->json());

        $user->delete();
    }
    public function testbooksUser(){
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $token = $user->createToken('Token')->accessToken;

        $book = Book::create([
            'name' => 'PHP Orientado a Objetos',
            'description' => 'Bíblia do PHP',
            'author' => 'Fábio Silva',
            'release_date' => '2019-07-15',
            'user_id' => $user->id
        ]);
        //outra forma de fazer
//        $book = $user->books()->create([
//            'name' => 'PHP Orientado a Objetos',
//            'description' => 'Bíblia do PHP',
//            'author' => 'Fábio Silva',
//            'release_date' => '2019-07-15'
//        ]);


        $response=$this->withHeaders(['Authorization'=>'Bearer ' . $token])->json('get', '/api/v1/user/books');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'books');

        $user->delete();
    }
    public function testupdateUser(){
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $token = $user->createToken('Token')->accessToken;

        $response=$this->withHeaders(['Authorization'=>'Bearer ' . $token])->json('put', '/api/v1/user', ['name'=>'Fabio']);
        $response->assertStatus(200);
        $user=User::find($user->id);
        $this->assertEquals('Fabio', $user->name);
        $user->delete();
    }
    public function testdeleteUser(){
        $user = User::create([
            'name' => 'Teste',
            'email' => 'email@teste.com',
            'password' => bcrypt('12345678')
        ]);
        $token = $user->createToken('Token')->accessToken;
        $response=$this->withHeaders(['Authorization'=>'Bearer ' . $token])->json('delete', '/api/v1/user');
        $response->assertStatus(200);
        $user = User::find($user->id);
        $this->assertNull($user);
    }

}
