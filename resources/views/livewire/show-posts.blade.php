<div>
   @if($count==0)
      <x-jet-secondary-button wire:click="like">LIKE {{ $count }}</x-jet-secondary-button>
   @else
      <x-jet-danger-button wire:click="like">UnLike {{ $count }}</x-jet-danger-button>
   @endif
{{-- {{ dd($commentReply) }} --}}



   <!--Comments Form  & Re-Send-->
   @forelse ($comments as $comment)
      <!--Home Comment  (START)-->
      <div class="w-full divide-y divide-gray-200 bg-white py-5 px-5  max-w-2xl mx-auto">
         <p class="col text-blue-500 font-bold">{{$comment->user->name}}</p>
         <p>{{$comment->content}}</p>
         <x-jet-danger-button wire:click="commentDel({{ $comment->id }})">Del</x-jet-danger-button>
      </div>
      <!--Home Comment  (END)-->

      <!--Reply Comment-->
      <div class="w-full divide-y bg-gray-50 py-5 px-5  max-w-2xl mx-auto">
         @php
            $commentReply= \App\Models\Comment::where('parent_id','=', $comment->id)->get();
         @endphp
         @foreach ($commentReply as $reply )
            <p class="font-gray-500 font-bold font-serif text-gray-500">{{ $reply->content }} </p>
         @endforeach
         <input type="text" wire:model="content"  class="form-control">
         <x-jet-button class="btn btn-outline-secondary" wire:click="commentReply({{ $comment->id}})" type="submit">رد</x-jet-button>
      </div>
      <!--Reply Comment-->
   @empty
      <div class="w-full divide-y divide-gray-200 bg-white py-5 px-5  max-w-2xl mx-auto">
         <p class="col text-blue-500 font-bold">No Comment Found !</p>
      </div>
   @endforelse

   <div class="w-full divide-y divide-gray-200 bg-white py-5 px-5  max-w-2xl mx-auto">
      <textarea wire:model="content" class="w-100"></textarea>
      <div>
         <x-jet-button wire:click="comments">إرسال</x-jet-button>
      </div>
   </div>
           
  
</div>
