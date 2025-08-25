<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Exibir dashboard administrativo
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $activeUsers = User::whereNotNull('email_verified_at')->count();
        $adminUsers = User::where('role', 'admin')->count();
        $users = User::latest()->paginate(10);

        return view('admin.dashboard', compact('totalUsers', 'activeUsers', 'adminUsers', 'users'));
    }

    /**
     * Atualizar role do usuário
     */
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', Rule::in(['admin', 'user'])]
        ]);

        $user->update([
            'role' => $request->role
        ]);

        return redirect()->back()->with('success', 'Role do usuário atualizada com sucesso!');
    }

    /**
     * Deletar usuário
     */
    public function deleteUser(User $user)
    {
        // Não permitir deletar o próprio usuário
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Você não pode deletar sua própria conta!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Usuário deletado com sucesso!');
    }

    /**
     * Criar novo usuário
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['admin', 'user'])]
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Usuário criado com sucesso!');
    }
}
