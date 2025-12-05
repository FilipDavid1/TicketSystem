@extends('layouts.app')

@section('content')
<div class="container" x-data="categoryManager()">
    <div class="row mb-3">
        <div class="col-12 col-md-6">
            <h2>Správa kategórií</h2>
            <p class="text-muted">Prehľad a správa všetkých kategórií</p>
        </div>
        <div class="col-12 col-md-6">
            <div class="d-flex justify-content-md-end">
                <button type="button"
                    class="btn dark-btn"
                    @click="openCreateModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                    </svg>
                    Pridať kategóriu
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Zavrieť"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Zavrieť"></button>
    </div>
    @endif

    <div class="row">
        @forelse($categories as $category)
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="wrapper h-100 d-flex flex-column">
                <div class="flex-grow-1">
                    <h5 class="mb-3 d-flex justify-content-between align-items-center">
                        {{ $category->name }}
                        <span class="badge badge-tickets">{{ $category->tickets_count }}</span>
                    </h5>

                    @if($category->description)
                    <p class="text-muted mb-3">{{ $category->description }}</p>
                    @else
                    <p class="text-muted mb-3"><em>Bez popisu</em></p>
                    @endif

                    <div class="mb-3">
                        <strong class="d-block mb-2 d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                                <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                            </svg>
                            Priradení administrátori:
                        </strong>
                        @if($category->admins->isNotEmpty())
                        <div class="d-flex flex-row flex-wrap gap-2">
                            @foreach($category->admins as $admin)
                            <span class="badge badge-white">{{ $admin->name }}</span>
                            @endforeach
                        </div>
                        @else
                        <p class="text-muted small mb-0"><em>Žiadni administrátori</em></p>
                        @endif
                    </div>
                </div>

                <div class="d-flex gap-2 mt-auto">
                    <button type="button"
                        class="light-btn flex-fill d-flex align-items-center justify-content-center gap-2"
                        @click="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}', {{ json_encode($category->admins->pluck('id')) }})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        Upraviť
                    </button>
                    <button type="button"
                        class="light-btn"
                        @click="openDeleteModal({{ $category->id }}, '{{ addslashes($category->name) }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#9e0912" class="bi bi-trash3" viewBox="0 0 16 16">
                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="wrapper text-center py-5">
                <p class="text-muted mb-0">Zatiaľ neboli vytvorené žiadne kategórie.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Category Modal -->
    <div class="modal fade"
        id="categoryModal"
        tabindex="-1"
        aria-labelledby="categoryModalLabel"
        aria-hidden="true"
        x-ref="categoryModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel" x-text="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zavrieť"></button>
                </div>
                <form :action="formAction" method="POST" @submit="clearValidationErrors">
                    @csrf
                    <input type="hidden" name="_method" :value="formMethod">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">
                                Názov <span class="text-danger" aria-label="povinné">*</span>
                            </label>
                            <input type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="categoryName"
                                name="name"
                                required
                                maxlength="255"
                                placeholder="Zadajte názov kategórie"
                                x-model="form.name"
                                aria-required="true">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="categoryDescription" class="form-label">Popis</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="categoryDescription"
                                name="description"
                                rows="4"
                                placeholder="Zadajte popis kategórie"
                                x-model="form.description">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Priradiť administrátorov</label>
                            @foreach($admins as $admin)
                            <div class="form-check">
                                <input class="form-check-input @error('admin_ids') is-invalid @enderror"
                                    type="checkbox"
                                    name="admin_ids[]"
                                    value="{{ $admin->id }}"
                                    id="admin_{{ $admin->id }}"
                                    x-model="form.adminIds">
                                <label class="form-check-label" for="admin_{{ $admin->id }}">
                                    {{ $admin->name }} ({{ $admin->email }})
                                </label>
                            </div>
                            @endforeach
                            @error('admin_ids')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="light-btn" data-bs-dismiss="modal">Zrušiť</button>
                        <button type="submit" class="dark-btn">Uložiť</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade"
        id="deleteModal"
        tabindex="-1"
        aria-labelledby="deleteModalLabel"
        aria-hidden="true"
        x-ref="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Potvrdenie vymazania</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zavrieť"></button>
                </div>
                <div class="modal-body">
                    <p>Naozaj chcete vymazať kategóriu <strong x-text="deleteCategory.name"></strong>?</p>
                    <p class="text-danger small mb-0">Táto akcia je nenávratná.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="light-btn" data-bs-dismiss="modal">Zrušiť</button>
                    <form :action="deleteCategory.action" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="background-color: #ffe2e2; border-color: #9e0912; color: #9e0912;">
                            Vymazať
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function categoryManager() {
        return {
            modalTitle: 'Pridať kategóriu',
            formAction: '{{ route("categories.store") }}',
            formMethod: 'POST',

            form: {
                name: '',
                description: '',
                adminIds: []
            },

            deleteCategory: {
                name: '',
                action: ''
            },

            categoryModalInstance: null,
            deleteModalInstance: null,

            init() {
                this.categoryModalInstance = new bootstrap.Modal(this.$refs.categoryModal);
                this.deleteModalInstance = new bootstrap.Modal(this.$refs.deleteModal);

                this.$refs.categoryModal.addEventListener('hidden.bs.modal', () => {
                    this.resetForm();
                });
            },

            openCreateModal() {
                this.resetForm();
                this.modalTitle = 'Pridať kategóriu';
                this.formAction = '{{ route("categories.store") }}';
                this.formMethod = 'POST';
                this.categoryModalInstance.show();
            },

            openEditModal(id, name, description, adminIds) {
                this.resetForm();
                this.modalTitle = 'Upraviť kategóriu';
                this.formAction = '{{ route("categories.update", ":id") }}'.replace(':id', id);
                this.formMethod = 'PUT';
                this.form.name = name;
                this.form.description = description;
                this.form.adminIds = adminIds.map(id => String(id));
                this.categoryModalInstance.show();
            },

            openDeleteModal(id, name) {
                this.deleteCategory.name = name;
                this.deleteCategory.action = '{{ route("categories.destroy", ":id") }}'.replace(':id', id);
                this.deleteModalInstance.show();
            },

            resetForm() {
                this.form = {
                    name: '',
                    description: '',
                    adminIds: []
                };
                this.clearValidationErrors();
            },

            clearValidationErrors() {
                this.$nextTick(() => {
                    document.querySelectorAll('.is-invalid').forEach(el => {
                        el.classList.remove('is-invalid');
                    });
                });
            }
        }
    }
</script>
@endpush
@endsection