<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        //search and pagination
        $keyword = $request->query('search');

        $users = User::query()
            ->when($keyword, function ($q) use ($keyword) {
                $q->where(function ($q2) use ($keyword) {
                    $q2->where('name',  'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
                });
            })
            ->orderby('created_at', 'asc')
            ->paginate(10)
            ->appends(['search' => $keyword]);
            
        return view('admin-super.users.index', compact('users', 'keyword'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email|max:255',
            'role'      => 'required|in:admin-super,admin-gudang,supervisor,driver,sales',
            'password'  => 'required|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id . '|max:255',
            'role'      => 'required|in:admin-super,admin-gudang,supervisor,driver,sales',
        ];
        
        // Validasi password hanya jika diisi
        if ($request->filled('password')) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        $validated = $request->validate($rules, [
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        // Opsional: cegah admin menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun Anda sendiri!');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus!');
    }
}
