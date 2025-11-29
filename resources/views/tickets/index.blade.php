@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12 col-md-6 col-lg-4">
      <div class="wrapper">
        Otvorené tickety ({{ $tickets->where('status', 'open')->count() }})
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
      <div class="wrapper">
        v riešení ({{ $tickets->where('status', 'in_progress')->count() }})
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
      <div class="wrapper">
        Vyriešené tickety ({{ $tickets->where('status', 'resolved')->count() }})
      </div>
    </div>
  </div>

  <div class="wrapper filters">
    <div class="row">
      <div class="col-6">
        <p class="d-flex align-items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-funnel" viewBox="0 0 16 16">
            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z" />
          </svg>
          <span>Filtre</span>
        </p>
      </div>
      <div class="col-6">
        <div class="d-flex justify-content-end">
          @if(request()->has('status') || request()->has('category') || request()->has('priority') || request()->has('search'))
          <a href="{{ route('tickets.index') }}" class="light-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
              <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
            </svg>
            <span>Zrušiť filtre</span>
          </a>
          @endif
        </div>
      </div>
    </div>

    <form id="filtersForm" action="{{ route('tickets.index') }}" method="GET">
      <div class="row">
        <!-- Search -->
        <div class="col-12 col-md-3">
          <label for="search">Hľadať</label>
          <input type="text" name="search" id="search" class="form-control"
            placeholder="Hľadať"
            value="{{ request('search') }}">
        </div>

        <!-- Status -->
        <div class="col-12 col-md-3">
          <label for="status">Stav</label>
          <select name="status" id="status" class="form-control" onchange="this.form.submit()">
            <option value="">Všetky</option>
            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Otvorené</option>
            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>V riešení</option>
            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Vyriešené</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Zamietnuté</option>
          </select>
        </div>

        <!-- Category -->
        <div class="col-12 col-md-3">
          <label for="category">Kategória</label>
          <select name="category" id="category" class="form-control" onchange="this.form.submit()">
            <option value="">Všetky</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
            @endforeach
          </select>
        </div>

        <!-- Priority -->
        <div class="col-12 col-md-3">
          <label for="priority">Priorita</label>
          <select name="priority" id="priority" class="form-control" onchange="this.form.submit()">
            <option value="">Všetky</option>
            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Nízka</option>
            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Stredná</option>
            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Vysoká</option>
          </select>
        </div>
      </div>
    </form>
  </div>

  <div class="wrapper">
    @if(Auth::user()->role === 'user')
    <p>Moje tickety ({{ $tickets->count() }})</p>
    @else
    <p>Všetky tickety ({{ $tickets->count() }})</p>
    @endif
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Názov</th>
          <th>Kategória</th>
          <th>Stav</th>
          <th>Priorita</th>
          <th>Užívateľ</th>
          <th>Čas vytvorenia</th>
          <th>Akcia</th>
        </tr>
      </thead>
      <tbody>
        @foreach($tickets as $ticket)
        <tr>
          <td>{{ $ticket->id }}</td>
          <td>{{ $ticket->title }}</td>
          <td>{{ $ticket->category->name }}</td>
          <td>
            @if($ticket->status === 'open')
            <span class="badge badge-open">
              Otvorené
            </span>
            @elseif($ticket->status === 'in_progress')
            <span class="badge badge-in-progress">
              V riešení
            </span>
            @elseif($ticket->status === 'resolved')
            <span class="badge badge-resolved">
              Vyriešené
            </span>
            @else
            <span class="badge badge-rejected">
              Zamietnuté
            </span>
            @endif
          </td>
          <td>
            @if($ticket->priority === 'low')
            <span class="badge badge-low">
              Nízka
            </span>
            @elseif($ticket->priority === 'medium')
            <span class="badge badge-medium">
              Stredná
            </span>
            @else
            <span class="badge badge-in-progress">
              Vysoká
            </span>
            @endif
            </span>
          </td>
          <td>{{ $ticket->user->name }}</td>
          <td>{{ $ticket->created_at->format('d.m.Y H:i') }}</td>
          <td>
            <a href="{{ route('tickets.show', $ticket->id) }}" class="light-btn">Zobraziť</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection