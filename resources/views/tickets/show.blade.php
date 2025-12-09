@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12">
      <h2>
        @if(isset($ticket) && $ticket)
          Upraviť tiket
        @else
          Vytvoriť tiket
        @endif
      </h2>
    </div>
  </div>

  <div class="wrapper">
    <form method="POST" 
      action="{{ isset($ticket) && $ticket ? route('tickets.update', $ticket->id) : route('tickets.store') }}">
      @csrf
      @if(isset($ticket) && $ticket)
        @method('PUT')
      @endif
      
      <div class="row">
        <div class="col-12 mb-3">
          <label for="title" class="form-label">Názov <span class="text-danger">*</span></label>
          <input type="text" 
            class="form-control @error('title') is-invalid @enderror" 
            id="title" 
            name="title" 
            value="{{ old('title', isset($ticket) && $ticket ? $ticket->title : '') }}" 
            required
            placeholder="Zadajte názov tiketu">
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-12 mb-3">
          <label for="description" class="form-label">Popis <span class="text-danger">*</span></label>
          <textarea class="form-control @error('description') is-invalid @enderror" 
            id="description" 
            name="description" 
            rows="6" 
            required
            placeholder="Zadajte popis tiketu">{{ old('description', isset($ticket) && $ticket ? $ticket->description : '') }}</textarea>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-12 col-md-6 mb-3">
          <label for="category_id" class="form-label">Kategória <span class="text-danger">*</span></label>
          <select class="form-control @error('category_id') is-invalid @enderror" 
            id="category_id" 
            name="category_id" 
            required>
            <option value="">Vyberte kategóriu</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" 
                {{ old('category_id', isset($ticket) && $ticket ? $ticket->category_id : '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
          @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-12 col-md-3 mb-3">
          <label for="priority" class="form-label">Priorita <span class="text-danger">*</span></label>
          <select class="form-control @error('priority') is-invalid @enderror" 
            id="priority" 
            name="priority" 
            required>
            <option value="">Vyberte prioritu</option>
            <option value="low" {{ old('priority', isset($ticket) && $ticket ? $ticket->priority : '') == 'low' ? 'selected' : '' }}>Nízka</option>
            <option value="medium" {{ old('priority', isset($ticket) && $ticket ? $ticket->priority : '') == 'medium' ? 'selected' : '' }}>Stredná</option>
            <option value="high" {{ old('priority', isset($ticket) && $ticket ? $ticket->priority : '') == 'high' ? 'selected' : '' }}>Vysoká</option>
          </select>
          @error('priority')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-12 col-md-3 mb-3">
          <label for="status" class="form-label">Stav <span class="text-danger">*</span></label>
          <select class="form-control @error('status') is-invalid @enderror" 
            id="status" 
            name="status" 
            required>
            <option value="">Vyberte stav</option>
            <option value="open" {{ old('status', isset($ticket) && $ticket ? $ticket->status : '') == 'open' ? 'selected' : '' }}>Otvorené</option>
            <option value="in_progress" {{ old('status', isset($ticket) && $ticket ? $ticket->status : '') == 'in_progress' ? 'selected' : '' }}>V riešení</option>
            <option value="resolved" {{ old('status', isset($ticket) && $ticket ? $ticket->status : '') == 'resolved' ? 'selected' : '' }}>Vyriešené</option>
            <option value="rejected" {{ old('status', isset($ticket) && $ticket ? $ticket->status : '') == 'rejected' ? 'selected' : '' }}>Zamietnuté</option>
          </select>
          @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('tickets.index') }}" class="light-btn">Zrušiť</a>
        <button type="submit" class="dark-btn">
          @if(isset($ticket) && $ticket)
            Uložiť zmeny
          @else
            Vytvoriť tiket
          @endif
        </button>
      </div>
    </form>
  </div>

  <!-- Comments -->
  <div class="wrapper">
    <h3>Komentáre</h3>
    
    @if($ticket->comments->count() > 0)
      <div class="mb-4">
        @foreach($ticket->comments as $comment)
          <div class="comment mb-3 p-3 border rounded">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <strong>{{ $comment->user->name }}</strong>
              <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
            <p class="mb-0">{{ $comment->content }}</p>
          </div>
        @endforeach
      </div>
    @else
      <p class="text-muted mb-4">Žiadne komentáre.</p>
    @endif

    <form method="POST" action="{{ route('comments.store', $ticket->id) }}">
      @csrf
      <div class="row">
        <div class="col-12 mb-3">
          <label for="content" class="form-label">Pridať komentár</label>
          <textarea class="form-control @error('content') is-invalid @enderror" 
            id="content" 
            name="content" 
            rows="3" 
            required
            placeholder="Zadajte komentár"></textarea>
          @error('content')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="d-flex justify-content-end gap-2">
        <button type="submit" class="dark-btn">
          Pridať komentár
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
