@php
    $search = $this->search;
    $selectedStatus = $this->selectedStatus;
@endphp
<div class="text-center py-10 px-4">
    <div class="flex flex-col items-center justify-center text-gray-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-gray-300" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-lg font-bold text-gray-600">Data Tidak Ditemukan</span>
        <span class="text-sm mt-1">Coba cari dengan kata kunci lain atau ubah filter Anda.</span>
        @if ($search || $selectedStatus)
            <button wire:click="resetFilters" class="btn btn-sm btn-outline mt-4">
                <i class="bi bi-arrow-repeat mr-1"></i> Reset Filter
            </button>
        @endif
    </div>
</div>
