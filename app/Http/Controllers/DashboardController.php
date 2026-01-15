<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->role === 'superadmin') {
            $data['user'] = $user;
            $data['usersCount'] = User::count();
            $data['categoriesCount'] = Category::count();
            $data['ticketsCount'] = Ticket::count();
            $data['commentsCount'] = Comment::count();
            $data['recentUsers'] = User::orderBy('created_at', 'desc')->limit(5)->get();
            $data['recentTickets'] = Ticket::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } elseif ($user->role === 'admin') {
            $data['user'] = $user;
            $data['categoriesCount'] = $user->categories()->count();
            $data['ticketsCount'] = Ticket::forAdmin($user->id)->count();
            $data['commentsCount'] = Comment::whereHas('ticket', function($q) use ($user) {
                $q->forAdmin($user->id);
            })->count();
            $data['recentTickets'] = Ticket::forAdmin($user->id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            $data['user'] = $user;
            $data['ticketsCount'] = $user->tickets()->count();
            $data['commentsCount'] = $user->comments()->count();
            $data['recentTickets'] = $user->tickets()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        return view('dashboard', $data);
    }
}
