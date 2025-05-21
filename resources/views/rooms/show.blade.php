<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Ruangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white dark:bg-gray-800">
                <img src='{{ asset($room->image) }}' alt="{{ $room->name }}" class="mt-4 rounded-lg">
                <h1 class="mt-2 text-3xl font-semibold text-gray-600 dark:text-gray-400">{{ $room->nama_ruang }}</h1>
                <p class="mt-2 text-sm text-whie dark:text-gray-400">{{ $room->deskripsi }}</p>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $room->status }}</p>
                <a href="{{ route('booking.create', ['room_id' => $room->id]) }}" class="mt-4 inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Book Now
                    <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
