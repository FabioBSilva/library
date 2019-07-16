<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable=['comment','positive_rep','negative_rep','user_id','book_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function book(){
        return $this->belongsTo(Book::class); //belongsto fla q somente um comentario pode ser de um livro, nao pode pertencer a outro livro
    }
}
