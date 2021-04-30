<div> 
    <div class="flex items-center justify-end px-4 py-4 text-right">
        <x-jet-button wire:click="showCreateModel">
            Create Post
        </x-jet-button>
    </div>

    <table class="w-full divide-y divide-gray-200">
        <thead>
            <div class="m-auto mx-auto">
                @if (session()->has('message'))
                    <div class="bg-green-100 font-bold py-2 px-2 mx-auto">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
            <tr>

                <!--In cases where you don't need data updates to happen live,
                Livewire has a .(defer) modifer that batches data updates with the next network request.-->
                <input type="text" wire:model.defer="search">
                <x-jet-button class="mx-3" wire:click="search">Search</x-jet-button>
                <!--In cases where you do need data updates to happen live-->
                <input type="text" wire:model="search">
                <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-blue-500 tracking-wider">id</th>
                <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-blue-500 tracking-wider">image</th>
                <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-blue-500 tracking-wider">title</th>
                <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-blue-500 tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($posts as $post)
                <tr>
                    <td class="px-6 py-3 border-b border-gray-200">{{ $post->id }}</td>
                    <td class="px-6 py-3 border-b border-gray-200"><img src="{{asset('images/'. $post->image)}}" width="44" alt="{{ $post->title }}"></td>
                    <td class="px-6 py-3 border-b border-gray-200">
                        <a href="{{ route('show.post',$post->slug) }}" >
                          {{ $post->title }}
                        </a>
                    </td>
                    <td class="px-6 py-3 border-b border-gray-200">
                        <div class="flex items-center justify-end px-4 py-4 text-right">
                            <x-jet-button class="mr-2"  wire:click="showUpdateModel({{ $post->id }})">
                                Edit Post
                            </x-jet-button>
                            <x-jet-danger-button wire:click="modelFormVisibleDelete({{ $post->id }})">
                                Delete Post
                            </x-jet-danger-button>
                        </div>
                    </td>
                </tr> 
            @empty
                <td class="px-6 py-3 border-b border-gray-200" colspan="4">No Posts Found !</td>
            @endforelse
        </tbody>
    </table>
    <div class="pt-4 mb-5 mx-5">
        {{ $posts->links() }}
        {{--{{ $posts->links("pagination::tailwind") }} --}}
    </div>

    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <!--
        <form action="/image" method="POST" enctype="multipart/form-data">
            {{-- @csrf --}}
            <input type="file" name="image">
            <x-jet-danger-button>
            Uplaod
            </x-jet-danger-button>
        </form>
    -->
    <x-jet-dialog-modal wire:model="modelFormVisibleDelete">
        <x-slot name="title">
            Delete
        </x-slot>
        <x-slot name="content">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non, enim?
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modelFormVisibleDelete')" >Cancel</x-jet-secondary-button>
            <x-jet-danger-button class="ml-2" wire:click="destory">Update Post</x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
    <x-jet-dialog-modal wire:model="modelFormVisible">
        <x-slot name="title">
            {{ $modelId ? 'Upate Post' :' Create Post' }}
        </x-slot>
        <x-slot name="content">
           <div class="mt-4">
                @if (session('success'))
                    <div class="alert alert-success">
                    <p style="font-weight: bold">{{ session('success') }}</p> 
                    </div>
                @endif
               <!--Form your title-->
               <x-jet-label for="title" value="Title"></x-jet-label>
               <x-jet-input type="text" id="title" wire:model.debounce.5000ms="title" class="block mt-1 w-full border-gray-300"></x-jet-input>
                @error('title')<span class="text-red-900 text-xl">{{$message}}</span>@enderror

               <!--Form your slug-->
                <x-jet-label for="slug" value="Slug"></x-jet-label>
                <div class="mt-1 flex rounded-md shadow-sm">
                   <span class="inline-flex items-center px-2 border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm rounded-l">
                   {{ config('app.url') . '/'}}
                   </span>
                   <input type="text" id="slug" wire:model="slug_url" placeholder="url slug" class="block w-full border-gray-300 form-input flex-1 rounded-none rounded-r-md transition duration-150 ease-in-out sm:leading-5">
                </div>
                @error('slug')<span class="text-red-900 text-xl">{{$message}}</span>@enderror

                <!-- Area :  All Settings CKEditer *START*-->
                <div class="mt-4">
                    <x-jet-label for="body" value="Content"></x-jet-label>
                    <textarea wire:model="body"></textarea>
                </div>
                @error('body')<span class="text-red-900 text-xl">{{$message}}</span>@enderror
                <!-- Area :  All Settings CKEditer  *END*-->


                <!--Form your Image-->
                <div class="mt-4 col-6 flex">
                    @if ($post_image)
                        <img class="border border-gray-300 rounded-md" src="{{ $post_image->temporaryUrl() }}" alt="{{ $title }}" width="200">
                    @endif
                    @if ($image)
                        <img class="border border-gray-300 rounded-md ml-2" src="{{ asset('images/'.$image)  }}" alt="{{ $title }}" width="200">
                    @endif
                </div>
                <div class="mt-4">
                    <x-jet-label for="image" value="Image"></x-jet-label>
                    <input type="file" wire:model="post_image" class="border border-gray-300 py-2 form-input flex-1 block w-full rounded-md transition duration-150 ease-in-out sm:leading-5">
                    @error('image')<span class="text-red-900 text-xl">{{$message}}</span>@enderror
                </div>
           </div>
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modelFormVisible')" >Cancel</x-jet-secondary-button>
            @if ($modelId )
                <x-jet-button class="ml-2" wire:click="update">Update Post</x-jet-button>
            @else
                <x-jet-button class="ml-2" wire:click="store">Create Post</x-jet-button>
            @endif
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        const inputElement = document.querySelector('input[type="file"]');
        const pond = FilePond.create( inputElement );
        FilePond.setOptions({
            server: {
                url : '/posts',
                headers : {
                    'X-CSRF-TOKEN':{{ csrf_token() }}
                }
            }
        });
    </script>
</div>
