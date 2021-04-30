<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\{
    Post,
    User,
    Like,
    Comment
};
class ShowPosts extends Component
{
    // Show Post
    public $title;
    public $slug;
    public $body;
    public $image;
    public $post_id;
    public $slug_url;
    // Like
    public $likes;
    // Comment
    public $content;


   

public function showCreateModel(){
    
    $this->modelFormVisible=true;
}

   /*  هي تعرف لك متغير لكل صفحة بدونها مابتظهر البيانات  mount دالة مهمة هذي  */
    public function mount($slug)
    {
        $this->show($slug);
    }


    public function show ($slug)
    {
        $post = \App\Models\Post::whereSlug($slug)->first();
        $this->title= $post->title;
        $this->body=$post->body;
        $this->image=$post->image;
        $this->post_id=$post->id;
    }

    public function like()
    {
        $this->likes= Like::where('post_id',  $this->post_id);
        if($this->likes->count()==0){
            $like=new Like();
            $like->user_id=auth()->user()->id;
            $like->post_id= $this->post_id;
            $like->save();
        } 
        else
        {
            $this->likes->delete();
        }
    }

    public function comments()
    {
        $comment = new Comment();
        $comment->content =$this->content;
        $comment->user_id =auth()->user()->id;
        $comment->post_id =$this->post_id;
        $comment->save();
    }

    public function  commentReply($id)
    {
        $comment = new Comment();
        $comment->content =$this->content;
        $comment->user_id =auth()->user()->id;
        $comment->parent_id =$id;
        $comment->post_id =$this->post_id;
        $comment->save();
    }

    public function commentDel($id)
    {
        $comment= Comment::where('id',$id);
        $comment->delete();


    }
    public function render()
    {
        $this->likes= Like::where('post_id',  $this->post_id)->count();
        $comments= Comment::with('user')->where('post_id',  $this->post_id)->where('parent_id' ,'=', null)->get();
        return view('livewire.show-posts' , ['count'=>$this->likes , 'comments'=>$comments])->layout('layouts.app');
    }
}
