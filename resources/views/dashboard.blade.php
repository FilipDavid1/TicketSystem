<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 fw-semibold text-dark mb-0">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container px-3 px-md-4 px-lg-5">
            @include('tickets.index', ['tickets' => $tickets])
        </div>
    </div>
</x-app-layout>