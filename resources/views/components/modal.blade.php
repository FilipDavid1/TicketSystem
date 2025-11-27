@props([
    'name',
    'show' => false,
    'maxWidth' => 'lg'
])

@php
$modalSize = [
    'sm' => 'modal-sm',
    'md' => '',
    'lg' => 'modal-lg',
    'xl' => 'modal-xl',
    '2xl' => 'modal-xl',
][$maxWidth] ?? '';
@endphp

<div
    class="modal fade"
    id="modal-{{ $name }}"
    tabindex="-1"
    aria-labelledby="modal-{{ $name }}-label"
    aria-hidden="true"
    x-data="{ 
        show: @js($show),
        modal: null
    }"
    x-init="
        modal = new bootstrap.Modal($el);
        $watch('show', value => {
            if (value) {
                setTimeout(() => modal.show(), 100);
            } else {
                modal.hide();
            }
        });
        $el.addEventListener('hidden.bs.modal', () => { show = false; });
        if (show) { 
            setTimeout(() => modal.show(), 100);
        }
    "
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? (show = true, modal.show()) : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? (show = false, modal.hide()) : null"
>
    <div class="modal-dialog {{ $modalSize }}">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>
