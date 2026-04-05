<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teknisi;
use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    public function index(Request $request)
    {
        $query = Teknisi::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('spesialisasi', 'like', "%{$request->search}%");
            });
        }

        return view('admin.daftar-teknisi', [
            'teknisi'      => $query->paginate(10)->withQueryString(),
            'totalTeknisi' => Teknisi::count(),
            'totalAktif'   => Teknisi::where('status', 'Aktif')->count(),
        ]);
    }

    public function create()
    {
        return view('admin.tambah-teknisi');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:teknisi,email',
            'password'     => 'required|string|min:8',
            'spesialisasi' => 'required|in:Networking,Hardware,Software,CCTV',
            'status'       => 'required|in:Aktif,Tidak Aktif',
        ]);

        Teknisi::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => bcrypt($request->password),
            'spesialisasi' => $request->spesialisasi,
            'status'       => $request->status,
        ]);

        return redirect()->route('admin.teknisi.index')
                         ->with('success', 'Teknisi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        return redirect()->route('admin.teknisi.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'password'     => 'nullable|string|min:8',
            'spesialisasi' => 'required|in:Networking,Hardware,Software,CCTV',
            'status'       => 'required|in:Aktif,Tidak Aktif',
        ]);

        $teknisi = Teknisi::findOrFail($id);
        $data    = $request->only(['name', 'email', 'spesialisasi', 'status']);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $teknisi->update($data);

        return redirect()->route('admin.teknisi.index')
                         ->with('success', 'Data teknisi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Teknisi::findOrFail($id)->delete();

        return redirect()->route('admin.teknisi.index')
                         ->with('success', 'Teknisi berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        Teknisi::whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Teknisi berhasil dihapus.');
    }
}