<?php

namespace App\Http\Controllers;

use Storage;
use App\Models\File;
use App\Models\Item;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $item = Item::with('children.category', 'ancestorsAndSelf.category')
            ->forCurrentTeam()
            ->where(
                'uuid',
                $request->get(
                    'uuid',
                    Item::forCurrentTeam()
                        ->whereNull('parent_id')
                        ->first()->uuid
                )
            )
            ->firstOrFail();

        return view('files', [
            'item' => $item,
            'ancestors' => $item
                ->ancestorsAndSelf()
                ->breadthFirst()
                ->get(),
        ]);
    }

    public function download(File $file)
    {
        $this->authorize('download', $file);

        return Storage::disk('local')->download($file->path, $file->name);
    }
}
