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
    @if(Auth::user()->role === 'admin' && isset($ticket) && $ticket)
    <div class="alert alert-info mb-3">
      <strong>Poznámka:</strong> Ako admin môžete zmeniť len stav tiketu. Ostatné polia sú len na čítanie.
    </div>
    @endif
    
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
            {{ Auth::user()->role === 'admin' && isset($ticket) && $ticket ? 'readonly disabled' : 'required' }}
            placeholder="Zadajte názov tiketu">
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-12 mb-3">
          <label for="description" class="form-label">Popis <span class="text-danger">*</span></label>
          @if(Auth::user()->role === 'admin' && isset($ticket) && $ticket)
            <div class="form-control" style="min-height: 150px; background-color: #e9ecef; cursor: not-allowed;">
              {!! isset($ticket) && $ticket ? $ticket->description : '' !!}
            </div>
          @else
            <textarea class="form-control @error('description') is-invalid @enderror" 
              id="description" 
              name="description" 
              rows="6" 
              required
              placeholder="Zadajte popis tiketu">{{ old('description', isset($ticket) && $ticket ? $ticket->description : '') }}</textarea>
          @endif
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-12 col-md-6 mb-3">
          <label for="category_id" class="form-label">Kategória <span class="text-danger">*</span></label>
          <select class="form-control @error('category_id') is-invalid @enderror" 
            id="category_id" 
            name="category_id" 
            {{ Auth::user()->role === 'admin' && isset($ticket) && $ticket ? 'disabled' : 'required' }}>
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

        <div class="col-12 col-md-{{ (Auth::user()->role === 'user' && (!isset($ticket) || !$ticket)) ? '6' : '3' }} mb-3">
          <label for="priority" class="form-label">Priorita <span class="text-danger">*</span></label>
          <select class="form-control @error('priority') is-invalid @enderror" 
            id="priority" 
            name="priority" 
            {{ Auth::user()->role === 'admin' && isset($ticket) && $ticket ? 'disabled' : 'required' }}>
            <option value="">Vyberte prioritu</option>
            <option value="low" {{ old('priority', isset($ticket) && $ticket ? $ticket->priority : '') == 'low' ? 'selected' : '' }}>Nízka</option>
            <option value="medium" {{ old('priority', isset($ticket) && $ticket ? $ticket->priority : '') == 'medium' ? 'selected' : '' }}>Stredná</option>
            <option value="high" {{ old('priority', isset($ticket) && $ticket ? $ticket->priority : '') == 'high' ? 'selected' : '' }}>Vysoká</option>
          </select>
          @error('priority')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
        <div class="col-12 col-md-3 mb-3">
          <label for="status" class="form-label">Stav <span class="text-danger">*</span></label>
          <select class="form-control @error('status') is-invalid @enderror" 
            id="status" 
            name="status" 
            required>
            <option value="">Vyberte stav</option>
            <option value="open" {{ old('status', isset($ticket) && $ticket ? $ticket->status : 'open') == 'open' ? 'selected' : '' }}>Otvorené</option>
            <option value="in_progress" {{ old('status', isset($ticket) && $ticket ? $ticket->status : '') == 'in_progress' ? 'selected' : '' }}>V riešení</option>
            <option value="resolved" {{ old('status', isset($ticket) && $ticket ? $ticket->status : '') == 'resolved' ? 'selected' : '' }}>Vyriešené</option>
            <option value="rejected" {{ old('status', isset($ticket) && $ticket ? $ticket->status : '') == 'rejected' ? 'selected' : '' }}>Zamietnuté</option>
          </select>
          @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        @elseif(isset($ticket) && $ticket)
        <div class="col-12 col-md-3 mb-3">
          <label for="status_display" class="form-label">Stav</label>
          <input type="text" class="form-control" id="status_display" 
            value="@if($ticket->status == 'open')Otvorené
              @elseif($ticket->status == 'in_progress')V riešení
              @elseif($ticket->status == 'resolved')Vyriešené
              @elseif($ticket->status == 'rejected')Zamietnuté
              @endif" 
            readonly disabled>
        </div>
        @endif
      </div>

      <div class="d-flex justify-content-between gap-2 mt-4">
        @if(isset($ticket) && $ticket && (Auth::user()->role === 'superadmin' || $ticket->user_id === Auth::id()))
        <div>
          <button type="button" class="btn btn-danger" onclick="if(confirm('Naozaj chcete zmazať tento tiket?')) { document.getElementById('delete-form-{{ $ticket->id }}').submit(); }">
            <i class="bi bi-trash"></i>
            Zmazať tiket
          </button>
        </div>
        @else
        <div></div>
        @endif
        <div class="d-flex gap-2">
          <a href="{{ route('tickets.index') }}" class="light-btn">Zrušiť</a>
          @if(Auth::user()->role === 'admin' && isset($ticket) && $ticket)
          <button type="submit" class="dark-btn">
            Zmeniť stav
          </button>
          @else
          <button type="submit" class="dark-btn">
            @if(isset($ticket) && $ticket)
              Uložiť zmeny
            @else
              Vytvoriť tiket
            @endif
          </button>
          @endif
        </div>
      </div>
    </form>

    @if(isset($ticket) && $ticket)
    <form id="delete-form-{{ $ticket->id }}" action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" style="display: none;">
      @csrf
      @method('DELETE')
    </form>
    @endif
  </div>

  @if(isset($ticket) && $ticket)
  <!-- Comments -->
  <div class="wrapper">
    <h3>Komentáre</h3>
    
    <div id="commentsContainer" class="mb-4">
      @if(isset($ticket) && $ticket && $ticket->comments->isNotEmpty())
        @foreach($ticket->comments as $comment)
          <div class="comment mb-3 p-3 border rounded">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <strong>{{ $comment->user->name }}</strong>
              <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
            <p class="mb-0">{{ $comment->content }}</p>
          </div>
        @endforeach
      @else
        <p class="text-muted" id="noCommentsMessage">Žiadne komentáre.</p>
      @endif
    </div>

    <div id="commentAlert" class="alert alert-success" style="display: none;">
      Komentár bol úspešne pridaný!
    </div>

    <form id="commentForm" method="POST" action="{{ route('comments.store', $ticket->id) }}">
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
          <div id="contentError" class="invalid-feedback" style="display: none;"></div>
        </div>
      </div>
      <div class="d-flex justify-content-end gap-2">
        <button type="submit" class="dark-btn" id="submitCommentBtn">
          Pridať komentár
        </button>
      </div>
    </form>
  </div>
  @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('commentForm');
    
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitCommentBtn');
            const contentTextarea = document.getElementById('content');
            const contentError = document.getElementById('contentError');
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Pridávam...';
            
            contentTextarea.classList.remove('is-invalid');
            contentError.style.display = 'none';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    contentTextarea.value = '';
                    
                    const alert = document.getElementById('commentAlert');
                    alert.style.display = 'block';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 3000);
                    
                    const noCommentsMsg = document.getElementById('noCommentsMessage');
                    if (noCommentsMsg) {
                        noCommentsMsg.remove();
                    }
                    
                    const commentsContainer = document.getElementById('commentsContainer');
                    const commentHtml = `
                        <div class="comment mb-3 p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <strong>${data.comment.user.name}</strong>
                                <small class="text-muted">Práve teraz</small>
                            </div>
                            <p class="mb-0">${escapeHtml(data.comment.content)}</p>
                        </div>
                    `;
                    commentsContainer.insertAdjacentHTML('beforeend', commentHtml);
                    
                    commentsContainer.lastElementChild.scrollIntoView({ behavior: 'smooth' });
                }
            })
            .catch(error => {
                console.error('Error adding comment:', error);
                contentTextarea.classList.add('is-invalid');
                contentError.textContent = 'Nastala chyba pri pridávaní komentára.';
                contentError.style.display = 'block';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Pridať komentár';
            });
        });
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const descriptionField = document.getElementById('description');
    
    if (descriptionField && !descriptionField.hasAttribute('disabled')) {
        tinymce.init({
            selector: '#description',
            height: 400,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'charmap',
                'searchreplace', 'visualblocks', 'code',
                'insertdatetime', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
                'bold italic underline strikethrough | forecolor backcolor | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | ' +
                'link | removeformat | code | help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; padding: 10px; }',
            promotion: false,
            branding: false,

            extended_valid_elements: 'p[style],span[style],div[style],strong[style],em[style],u[style],strike[style],s[style],h1[style],h2[style],h3[style],h4[style],h5[style],h6[style],a[href|target|style],ul[style],ol[style],li[style],blockquote[style],code[style],pre[style],br,img[src|alt|width|height|style]',
            valid_styles: {
                '*': 'color,background-color,text-align,font-size,font-weight,font-family,font-style,text-decoration,padding,margin,border,line-height,width,height'
            },
            formats: {
                alignleft: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', styles: { textAlign: 'left' } },
                aligncenter: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', styles: { textAlign: 'center' } },
                alignright: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', styles: { textAlign: 'right' } },
                alignjustify: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', styles: { textAlign: 'justify' } },
            },
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    }
});
</script>
@endpush

@endsection
