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
        $categories = Category::withCount('tickets')->with('admins')->get();
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

        $category = Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        if (!empty($validated['admin_ids'])) {
            $category->admins()->attach($validated['admin_ids']);
        }

        return redirect()->route('categories.index')->with('success', 'Kategória bola úspešne vytvorená.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

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

        $category->admins()->sync($validated['admin_ids'] ?? []);

        return redirect()->route('categories.index')->with('success', 'Kategória bola úspešne aktualizovaná.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategória bola úspešne vymazaná.');
    }
}

