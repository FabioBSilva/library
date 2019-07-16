<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',];

    protected $hidden = ['password',];

    public function books(){
        return $this->hasMany(Book::class); //fala pra api q o usuario tem varios livros
    }

    public function comments(){
        return $this->hasMany(Comment::class); //fala pra api q o usuario tem varios comentarios
    }
}
