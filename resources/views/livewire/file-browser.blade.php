<div>
    <div class="flex flex-wrap items-center justify-between mb-4">
        <div class="flex-grow w-full md:w-auto mr-0 md:mr-3 mt-4 md:mt-0 order-3 md:order-1">
            <input type="search" placeholder="Search files and folders" class="w-full px-3 h-12 border-2 rounded-lg" wire:model="query">
        </div>
        <div class="order-2 w-full md:w-auto">
            <div class="flex">
                <button class="bg-gray-200 px-6 h-12 rounded-lg mr-2 flex-grow" wire:click="$set('creatingNewFolder', true)">New Folder</button>
                <button class="bg-indigo-600 text-white font-bold px-6 h-12 rounded-lg flex-grow" wire:click="$set('showingFileUploadForm', true)">Upload</button>
            </div>
        </div>
    </div>

    <div class="border-2 border-gray-200 rounded-lg">
        <div class="py-2 px-3">
            <div class="flex items-center">
                @if ($this->query)
                    <div class="font-bold text-gray-400">
                        Found {{ $this->results->count() }} {{ Str::plural('result', $this->results->count()) }}.
                        <button class="text-indigo-600 font-bold" wire:click="$set('query', null)">Clear Search</button>
                    </div>
                @else
                    @foreach ($ancestors as $ancestor)
                        <a href="{{ route('files', ['uuid' => $ancestor->uuid]) }}" class="text-gray-400 font-bold">
                            {{ $ancestor->category->name }}
                        </a>

                        @if (!$loop->last)
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="1em" hight="1em" class="text-gray-300 w-5 h-5 mx-1">
                              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        <div class="overflow-auto">
            <table class="w-full rounded-b-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left py-2 px-3">Name</th>
                        <th class="text-left py-2 px-3 w-2/12">Size</th>
                        <th class="text-left py-2 px-3 w-2/12">Created</th>
                        <th class="py-2 w-2/12"></th>
                    </tr>
                </thead>

                <tbody>
                    @if ($creatingNewFolder)
                        <tr class="border-gray-100 border-b-2 hover:bg-gray-100">
                            <td class="p-3">
                                <form class="flex items-center" wire:submit.prevent="createFolder">
                                    <input type="text" name="" id="" class="w-full px-3 h-10 border-2 border-gray-200 rounded-lg mr-2" wire:model="newFolderState.name">
                                    <button type="submit" class="bg-indigo-600 text-white px-6 h-10 rounded-lg mr-2">
                                        Create
                                    </button>
                                    <button wire:click="$set('creatingNewFolder', false)" class="bg-gray-200 px-6 h-10 rounded-lg mr-2">
                                        Cancel
                                    </button>
                                </form>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endif
                    @foreach($this->results as $child)
                        <tr class="border-gray-100 @if (!$loop->last) border-b-2 @endif hover:bg-gray-100">
                            <td class="py-2 px-3 flex items-center">
                                @if ($child->category_type === 'file')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-indigo-500 flex-shrink-0ar">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                @endif

                                @if ($child->category_type === 'folder')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-indigo-500 flex-shrink-0ar">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                @endif

                                @if ($renamingItem === $child->id)
                                    <form class="flex items-center ml-2 flex-grow" wire:submit.prevent="renameItem">
                                        <input type="text" name="" id="" class="w-full px-3 h-10 border-2 border-gray-200 rounded-lg mr-2" wire:model="renamingItemState.name">
                                        <button type="submit" class="bg-indigo-600 text-white px-6 h-10 rounded-lg mr-2">
                                            Rename
                                        </button>
                                        <button wire:click="$set('renamingItem', null)" class="bg-gray-200 px-6 h-10 rounded-lg mr-2">
                                            Cancel
                                        </button>
                                    </form>
                                @else
                                    @if ($child->category_type === 'folder')
                                        <a href="{{ route('files', ['uuid' => $child->uuid]) }}" class="p-2 font-bold text-indigo-600 flex-grow">
                                            {{ $child->category->name }}
                                        </a>
                                    @endif

                                    @if ($child->category_type === 'file')
                                        <a href="{{ route('files.download', $child->category) }}" class="p-2 font-bold text-indigo-600 flex-grow">
                                            {{ $child->category->name }}
                                        </a>
                                    @endif
                                @endif
                            </td>

                            <td class="py-2 px-3">
                                @if ($child->category_type === 'file')
                                    {{ $child->category->sizeForHumans() }}
                                @else
                                    &mdash;
                                @endif
                            </td>

                            <td class="py-2 px-3">
                                {{ $child->created_at }}
                            </td>

                            <td class="py-2 px-3">
                                <div class="flex justify-end items-center">
                                    <ul class="flex items-center">
                                        <li class="mr-2">
                                            <button class="font-bold text-yellow-400" wire:click="$set('renamingItem', {{ $child->id }})">
                                                Rename
                                            </button>
                                        </li>
                                        <li>
                                            <button class="font-bold text-red-500" wire:click="$set('confirmingItemDeletion', {{ $child->id }})">
                                                Delete
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($this->results->count() === 0)
            <div class="p-3 text-gray-600">
                This folder is empty.
            </div>
        @endif
    </div>

    <x-jet-dialog-modal wire:model="confirmingItemDeletion">
        <x-slot name="title">
            {{ __('Delete') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="ml-2" wire:click="$set('confirmingItemDeletion', null)" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteItem">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-modal wire:model="showingFileUploadForm">
        <div
            class="m-3 border-dashed border-2"
            wire:ignore
            x-data="{
                initFilepond () {
                    const pond = FilePond.create(this.$refs.filepond, {
                        onprocessfile: (error, file) => {
                            pond.removeFile(file.id)

                            if (pond.getFiles().length === 0) {
                                @this.set('showingFileUploadForm', false)
                            }
                        },
                        allowRevert: false,
                        server: {
                            process: (fieldName, file, metadata, load, error, progress, abort, tranfer, options) => {
                                @this.upload('upload', file, load, error, progress)
                            }
                        }
                    })
                }
            }"
            x-init="initFilepond"
        >
            <div>
                <input type="file" x-ref="filepond" multiple>
            </div>
        </div>
    </x-jet-modal>
</div>
