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
                    <i class="bi bi-plus"></i>
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
            <div class="wrapper bg-white h-100 d-flex flex-column">
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
                        <i class="bi bi-people"></i>
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
                    @if(Auth::user()->role === 'superadmin' || $category->created_by === Auth::id())
                    <button type="button"
                        class="light-btn flex-fill d-flex align-items-center justify-content-center gap-2"
                        @click="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}', {{ json_encode($category->admins->pluck('id')) }})">
                        <i class="bi bi-pencil-square"></i>
                        Upraviť
                    </button>
                    <button type="button"
                        class="light-btn"
                        @click="openDeleteModal({{ $category->id }}, '{{ addslashes($category->name) }}')">
                        <i class="bi bi-trash3"></i>
                    </button>
                    @else
                    <div class="text-muted small flex-fill text-center py-2">
                        <em>Nemôžete upravovať túto kategóriu</em>
                    </div>
                    @endif
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