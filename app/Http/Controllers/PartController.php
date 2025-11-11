<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\PartImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PartController extends Controller
{
    public function __construct()
    {
        // require auth for all actions; route middleware will handle role where needed
        $this->middleware('auth');
    }

    // show list of parts (accessible to any authenticated user)
    public function index()
    {
        $parts = Part::orderBy('created_at', 'desc')->paginate(20);
        return view('parts.index', compact('parts'));
    }

    // show create form (admin/coordinator)
    public function create()
    {
        return view('parts.create');
    }

    // store new part (admin/coordinator)
    public function store(Request $request)
    {
    $validated = $request->validate([
            'sku' => 'nullable|string|unique:parts,sku',
        'name' => 'required|string|max:255',
        'brand' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'cost_price' => 'nullable|numeric|min:0',
        'sell_price' => 'nullable|numeric|min:0',
        'current_quantity' => 'required|integer|min:0',
        'images' => 'nullable|array',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
    ]);

    $validated['created_by'] = Auth::id();
    $part = Part::create($validated);

    // handle uploaded images (if any)
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            if (! $file->isValid()) continue;
            $path = $file->store('parts', 'public'); // storage/app/public/parts/...
            $part->images()->create([
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'uploaded_by' => Auth::id(),
            ]);
        }
    }

    return redirect()->route('parts.index')->with('success', 'Part created successfully.');
    }


    // show edit form
    public function edit(Part $part)
    {
        return view('parts.edit', compact('part'));
    }

    // update part (admin/coordinator)
    public function update(Request $request, Part $part)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:parts,sku,'.$part->id,
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cost_price' => 'nullable|numeric|min:0',
            'sell_price' => 'nullable|numeric|min:0',
            'current_quantity' => 'required|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $part->update($validated);

        // Save any newly uploaded images (append to existing)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if (! $file->isValid()) continue;
                $path = $file->store('parts', 'public');
                $part->images()->create([
                    'path' => $path,
                    'filename' => $file->getClientOriginalName(),
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('parts.index')->with('success', 'Part updated successfully.');
    }


    // soft delete part (admin/coordinator)
    public function destroy(Part $part)
    {
        $part->delete();
        return redirect()->route('parts.index')->with('success', 'Part deleted.');
    }

    //forced delete from the index
    public function trashed()
    {
        $parts = Part::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(20);
        return view('parts.trashed', compact('parts'));
    }

    public function restore($id)
    {
        $part = Part::withTrashed()->findOrFail($id);
        $part->restore();
        return redirect()->route('parts.trashed')->with('success','Part restored.');
    }

    public function forceDelete($id)
    {
        $part = Part::withTrashed()->findOrFail($id);
        $part->forceDelete();
        return redirect()->route('parts.trashed')->with('success','Part permanently deleted.');
    }





        public function destroyImage($id)
    {
        $img = PartImage::findOrFail($id);

        // delete file from storage if exists
        if (Storage::disk('public')->exists($img->path)) {
            Storage::disk('public')->delete($img->path);
        }

        $img->delete(); // deletes DB row

        return back()->with('success', 'Image deleted.');
    }


    // optional: show single part (if you want)
    public function show(Part $part)
    {
        return view('parts.show', compact('part'));
    }
}
