<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::orderBy('id','desc')->paginate(10);
        return view('admin.stores.index', compact('stores'));
    }

    public function create()
    {
        return view('admin.stores.form', ['store' => new Store()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Store::create($data);
        return redirect()->route('admin.stores.index')->with('success', 'Store created');
    }

    public function edit(Store $store)
    {
        return view('admin.stores.form', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $data = $this->validateData($request);
        $store->update($data);
        return redirect()->route('admin.stores.index')->with('success', 'Store updated');
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return redirect()->route('admin.stores.index')->with('success', 'Store deleted');
    }

    public function toggleStatus(Request $request, Store $store)
    {
        $request->validate([
            'active' => 'required|boolean'
        ]);

        $store->update([
            'active' => $request->boolean('active')
        ]);

        return response()->json([
            'success' => true,
            'status' => $store->active ? 'activated' : 'deactivated'
        ]);
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'radius_meters' => 'required|integer|min:1|max:5000',
            'active' => 'nullable|boolean',
        ]) + [ 'active' => (bool)$request->boolean('active') ];
    }
}
