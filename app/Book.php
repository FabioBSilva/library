<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //o fillable informa quais campos o usuario pode preencher
    protected $fillable =['name','description','author','release_date','user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }
}
