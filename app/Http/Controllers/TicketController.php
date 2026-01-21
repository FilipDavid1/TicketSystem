<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;

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

        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $categories = Category::forAdmin($user->id)->get();
            $query->forAdmin($user->id);
        } else {
            $categories = Category::all();
        }

        if ($user->role === 'user') {
            $query->where('user_id', Auth::id());
        } elseif ($request->filled('user') && in_array($user->role, ['admin', 'superadmin'])) {
            $query->where('user_id', $request->user);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $users = [];
        if (in_array($user->role, ['admin', 'superadmin'])) {
            $users = \App\Models\User::orderBy('name')->get();
        }
        
        return view('tickets.index', compact('tickets', 'categories', 'users'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $categories = Category::forAdmin($user->id)->get();
        } else {
            $categories = Category::all();
        }
        
        return view('tickets.show', ['ticket' => null, 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (in_array($user->role, ['admin', 'superadmin'])) {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'priority' => 'required|in:low,medium,high',
                'status' => 'required|in:open,in_progress,resolved,rejected',
            ]);
        } else {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'priority' => 'required|in:low,medium,high',
            ]);
            $validated['status'] = 'open';
        }

        $validated['user_id'] = Auth::id();

        Ticket::create($validated);

        return redirect()->route('tickets.index')->with('success', 'Tiket bol úspešne vytvorený.');
    }

    public function show($id)
    {
        $ticket = Ticket::with(['comments.user'])->findOrFail($id);
        $user = Auth::user();
        
        if ($user->role === 'user' && $ticket->user_id !== $user->id) {
            abort(403, 'Nemáte oprávnenie na zobrazenie tohto tiketu.');
        }
        
        if ($user->role === 'admin') {
            $hasAccess = $ticket->category->admins()->where('users.id', $user->id)->exists();
            if (!$hasAccess) {
                abort(403, 'Nemáte oprávnenie na zobrazenie tohto tiketu.');
            }
            $categories = Category::forAdmin($user->id)->get();
        } else {
            $categories = Category::all();
        }
        
        return view('tickets.show', compact('ticket', 'categories'));
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $user = Auth::user();
        
        if ($user->role === 'user') {
            if ($ticket->user_id !== $user->id) {
                abort(403, 'Nemáte oprávnenie na úpravu tohto tiketu.');
            }
        } elseif ($user->role === 'admin') {
            $hasAccess = $ticket->category->admins()->where('users.id', $user->id)->exists();
            if (!$hasAccess) {
                abort(403, 'Nemáte oprávnenie na úpravu tohto tiketu.');
            }
        }
        
        if ($user->role === 'admin') {
            $categories = Category::forAdmin($user->id)->get();
        } else {
            $categories = Category::all();
        }
        
        return view('tickets.show', compact('ticket', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'user') {
            if ($ticket->user_id !== $user->id) {
                abort(403, 'Nemáte oprávnenie na úpravu tohto tiketu.');
            }
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'priority' => 'required|in:low,medium,high',
            ]);
            $validated['description'] = Purifier::clean($validated['description']);
        } elseif ($user->role === 'admin') {
            $hasAccess = $ticket->category->admins()->where('users.id', $user->id)->exists();
            if (!$hasAccess) {
                abort(403, 'Nemáte oprávnenie na úpravu tohto tiketu.');
            }
            $validated = $request->validate([
                'status' => 'required|in:open,in_progress,resolved,rejected',
            ]);
        } else {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'priority' => 'required|in:low,medium,high',
                'status' => 'required|in:open,in_progress,resolved,rejected',
            ]);
            $validated['description'] = Purifier::clean($validated['description']);
        }

        $ticket->update($validated);

        return redirect()->route('tickets.index')->with('success', 'Tiket bol úspešne aktualizovaný.');
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $user = Auth::user();

        if ($user->role != 'superadmin' && $ticket->user_id !== $user->id) {
            abort(403, 'Nemáte oprávnenie na zmazanie tohto tiketu. Môžete len zmeniť jeho stav.');
        }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Tiket bol úspešne zmazaný.');
    }

    public function filter(Request $request)
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

        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $query->forAdmin($user->id);
        }

        if ($user->role === 'user') {
            $query->where('user_id', Auth::id());
        } elseif ($request->filled('user') && in_array($user->role, ['admin', 'superadmin'])) {
            $query->where('user_id', $request->user);
        }

        $countQuery = clone $query;
        $allTickets = $countQuery->get();
        
        $tickets = $query->with(['category', 'user'])->orderBy('created_at', 'desc')->paginate(10);
        
        return response()->json([
            'tickets' => $tickets->items(),
            'pagination' => [
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'per_page' => $tickets->perPage(),
                'total' => $tickets->total(),
                'from' => $tickets->firstItem(),
                'to' => $tickets->lastItem(),
            ],
            'counts' => [
                'open' => $allTickets->where('status', 'open')->count(),
                'in_progress' => $allTickets->where('status', 'in_progress')->count(),
                'resolved' => $allTickets->where('status', 'resolved')->count(),
                'total' => $tickets->total()
            ]
        ]);
    }
}
