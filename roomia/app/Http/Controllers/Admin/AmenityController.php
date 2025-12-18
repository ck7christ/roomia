<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Amenity;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $amenities = Amenity::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.amenities.index', compact('amenities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $amenity = new Amenity();
        return view('admin.amenities.create', compact('amenity'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $this->validateData($request);

        Amenity::create($data);

        return redirect()
            ->route('admin.amenities.index')
            ->with('success', 'Tạo tiện nghi thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Amenity $amenity)
    {
        //
        return view('admin.amenities.show', compact('amenity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Amenity $amenity)
    {
        //
        return view('admin.amenities.edit', compact('amenity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Amenity $amenity)
    {
        //
        $data = $this->validateData($request, $amenity->id);

        $amenity->update($data);

        return redirect()
            ->route('admin.amenities.index')
            ->with('success', 'Cập nhật tiện nghi thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Amenity $amenity)
    {
        //
        $amenity->delete();

        return redirect()
            ->route('admin.amenities.index')
            ->with('success', 'Xóa tiện nghi thành công.');
    }

    private function validateData(Request $request, $id = null): array
    {
        return $request->validate([
            'name'       => 'required|string|max:255',
            'code'       => 'required|string|max:100|unique:amenities,code,' . ($id ?? 'NULL') . ',id',
            'group'      => 'nullable|string|max:100',
            'icon_class' => 'nullable|string|max:255',
            'is_active'  => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
    }
}
