<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Booking Request Details') }}
            </h2>
            <a href="{{ route('bookings.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded inline-flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                Booking #{{ $booking['id'] }}
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400">
                                Created on {{ $booking->created_at->format('M d, Y \a\t h:i A') }}
                            </p>
                        </div>
                        <div>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $booking->status_badge['class'] }}">
                                {{ $booking->status_badge['text'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Main booking information grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Venue Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white border-b pb-2">
                                Venue Information
                            </h4>
                            <div class="space-y-2">
                                <p class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Venue:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->place->name ?? 'Unknown Venue' }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Address:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->place->address ?? 'No address' }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Venue ID:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->place_id }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Capacity:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->capacity ?? 'Not specified' }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Guest Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white border-b pb-2">
                                Guest Information
                            </h4>
                            <div class="space-y-2">
                                <p class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Name:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->guest_name ?? 'Unknown' }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Email:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->guest_email ?? 'Not provided' }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Contact Info:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $booking->mobile_info['contact_info'] ?? 'Not provided' }}
                                    </span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">User ID:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->user_id }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Booking Schedule -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white border-b pb-2">
                                Booking Schedule
                            </h4>
                            <div class="space-y-2">
                                <p class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Date:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->formatted_date }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Start Time:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->formatted_start_time }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">End Time:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->formatted_end_time }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Duration:</span>
                                    @php
                                        $start = \Carbon\Carbon::parse($booking->start_time);
                                        $end = \Carbon\Carbon::parse($booking->end_time);
                                        $duration = $start->diffInMinutes($end);
                                        $hours = floor($duration / 60);
                                        $minutes = $duration % 60;
                                    @endphp
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        @if($hours > 0)
                                            {{ $hours }} hour{{ $hours > 1 ? 's' : '' }}
                                        @endif
                                        @if($minutes > 0)
                                            {{ $minutes }} minute{{ $minutes > 1 ? 's' : '' }}
                                        @endif
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white border-b pb-2">
                                Additional Notes
                            </h4>
                            <div class="space-y-2">
                                <p class="text-gray-900 dark:text-white">
                                    @if($booking->notes)
                                        {{ $booking->notes }}
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400 italic">No additional notes provided</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-4 border-t">
                        @if(!$booking->is_confirmed)
                            <form action="{{ route('bookings.approve', $booking) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                        class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded"
                                        onclick="return confirm('Are you sure you want to confirm this booking?')">
                                    Confirm Booking
                                </button>
                            </form>

                            <form action="{{ route('bookings.reject', $booking) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded"
                                        onclick="return confirm('Are you sure you want to cancel this booking?')">
                                    Cancel Booking
                                </button>
                            </form>
                        @else
                            <div class="bg-green-100 text-green-800 py-2 px-4 rounded flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                This booking has been confirmed
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
