<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin']);
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $categories = Category::forAdmin($user->id)->withCount('tickets')->with('admins')->get();
        } else {
            $categories = Category::withCount('tickets')->with('admins')->get();
        }
        
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();

        return view('categories.index', compact('categories', 'admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'admin_ids' => 'nullable|array',
            'admin_ids.*' => 'exists:users,id',
        ]);

        $user = Auth::user();

        $category = Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'created_by' => $user->id,
        ]);

        $adminIds = $validated['admin_ids'] ?? [];
        
        if ($user->role === 'admin' && !in_array($user->id, $adminIds)) {
            $adminIds[] = $user->id;
        }
        
        if (!empty($adminIds)) {
            $category->admins()->attach($adminIds);
        }

        return redirect()->route('categories.index')->with('success', 'Kategória bola úspešne vytvorená.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'admin' && $category->created_by !== $user->id) {
            abort(403, 'Nemáte oprávnenie upravovať túto kategóriu. Môžete upravovať iba kategórie, ktoré ste vytvorili.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'admin_ids' => 'nullable|array',
            'admin_ids.*' => 'exists:users,id',
        ]);

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        $adminIds = $validated['admin_ids'] ?? [];
        
        if ($user->role === 'admin' && !in_array($user->id, $adminIds)) {
            $adminIds[] = $user->id;
        }
        
        $category->admins()->sync($adminIds);

        return redirect()->route('categories.index')->with('success', 'Kategória bola úspešne aktualizovaná.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'admin' && $category->created_by !== $user->id) {
            abort(403, 'Nemáte oprávnenie vymazať túto kategóriu. Môžete mazať iba kategórie, ktoré ste vytvorili.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategória bola úspešne vymazaná.');
    }
}

