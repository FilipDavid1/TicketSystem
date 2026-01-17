@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-6">
      <h2>Tikety</h2>
      <p>Prehľad všetkých tiketov</p>
    </div>
    <div class="col-6">
      <div class="d-flex justify-content-end">
        <a href="{{ route('tickets.create') }}" class="btn dark-btn">
        <i class="bi bi-plus"></i>
          Vytvoriť tiket
        </a>
      </div>
    </div>
  </div>
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
          <i class="bi bi-funnel"></i>
          <span>Filtre</span>
        </p>
      </div>
      <div class="col-6">
        <div class="d-flex justify-content-end">
          @if(request()->has('status') || request()->has('category') || request()->has('priority') || request()->has('search') || request()->has('user'))
          <a href="{{ route('tickets.index') }}" class="light-btn">
            <i class="bi bi-x"></i>
            <span>Zrušiť filtre</span>
          </a>
          @endif
        </div>
      </div>
    </div>

    <form id="filtersForm" action="{{ route('tickets.index') }}" method="GET">
      <div class="row">
        <div class="col-12 col-md-3">
          <label for="search">Hľadať</label>
          <input type="text" name="search" id="search" class="form-control"
            placeholder="Hľadať"
            value="{{ request('search') }}">
        </div>

        <div class="col-12 col-md-3">
          <label for="status">Stav</label>
          <select name="status" id="status" class="form-control">
            <option value="">Všetky</option>
            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Otvorené</option>
            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>V riešení</option>
            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Vyriešené</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Zamietnuté</option>
          </select>
        </div>

        <div class="col-12 col-md-3">
          <label for="category">Kategória</label>
          <select name="category" id="category" class="form-control">
            <option value="">Všetky</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="col-12 col-md-3">
          <label for="priority">Priorita</label>
          <select name="priority" id="priority" class="form-control">
            <option value="">Všetky</option>
            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Nízka</option>
            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Stredná</option>
            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Vysoká</option>
          </select>
        </div>

        @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
        <div class="col-12 col-md-3 mt-3">
          <label for="user">Používateľ</label>
          <select name="user" id="user" class="form-control">
            <option value="">Všetci</option>
            @foreach($users as $filterUser)
            <option value="{{ $filterUser->id }}" {{ request('user') == $filterUser->id ? 'selected' : '' }}>
              {{ $filterUser->name }}
            </option>
            @endforeach
          </select>
        </div>
        @endif
      </div>
    </form>
  </div>

  <div class="wrapper">
    @if(Auth::user()->role === 'user')
    <p id="ticketsCount">Moje tickety ({{ $tickets->count() }})</p>
    @else
    <p id="ticketsCount">Všetky tickety ({{ $tickets->count() }})</p>
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
      <tbody id="ticketsTableBody">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filtersForm = document.getElementById('filtersForm');
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const categorySelect = document.getElementById('category');
    const prioritySelect = document.getElementById('priority');
    const userSelect = document.getElementById('user');
    let searchTimeout;

    // Function to fetch and update tickets
    function filterTickets() {
        const formData = new FormData(filtersForm);
        const params = new URLSearchParams(formData);

        fetch('{{ route("tickets.filter") }}?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            updateTicketsTable(data.tickets);
            updateTicketsCounts(data.counts);
        })
        .catch(error => {
            console.error('Error filtering tickets:', error);
        });
    }

    // Update tickets table
    function updateTicketsTable(tickets) {
        const tbody = document.getElementById('ticketsTableBody');
        
        if (tickets.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center">Žiadne tikety</td></tr>';
            return;
        }

        tbody.innerHTML = tickets.map(ticket => {
            const statusBadges = {
                'open': '<span class="badge badge-open">Otvorené</span>',
                'in_progress': '<span class="badge badge-in-progress">V riešení</span>',
                'resolved': '<span class="badge badge-resolved">Vyriešené</span>',
                'rejected': '<span class="badge badge-rejected">Zamietnuté</span>'
            };

            const priorityBadges = {
                'low': '<span class="badge badge-low">Nízka</span>',
                'medium': '<span class="badge badge-medium">Stredná</span>',
                'high': '<span class="badge badge-in-progress">Vysoká</span>'
            };

            const createdDate = new Date(ticket.created_at);
            const formattedDate = createdDate.toLocaleString('sk-SK', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            return `
                <tr>
                    <td>${ticket.id}</td>
                    <td>${ticket.title}</td>
                    <td>${ticket.category.name}</td>
                    <td>${statusBadges[ticket.status]}</td>
                    <td>${priorityBadges[ticket.priority]}</td>
                    <td>${ticket.user.name}</td>
                    <td>${formattedDate}</td>
                    <td>
                        <a href="/tickets/${ticket.id}" class="light-btn">Zobraziť</a>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Update ticket counts
    function updateTicketsCounts(counts) {
        const countElements = document.querySelectorAll('.wrapper .col-12.col-md-6.col-lg-4 .wrapper');
        if (countElements.length >= 3) {
            countElements[0].textContent = `Otvorené tickety (${counts.open})`;
            countElements[1].textContent = `v riešení (${counts.in_progress})`;
            countElements[2].textContent = `Vyriešené tickety (${counts.resolved})`;
        }

        const userRole = '{{ Auth::user()->role }}';
        const ticketsCount = document.getElementById('ticketsCount');
        if (ticketsCount) {
            if (userRole === 'user') {
                ticketsCount.textContent = `Moje tickety (${counts.total})`;
            } else {
                ticketsCount.textContent = `Všetky tickety (${counts.total})`;
            }
        }
    }

    // Add event listeners for immediate filter changes
    statusSelect.addEventListener('change', filterTickets);
    categorySelect.addEventListener('change', filterTickets);
    prioritySelect.addEventListener('change', filterTickets);
    
    if (userSelect) {
        userSelect.addEventListener('change', filterTickets);
    }

    // Add debounced event listener for search input
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterTickets, 500);
    });
});
</script>
@endsection