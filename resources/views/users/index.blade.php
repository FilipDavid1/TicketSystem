@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 col-md-6">
            <h2>Správa používateľov</h2>
            <p class="text-muted">Prehľad a správa všetkých používateľov systému</p>
        </div>
        <div class="col-12 col-md-6">
            <div class="d-flex justify-content-md-end">
                <a href="{{ route('users.create') }}" class="dark-btn">
                <i class="bi bi-plus"></i>
                    Vytvoriť nového používateľa
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Meno</th>
                            <th scope="col">Email</th>
                            <th scope="col">Rola</th>
                            <th scope="col">Vytvorený</th>
                            <th scope="col" class="text-end">Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if($user->id === auth()->id())
                                <span class="badge badge-resolved ms-2">Vy</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'superadmin')
                                <span class="badge badge-superadmin">
                                    super-admin
                                </span>
                                @elseif($user->role === 'admin')
                                <span class="badge badge-low">
                                    admin
                                </span>
                                @else
                                <span class="badge badge-resolved">
                                    používateľ
                                </span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('users.edit', $user->id) }}" class="light-btn me-2">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Naozaj chcete vymazať tohto používateľa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="light-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Žiadni používatelia neboli nájdení.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection