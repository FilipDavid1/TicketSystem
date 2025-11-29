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

        $tickets = $query->get();

        return view('tickets.index', compact('tickets', 'categories'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        return view('tickets.store');
    }

    public function show($id)
    {
        return view('tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        return view('tickets.update', compact('ticket'));
    }

    public function destroy($id)
    {
        return view('tickets.destroy', compact('ticket'));
    }
}
