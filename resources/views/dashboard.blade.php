@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm 
                @if($user->isSuperAdmin()) bg-primary
                @elseif($user->isAdmin()) bg-success
                @else bg-secondary
                @endif text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="mb-1">Vitajte, {{ $user->name }}!</h3>
                            <p class="text-light mb-0">
                                @if($user->isSuperAdmin())
                                    Máte plný prístup k správe celého systému
                                @elseif($user->isAdmin())
                                    Môžete spravovať kategórie a tikety
                                @else
                                    Môžete vytvárať a spravovať svoje tikety
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        @if($user->isSuperAdmin())
            <!-- Superadmin Cards -->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('users.index') }}">
                    <div class="card hoverable h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-muted mb-1">Celkový počet používateľov</h6>
                                <div class="p-1 rounded">
                                    <i class="bi bi-people text-primary fs-3"></i>
                                </div>
                            </div>
                            <div class="d-flex flex-column justify-content-between align-items-start mb-3">
                                <h2 class="mb-0">{{ $usersCount }}</h2>
                                <p class="text-muted">Celkový počet používateľov v systéme</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3">
                <a href="{{ route('categories.index') }}">
                    <div class="card hoverable h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-muted mb-1">Celkový počet kategórií</h6>
                                <div class="p-1 rounded">
                                    <i class="bi bi-folder text-info fs-3"></i>
                                </div>
                            </div>
                            <div class="d-flex flex-column justify-content-between align-items-start mb-3">
                                <h2 class="mb-0">{{ $categoriesCount }}</h2>
                                <p class="text-muted">Celkový počet kategórií v systéme</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3">
                <a href="{{ route('tickets.index') }}">
                    <div class="card hoverable h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-muted mb-1">Celkový počet tiketov</h6>
                                <div class="p-1 rounded">
                                    <i class="bi bi-ticket text-success fs-3"></i>
                                </div>
                            </div>
                            <div class="d-flex flex-column justify-content-between align-items-start mb-3">
                                <h2 class="mb-0">{{ $ticketsCount }}</h2>
                                <p class="text-muted">Celkový počet tiketov v systéme</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card hoverable h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-muted mb-1">Celkový počet komentárov</h6>
                            <div class="p-1 rounded">
                                <i class="bi bi-chat-dots text-warning fs-3"></i>
                            </div>
                        </div>
                        <div class="d-flex flex-column justify-content-between align-items-start mb-3">
                            <h2 class="mb-0">{{ $commentsCount }}</h2>
                            <p class="text-muted">Celkový počet komentárov v systéme</p>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($user->isAdmin())
            <!-- Admin Cards -->
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('categories.index') }}">
                    <div class="card hoverable h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-muted mb-1">Moje kategórie</h6>
                                <div class="p-1 rounded">
                                    <i class="bi bi-folder text-info fs-3"></i>
                                </div>
                            </div>
                            <div class="d-flex flex-column justify-content-between align-items-start mb-3">
                                <h2 class="mb-0">{{ $categoriesCount }}</h2>
                                <p class="text-muted">Počet kategórií priradených vám</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4">
                <a href="{{ route('tickets.index') }}">
                    <div class="card hoverable h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-muted mb-1">Dostupné tikety</h6>
                                <div class="p-1 rounded">
                                    <i class="bi bi-ticket text-success fs-3"></i>
                                </div>
                            </div>
                            <div class="d-flex flex-column justify-content-between align-items-start mb-3">
                                <h2 class="mb-0">{{ $ticketsCount }}</h2>
                                <p class="text-muted">Počet tiketov z vašich kategórií</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card hoverable h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-muted mb-1">Dostupné komentáre</h6>
                            <div class="p-1 rounded">
                                <i class="bi bi-chat-dots text-warning fs-3"></i>
                            </div>
                        </div>
                        <div class="d-flex flex-column justify-content-between align-items-start mb-3">
                            <h2 class="mb-0">{{ $commentsCount }}</h2>
                            <p class="text-muted">Počet komentárov z vašich kategórií</p>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- User Cards -->
            <div class="col-md-6 col-lg-6">
                <a href="{{ route('tickets.index') }}">
                    <div class="card hoverable h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-muted mb-1">Moje tikety</h6>
                                <div class="p-1 rounded">
                                    <i class="bi bi-ticket text-primary fs-3"></i>
                                </div>
                            </div>
                            <div class="d-flex flex-column justify-content-between align-items-start mb-3">
                                <h2 class="mb-0">{{ $ticketsCount }}</h2>
                                <p class="text-muted">Celkový počet vašich tiketov</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-6">
                <div class="card hoverable h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-muted mb-1">Moje komentáre</h6>
                            <div class="p-1 rounded">
                                <i class="bi bi-chat-dots text-warning fs-3"></i>
                            </div>
                        </div>
                        <div class="d-flex flex-column justify-content-between align-items-start mb-3">
                            <h2 class="mb-0">{{ $commentsCount }}</h2>
                            <p class="text-muted">Celkový počet vašich komentárov</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Recent Lists -->
    <div class="row g-4">
        @if($user->isSuperAdmin())
            <!-- Recent Users -->
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Nedávni používatelia</h5>
                            <a href="{{ route('users.index') }}" class="transparent-btn">
                                Zobraziť všetkých
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($recentUsers as $user)
                            <a href="{{ route('users.edit', $user->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-1">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                    <span class="badge 
                                        @if($user->role === 'superadmin') badge-rejected
                                        @elseif($user->role === 'admin') badge-in-progress
                                        @else badge-open
                                        @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Tickets -->
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Nedávne tikety</h5>
                            <a href="{{ route('tickets.index') }}" class="transparent-btn">
                            Zobraziť všetky
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($recentTickets as $ticket)
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-start px-0">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ Str::limit($ticket->title, 40) }}</h6>
                                        <small class="text-muted">od {{ $ticket->user->name }}</small>
                                    </div>
                                    @if($ticket->priority === 'medium')
                                        <span class="badge ms-2 badge-medium">
                                            Stredná
                                        </span>
                                    @elseif($ticket->priority === 'high')
                                        <span class="badge ms-2 badge-in-progress">
                                            Vysoká
                                        </span>
                                    @else
                                        <span class="badge ms-2 badge-rejected">
                                            Nízka
                                        </span>
                                    @endif
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        @elseif($user->isAdmin())
            <!-- Recent Tickets for Admin -->
            <div class="col-12">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Nedávne tikety</h5>
                            <a href="{{ route('tickets.index') }}" class="transparent-btn">
                                Zobraziť všetky tikety
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($recentTickets as $ticket)
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-start px-0">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ Str::limit($ticket->title, 40) }}</h6>
                                        <small class="text-muted">od {{ $ticket->user->name }}</small>
                                    </div>
                                    @if($ticket->priority === 'low')
                                        <span class="badge ms-2 badge-low">
                                            Nízka
                                        </span>
                                    @elseif($ticket->priority === 'medium')
                                        <span class="badge ms-2 badge-medium">
                                            Stredná
                                        </span>
                                    @else
                                        <span class="badge ms-2 badge-rejected">
                                            Vysoká
                                        </span>
                                    @endif
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- Recent Tickets for User -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Moje nedávne tikety</h5>
                    </div>
                    <div class="card-body">
                        @forelse($recentTickets as $ticket)
                            @if($loop->first)
                            <div class="list-group list-group-flush">
                            @endif
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-start px-0">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $ticket->title }}</h6>
                                            <small class="text-muted">{{ Str::limit($ticket->description, 80) }}</small>
                                        </div>
                                        @if($ticket->priority === 'low')
                                        <span class="badge ms-2 badge-low">
                                            Nízka
                                        </span>
                                        @elseif($ticket->priority === 'medium')
                                        <span class="badge ms-2 badge-medium">
                                            Stredná
                                        </span>
                                        @else   
                                        <span class="badge ms-2 badge-rejected">
                                            Vysoká
                                        </span>
                                        @endif
                                    </div>
                                </a>
                            @if($loop->last)
                            </div>
                            @endif
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                <p class="text-muted mb-3">Zatiaľ nemáte žiadne tikety</p>
                                <a href="{{ route('tickets.create') }}" class="dark-btn">
                                    Vytvoriť prvý tiket
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection