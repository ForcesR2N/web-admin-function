{{-- resources/views/bookings/index.blade.php --}}
{{-- View untuk Mobile App Booking Requests --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mobile App Booking Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Stats Cards -->
                    @if(isset($stats))
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-blue-500">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-10 text-blue-500 mr-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Requests</p>
                                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['total'] ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10 text-yellow-500 mr-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
                                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['pending'] ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-green-500">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-green-500 bg-opacity-10 text-green-500 mr-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Confirmed</p>
                                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['confirmed'] ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Bookings Table -->
                    @if(isset($bookings) && $bookings->count() > 0)
                        <div class="overflow-x-auto relative">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6">ID</th>
                                        <th scope="col" class="py-3 px-6">Place</th>
                                        <th scope="col" class="py-3 px-6">User</th>
                                        <th scope="col" class="py-3 px-6">Date</th>
                                        <th scope="col" class="py-3 px-6">Time</th>
                                        <th scope="col" class="py-3 px-6">Status</th>
                                        <th scope="col" class="py-3 px-6">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="py-4 px-6 font-medium">#{{ $booking->id }}</td>
                                            <td class="py-4 px-6">
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    {{ $booking->place->name ?? 'Place #' . $booking->place_id }}
                                                </div>
                                                <div class="text-xs text-gray-500">ID: {{ $booking->place_id }}</div>
                                            </td>
                                            <td class="py-4 px-6">
                                                <div class="font-medium">{{ $booking->user->name ?? 'User #' . $booking->user_id }}</div>
                                                <div class="text-xs text-gray-500">ID: {{ $booking->user_id }}</div>
                                            </td>
                                            <td class="py-4 px-6">
                                                {{ $booking->date ? $booking->date->format('d M Y') : 'N/A' }}
                                            </td>
                                            <td class="py-4 px-6">
                                                <div class="text-xs">
                                                    <p><span class="font-semibold">Start:</span> {{ $booking->formatted_start_time ?? $booking->start_time }}</p>
                                                    <p><span class="font-semibold">End:</span> {{ $booking->formatted_end_time ?? $booking->end_time }}</p>
                                                </div>
                                            </td>
                                            <td class="py-4 px-6">
                                                @if(isset($booking->status_badge))
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $booking->status_badge['class'] }}">
                                                        {{ $booking->status_badge['text'] }}
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $booking->is_confirmed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ $booking->is_confirmed ? 'Confirmed' : 'Pending' }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6">
                                                @if(!$booking->is_confirmed)
                                                    <div class="flex space-x-2">
                                                        <!-- Confirm Button -->
                                                        <form action="{{ route('bookings.approve', $booking) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit"
                                                                    class="bg-green-500 hover:bg-green-600 text-white font-medium py-1 px-3 rounded text-xs"
                                                                    onclick="return confirm('Are you sure you want to confirm this booking?')">
                                                                ✓ Confirm
                                                            </button>
                                                        </form>

                                                        <!-- Cancel Button -->
                                                        <form action="{{ route('bookings.reject', $booking) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit"
                                                                    class="bg-red-500 hover:bg-red-600 text-white font-medium py-1 px-3 rounded text-xs"
                                                                    onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                                ✗ Cancel
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="text-xs text-gray-500">
                                                        <p class="font-medium">Confirmed</p>
                                                        <p>{{ $booking->updated_at ? $booking->updated_at->format('d M Y, H:i') : 'N/A' }}</p>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No booking requests found</h3>
                            <p class="text-gray-500 dark:text-gray-400">Mobile app booking requests will appear here.</p>

                            <!-- Debug info -->
                            <div class="mt-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg text-left">
                                <h4 class="font-medium mb-2">Debug Information:</h4>
                                <p class="text-sm">Bookings variable: {{ isset($bookings) ? 'Set' : 'Not set' }}</p>
                                @if(isset($bookings))
                                    <p class="text-sm">Bookings count: {{ $bookings->count() }}</p>
                                    <p class="text-sm">Bookings type: {{ get_class($bookings) }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
