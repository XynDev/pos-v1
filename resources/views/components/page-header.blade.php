@props(['title' => 'Default Title'])

<div class="sm:flex sm:justify-between sm:items-center mb-8">
    {{-- Judul Halaman --}}
    <div class="mb-4 sm:mb-0">
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">
            {{-- Slot default ini akan menampilkan judul halaman --}}
            {{ $slot }}
        </h1>
    </div>

    {{-- Tombol Aksi (Opsional) --}}
    @if (isset($actions))
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            {{-- Slot bernama 'actions' ini untuk menampung tombol atau input --}}
            {{ $actions }}
        </div>
    @endif
</div>
