<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teknisi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'email'        => 'required|email|unique:teknisi,email|unique:users,email',
            'username'     => 'required|string|max:255|unique:users,username',
            'password'     => 'required|string|min:8',
            'spesialisasi' => 'required|in:Networking,Hardware,Software,CCTV,Lainnya',
            'status'       => 'required|in:Aktif,Tidak Aktif',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'nama_lengkap' => $request->name,
                'username'     => $request->username,
                'email'        => $request->email,
                'password'     => bcrypt($request->password),
                'role'         => 'teknisi',
                'is_active'    => $request->status === 'Aktif',
            ]);

            Teknisi::create([
                'user_id'      => $user->id,
                'name'         => $request->name,
                'email'        => $request->email,
                'password'     => bcrypt($request->password),
                'spesialisasi' => $request->spesialisasi,
                'status'       => $request->status,
            ]);
        });

        return redirect()->route('admin.teknisi.index')
                         ->with('success', 'Teknisi berhasil ditambahkan. Akun login sudah dibuat.');
    }

    public function edit($id)
    {
        $teknisi = Teknisi::findOrFail($id);
        return view('admin.edit-teknisi', compact('teknisi'));
    }

    public function update(Request $request, $id)
    {
        $teknisi = Teknisi::findOrFail($id);

        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'password'     => 'nullable|string|min:8',
            'spesialisasi' => 'required|in:Networking,Hardware,Software,CCTV,Lainnya',
            'status'       => 'required|in:Aktif,Tidak Aktif',
        ]);

        DB::transaction(function () use ($request, $teknisi) {
            $data = $request->only(['name', 'email', 'spesialisasi', 'status']);
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }
            $teknisi->update($data);

            if ($teknisi->user_id) {
                $userData = [
                    'nama_lengkap' => $request->name,
                    'email'        => $request->email,
                    'is_active'    => $request->status === 'Aktif',
                ];
                if ($request->filled('password')) {
                    $userData['password'] = bcrypt($request->password);
                }
                User::where('id', $teknisi->user_id)->update($userData);
            }
        });

        return redirect()->route('admin.teknisi.index')
                         ->with('success', 'Data teknisi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $teknisi = Teknisi::findOrFail($id);

        DB::transaction(function () use ($teknisi) {
            if ($teknisi->user_id) {
                User::where('id', $teknisi->user_id)->delete();
            }
            $teknisi->delete();
        });

        return redirect()->route('admin.teknisi.index')
                         ->with('success', 'Teknisi berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $teknisis = Teknisi::whereIn('id', $request->ids)->get();

        DB::transaction(function () use ($teknisis) {
            $userIds = $teknisis->pluck('user_id')->filter();
            User::whereIn('id', $userIds)->delete();
            $teknisis->each->delete();
        });

        return back()->with('success', 'Teknisi berhasil dihapus.');
    }
}