<?php
namespace App\Http\Livewire;
use Illuminate\Support\Facades\File;
use Illuminate\{
    Http\Request,
    Support\Str,
    Validation\Rule,
};
use Livewire\{
    Component,
    WithPagination,
    WithFileUploads,
};
use App\Models\{
    Post,
};

class Posts extends Component
{
    use WithPagination ,WithFileUploads;
    public $modelFormVisible=false;
    public $modelFormVisibleDelete=false;
    public $title;
    public $slug_url;
    public $body;
    public $image;
    public $modelId;
    public $post_image;
    public $post_image_name;
    public $search;

    public function search() {
        return  Post::where('title', 'LIKE', '%'.$this->search.'%');
    }
    public function index()
    {
        return $this->search()->orderByDesc('id')->paginate(5);
    }
    public function showCreateModel(){
        $this->modelFormReset();
        $this->modelFormVisible=true;
    }
    public function showUpdateModel($id){
        $this->modelFormVisible=true;
        $this->modelFormReset();
        $this->modelId=$id;
        $this->loadModelData();

    }
    public function modelFormVisibleDelete($id){
        $this->modelFormVisibleDelete=true;
        $this->modelId=$id;
    }
    public function loadModelData(){
        $data = Post::find($this->modelId);
        $this->title = $data->title;
        $this->slug_url = $data->slug;
        $this->body = $data->body;
        $this->image = $data->image;
    }
    public function modelData()
    {
        $data=[
            'title'=>$this->title,
            'body'=>$this->body,
            'image'=>$this->post_image_name,
        ];
        if($this->post_image != ''){
            $data['image']=$this->post_image_name;
        };
        return $data;

    }

    public function rules()
    {
        return
        [
            'title'=>['required'],
            'slug_url'=>['required',Rule::unique('posts','slug')->ignore($this->modelId)],
            'body'=>['required'],
            'post_image'=>[Rule::requiredIf(!$this->modelId),'max:1024'],
            // 'post_image'=>['required','max:1024'],
        ];
    }

    public function modelFormReset()
    {
        $this->title=null;
        $this->slug_url=null;
        $this->body=null;
        $this->image=null;
        $this->post_image=null;
        $this->post_image_name=null;
        $this->modelId=null;

    }

    public function updatedTitle($value)
    {
        $this->slug_url=Str::slug($value);
    }
    
    public function store()
    {
        // dd(request()->all());
        $this->validate();
        if($this->post_image != '')
        {
            $this->post_image_name=md5($this->post_image . microtime() ).'.'. $this->post_image->extension();
            $this->post_image->storeAs('/',$this->post_image_name,'uploads');
        }
        auth()->user()->posts()->create($this->modelData()+['slug'=>$this->slug_url]);

        $this->modelFormReset();
        $this->modelFormVisible=false;
        return back();
    }
    
    public function update(){
        $this->validate();
        $post = Post::where('id', $this->modelId)->first();
        // Update And Delete Image Old ^^
        if($this->post_image != ''){
            if($this->post_image != ''){
                if(File::exists('images/'.$post->image)){
                    unlink('images/'.$post->image);
                }
            }
            $this->post_image_name=md5($this->post_image . microtime() ).'.'. $this->post_image->extension();
            $this->post_image->storeAs('/',$this->post_image_name,'uploads');
        }
        $post->update($this->modelData());
        $this->modelFormVisible=false;
        $this->modelFormReset();
        session()->flash('message', 'Post successfully updated.');


    }
    public function render()
    {
        return view('livewire.posts',[
            'posts'=>$this->index()
        ]);
    }
    public function destory(){
        $post = Post::where('id', $this->modelId)->first();
        if($this->post_image != ''){
            if(File::exists('images/'.$post->image)){
                unlink('images/'.$post->image);
            }
        }
        $post->delete();
        $this->modelFormVisibleDelete=false;
        $this->resetPage();
      
    }
}
