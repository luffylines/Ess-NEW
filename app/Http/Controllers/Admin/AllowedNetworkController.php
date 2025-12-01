<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllowedNetwork;
use Illuminate\Http\Request;

class AllowedNetworkController extends Controller
{
    public function index()
    {
        $networks = AllowedNetwork::orderBy('id','desc')->paginate(10);
        return view('admin.networks.index', compact('networks'));
    }

    public function create()
    {
        return view('admin.networks.form', ['network' => new AllowedNetwork()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['ip_ranges'] = $this->parseNetworks($request->input('ip_ranges_text'));
        AllowedNetwork::create($data);
        return redirect()->route('admin.networks.index')->with('success', 'Network created');
    }

    public function edit(AllowedNetwork $network)
    {
        return view('admin.networks.form', compact('network'));
    }

    public function update(Request $request, AllowedNetwork $network)
    {
        $data = $this->validateData($request);
        $data['ip_ranges'] = $this->parseNetworks($request->input('ip_ranges_text'));
        $network->update($data);
        return redirect()->route('admin.networks.index')->with('success', 'Network updated');
    }

    public function destroy(AllowedNetwork $network)
    {
        $network->delete();
        return redirect()->route('admin.networks.index')->with('success', 'Network deleted');
    }

    public function toggleStatus(Request $request, AllowedNetwork $network)
    {
        $request->validate([
            'active' => 'required|boolean'
        ]);

        $network->update([
            'active' => $request->boolean('active')
        ]);

        return response()->json([
            'success' => true,
            'status' => $network->active ? 'activated' : 'deactivated'
        ]);
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'nullable|boolean',
        ]) + [ 'active' => (bool)$request->boolean('active') ];
    }

    private function parseNetworks(?string $text): array
    {
        if (!$text) return [];
        $lines = preg_split('/\r?\n/', $text);
        return array_values(array_filter(array_map('trim', $lines), fn($v) => $v !== ''));
    }
}