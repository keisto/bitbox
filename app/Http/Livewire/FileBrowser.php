<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithFileUploads;

class FileBrowser extends Component
{
    use WithFileUploads;

    public $query;

    public $upload;

    public $item;

    public $ancestors;

    public $creatingNewFolder = false;

    public $newFolderState = [
        'name' => '',
    ];

    public $renamingItem;

    public $renamingItemState = [
        'name' => '',
    ];

    public $showingFileUploadForm = false;

    public $confirmingItemDeletion;

    public function getResultsProperty()
    {
        if (strlen($this->query)) {
            return Item::search($this->query)
                ->where('team_id', $this->currentTeam->id)
                ->get();
        }

        return $this->item->children;
    }

    public function deleteItem()
    {
        Item::forCurrentTeam()
            ->find($this->confirmingItemDeletion)
            ->delete();

        $this->confirmingItemDeletion = null;

        $this->item = $this->item->fresh();
    }

    public function updatedUpload($upload)
    {
        $item = $this->currentTeam->items()->make([
            'parent_id' => $this->item->id,
        ]);

        $item->category()->associate(
            $this->currentTeam->files()->create([
                'name' => $upload->getClientOriginalName(),
                'size' => $upload->getSize(),
                'path' => $upload->storePublicly('files', ['disk' => 'local']),
            ])
        );

        $item->save();

        $this->item = $this->item->fresh();
    }

    public function renameItem()
    {
        $this->validate([
            'renamingItemState.name' => 'required|max:255',
        ]);

        Item::forCurrentTeam()
            ->find($this->renamingItem)
            ->category->update($this->renamingItemState);

        $this->item = $this->item->fresh();

        $this->renamingItem = null;
    }

    public function updatingRenamingItem($id)
    {
        if ($id === null) {
            $this->renamingItemState = [
                'name' => '',
            ];
        }

        if ($item = Item::forCurrentTeam()->find($id)) {
            $this->renamingItemState = [
                'name' => $item->category->name,
            ];
        }
    }

    public function createFolder()
    {
        $this->validate(['newFolderState.name' => 'required|max:255']);

        $item = $this->currentTeam->items()->make([
            'parent_id' => $this->item->id,
        ]);

        $item
            ->category()
            ->associate(
                $this->currentTeam->folders()->create($this->newFolderState)
            );

        $item->save();

        // Clear & Reset Form
        $this->creatingNewFolder = false;
        $this->newFolderState = [
            'name' => '',
        ];

        // Update item
        $this->item = $this->item->fresh();
    }

    public function getCurrentTeamProperty()
    {
        return auth()->user()->currentTeam;
    }

    public function render()
    {
        return view('livewire.file-browser');
    }
}
