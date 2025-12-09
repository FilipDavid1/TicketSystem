<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Ticket::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $categories = Category::all();

        if (Auth::user()->role === 'user') {
            $query->where('user_id', Auth::id());
        } elseif ($request->filled('user')) {
            $query->where('user_id', $request->user);
        }

        $tickets = $query->orderBy('created_at', 'desc')->get();
        return view('tickets.index', compact('tickets', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('tickets.show', ['ticket' => null, 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,in_progress,resolved,rejected',
        ]);

        $validated['user_id'] = Auth::id();

        Ticket::create($validated);

        return redirect()->route('tickets.index')->with('success', 'Tiket bol úspešne vytvorený.');
    }

    public function show($id)
    {
        $ticket = Ticket::with(['comments.user'])->findOrFail($id);
        $categories = Category::all();
        return view('tickets.show', compact('ticket', 'categories'));
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $categories = Category::all();
        return view('tickets.show', compact('ticket', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,in_progress,resolved,rejected',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.index')->with('success', 'Tiket bol úspešne aktualizovaný.');
    }

    public function destroy($id)
    {
        return view('tickets.destroy', compact('ticket'));
    }
}
