<?php

namespace App\Http\Controllers;

use App\Book;
use App\Comment;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function creatComment($idBook, Request $request){
        $user=Auth::user();

        $this->validate($request,[
            'comment'=>'string'
        ]);
        try{
            $book = Book::findOrFail($idBook);

            $comment = Comment::create([
                'comment'=>$request['comment'],
                'user_id' => $user->id,
                'book_id' => $book->id
            ]);

            return response()->json(['comment'=>$comment], 200);

        }catch(\Exception $exception){
            return response()->json(['error'=>'Couldn\'t creat comment'], 500);
        }
    }
    public function addPositiveComment($idComment){
        try {
            $comment = Comment::findOrFail($idComment);
            try {
                $comment = $comment->update([
                    'positive_rep' => $comment->positive_rep + 1
                ]);
                return response()->json(['comment' => $comment], 200);
            } catch(\Exception $exception) {
                return response()->json(['error' => 'Couldn\'t update comment'], 500);
            }
        } catch(\Exception $exception) {
            return response()->json(['error' => 'Couldn\'t find comment'], 404);
        }
    }
    public function addNegativeComment($idComment){
        try{
            $comment = Comment::findOrFail($idComment);
            try{
                $comment = $comment->update([
                    'negative_rep'=>$comment->negative_rep + 1
                ]);
                return response()->json(['comment'=>$comment], 200);
            }catch(\Exception $exception){
                return response()->json(['error'=>'Couldn\'t update comment'],500);
            }
        }catch(\Exception $exception){
                return response()->json(['error'=>'Couldn\'t find comment'], 404);
        }
    }
    public function editComment($idComment,Request $request){
        $user=Auth::user();

        $this->validate($request,[
            'comment'=>'string'
        ]);
        try{
            $comments = Comment::findOrFail($idComment);

            if($user->id == $comments->user_id)
                try{
                    $comments->update($request->only(['comment']));
                    $comments = Comment::findOrFail($comments->id);
                    return response()->json(['comment'=>$comments],200);
                }catch(\Exception $exception){
                    return response()->json(['error'=>'Couldn\'t edit comment']);
                }
        }catch(\Exception $exception){
            return response()->json(['error'=>'Couldn\'t find comment'], 404);
        }
    }
    public function deleteComment($idComment)
    {
        $user = Auth::user();

        try {
            $comments = Comment::findOrFail($idComment);
            if ($user->id == $comments->user_id)
                try {
                    $comments->delete();
                } catch (\Exception $exception) {
                    return response()->json(['error' => 'Couldn\'t delete comment'], 500);
                }
            else {
                return response()->json(['error' => 'Comment doesn\'t belong to user'], 403);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Couldn\'t find comment'], 404);
        }
    }
}
