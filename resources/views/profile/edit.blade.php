@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div>
                <h2>Môj profil</h2>
                <p class="text-muted mb-0">Spravujte svoje osobné údaje a nastavenia účtu</p>
            </div>
        </div>
    </div>

    @if(session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            Profil bol úspešne aktualizovaný.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            Heslo bolo úspešne zmenené.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Update Profile Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-2">Informácie o profile</h5>
                    <p class="text-muted small mb-4">Aktualizujte svoje osobné údaje a emailovú adresu.</p>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Meno <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name', $user->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                required
                                autofocus
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                value="{{ old('email', $user->email) }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="dark-btn">
                                Uložiť zmeny
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Password -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-2">Zmeniť heslo</h5>
                    <p class="text-muted small mb-4">Uistite sa, že používate dlhé a náhodné heslo pre bezpečnosť vášho účtu.</p>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Súčasné heslo <span class="text-danger">*</span></label>
                            <input 
                                type="password" 
                                name="current_password" 
                                id="current_password" 
                                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                required
                            >
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Nové heslo <span class="text-danger">*</span></label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                required
                            >
                            <small class="form-text text-muted">Minimálne 8 znakov</small>
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Potvrdenie nového hesla <span class="text-danger">*</span></label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                required
                            >
                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="dark-btn">
                                Zmeniť heslo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Account -->
            <div class="card shadow-sm border-danger mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-semibold text-danger mb-2">Zmazať účet</h5>
                    <p class="text-muted small mb-3">
                        Po zmazaní vášho účtu budú všetky jeho údaje a zdroje natrvalo odstránené. Pred zmazaním účtu si stiahnite všetky dáta alebo informácie, ktoré si chcete ponechať.
                    </p>

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="bi bi-trash me-2"></i>
                        Zmazať účet
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Naozaj chcete zmazať svoj účet?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Po zmazaní vášho účtu budú všetky jeho údaje a zdroje natrvalo odstránené. Prosím, zadajte svoje heslo pre potvrdenie, že chcete účet natrvalo zmazať.
                    </p>

                    <div class="mb-3">
                        <label for="delete_password" class="form-label">Heslo <span class="text-danger">*</span></label>
                        <input 
                            type="password" 
                            name="password" 
                            id="delete_password" 
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                            placeholder="Zadajte svoje heslo"
                            required
                        >
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="light-btn" data-bs-dismiss="modal">Zrušiť</button>
                    <button type="submit" class="btn btn-danger">
                        Zmazať účet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
    @push('scripts')
    <script>
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
        deleteModal.show();
    </script>
    @endpush
@endif
@endsection
