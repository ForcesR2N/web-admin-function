<x-app-layout>
    <div class="min-h-screen bg-gray-900 text-white">
        <!-- Header -->
        <div class="px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold text-white">Dashboard</h1>
            <button onclick="window.location.href='{{ route('dashboard', ['refresh' => time()]) }}'" class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-md flex items-center space-x-2 transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>REFRESH</span>
            </button>
        </div>

        <div class="px-6 py-4">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Requests Card -->
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center mr-8">
                            <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-1">Total Requests</p>
                            <p class="text-3xl font-bold text-white">{{ $stats['total'] ?? 3 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Requests Card -->
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center mr-8">
                            <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-1">Pending</p>
                            <p class="text-3xl font-bold text-white">{{ $stats['pending'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Confirmed Bookings Card -->
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-8">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-1">Confirmed</p>
                            <p class="text-3xl font-bold text-white">{{ $stats['confirmed'] ?? 3 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Alert -->
            @php
                $pendingCount = isset($stats['pending']) ? $stats['pending'] : (
                    isset($bookings) ? count(array_filter($bookings, function($booking) {
                        return isset($booking['is_confirmed']) && $booking['is_confirmed'] === false;
                    })) : 0
                );
            @endphp

            @if($pendingCount > 0)
                <div class="bg-yellow-900 border-l-4 border-yellow-500 p-4 mb-6 rounded-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-300">
                                There {{ $pendingCount === 1 ? 'is' : 'are' }} <span class="font-bold">{{ $pendingCount }}</span> pending booking {{ $pendingCount === 1 ? 'request' : 'requests' }} that {{ $pendingCount === 1 ? 'needs' : 'need' }} your attention.
                                <a href="{{ route('bookings.index') }}" class="font-bold underline hover:text-yellow-200 ml-1">
                                    View pending requests
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Filters and Search -->
            <div class="bg-gray-800 rounded-lg p-6 mb-6">
                <div class="flex flex-col space-y-6 md:flex-row md:space-y-0 md:space-x-4 md:items-center justify-between">
                    <!-- Status Filter Tabs -->
                    <div class="flex space-x-3">
                        <a href="{{ route('dashboard') }}"
                           class="{{ !request('filter') ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }} px-6 py-2 rounded-md transition-colors duration-200">
                            All
                        </a>
                        <a href="{{ route('dashboard', ['filter' => 'confirmed']) }}"
                           class="{{ request('filter') == 'confirmed' ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }} px-6 py-2 rounded-md transition-colors duration-200">
                            Confirmed
                        </a>
                        <a href="{{ route('dashboard', ['filter' => 'today']) }}"
                           class="{{ request('filter') == 'today' ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }} px-6 py-2 rounded-md transition-colors duration-200">
                            Today
                        </a>
                        <a href="{{ route('bookings.index') }}"
                           class="bg-gray-700 text-gray-300 hover:bg-gray-600 px-6 py-2 rounded-md transition-colors duration-200">
                            Pending Requests
                        </a>
                    </div>

                    <!-- Search Box -->
                    {{-- <div class="relative w-full md:w-64">
                        <form action="{{ route('dashboard') }}" method="GET">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="w-full bg-gray-700 border-gray-600 rounded-md text-white pl-12 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="Search bookings...">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                        </form>
                    </div> --}}
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="bg-gray-800 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-semibold text-white">All Bookings</h3>
                </div>

                @php
                    // Filter bookings based on requested filter
                    $filteredBookings = isset($bookings) ? array_filter($bookings, function($booking) {
                        // When showing confirmed only
                        if(request('filter') == 'confirmed') {
                            return isset($booking['is_confirmed']) && $booking['is_confirmed'] === true;
                        }
                        // When showing today's bookings
                        else if(request('filter') == 'today') {
                            $bookingDate = isset($booking['date']) ? date('Y-m-d', strtotime($booking['date'])) : '';
                            return $bookingDate == date('Y-m-d');
                        }
                        // Show all bookings by default
                        return true;
                    }) : [];

                    // Sort bookings by date (newest first)
                    if (count($filteredBookings) > 0) {
                        usort($filteredBookings, function($a, $b) {
                            $dateA = isset($a['date']) ? strtotime($a['date']) : 0;
                            $dateB = isset($b['date']) ? strtotime($b['date']) : 0;
                            return $dateB - $dateA; // Descending order
                        });
                    }
                @endphp

                @if(count($filteredBookings) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-900">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Place</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Guest</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Time</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700">
                                @foreach($filteredBookings as $booking)
                                    <tr class="hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-200">
                                            #{{ $booking['id'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-white">
                                                {{ $booking['place']['name'] ?? 'Kudos Cafe' }}
                                            </div>
                                            @if(isset($booking['place']['address']))
                                                <div class="text-xs text-gray-400 truncate max-w-xs">
                                                    {{ $booking['place']['address'] }}
                                                </div>
                                            @else
                                                <div class="text-xs text-gray-400 truncate max-w-xs">
                                                    Pakuwon Square AK 2 No. 3, Jl. Puncak Indah
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-white">
                                                {{ $booking['guest_name'] ?? 'admin' }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $booking['guest_email'] ?? 'admin@gmail.com' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $booking['formatted_date'] ?? date('d M Y', strtotime('+' . ($loop->index) . ' days')) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-xs text-gray-400">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $booking['formatted_start_time'] ?? '19:50' }}
                                                </div>
                                                <div class="flex items-center mt-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $booking['formatted_end_time'] ?? '21:50' }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ isset($booking['is_confirmed']) && $booking['is_confirmed'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ isset($booking['is_confirmed']) && $booking['is_confirmed'] ? 'Confirmed' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('bookings.show', $booking['id']) }}" class="text-indigo-400 hover:text-indigo-300 inline-flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View
                                            </a>

                                            @if(!isset($booking['is_confirmed']) || !$booking['is_confirmed'])
                                                <div class="mt-3">
                                                    <form action="{{ route('bookings.approve', $booking['id']) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="text-green-400 hover:text-green-300 inline-flex items-center"
                                                                onclick="return confirm('Are you sure you want to confirm this booking?')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Approve
                                                        </button>
                                                    </form>
                                                </div>

                                                <form action="{{ route('bookings.reject', $booking['id']) }}" method="POST">
    @csrf
    @method('DELETE')  <!-- Ubah ke DELETE method -->
    <button type="submit"
            class="bg-red-500 hover:bg-red-600 text-white font-medium py-1 px-3 rounded text-xs"
            onclick="return confirm('Are you sure you want to delete this booking?')">
        ✗ Cancel
    </button>
</form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-white">No bookings found</h3>
                        <p class="mt-2 text-sm text-gray-400">
                            @if(isset($error))
                                There was a problem connecting to the API. Please check your connection and try again.
                            @elseif(request('filter') == 'confirmed')
                                No confirmed bookings yet. Check the pending requests to approve new bookings.
                            @elseif(request('filter') == 'today')
                                No bookings scheduled for today.
                            @else
                                Your booking list is empty. Bookings made through the mobile app will appear here.
                            @endif
                        </p>
                        <div class="mt-6 flex justify-center space-x-4">
                            <a href="{{ route('dashboard', ['refresh' => time()]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Refresh
                            </a>

                            @if(request('filter') == 'confirmed')
                                <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    View Pending Requests
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Recent Activity Section -->
            <div class="mt-6 bg-gray-800 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-semibold text-white">Recent Activity</h3>
                </div>
                <div class="p-6">
                    <ul class="space-y-4">
                        <!-- Activity items - These would normally come from a database -->
                        <li class="flex items-start">
                            <div class="flex-shrink-0 bg-green-100 rounded-full p-1">
                                <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-white">Booking <span class="font-medium">#5</span> for <span class="font-medium">Hotel Bumi Surabaya</span> was confirmed</p>
                                <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 bg-yellow-100 rounded-full p-1">
                                <svg class="h-5 w-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-white">New booking request <span class="font-medium">#6</span> from <span class="font-medium">admin@gmail.com</span></p>
                                <p class="text-xs text-gray-400 mt-1">4 hours ago</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 bg-green-100 rounded-full p-1">
                                <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-white">Booking <span class="font-medium">#4</span> for <span class="font-medium">Kudos Cafe</span> was confirmed</p>
                                <p class="text-xs text-gray-400 mt-1">Yesterday at 16:43</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 bg-red-100 rounded-full p-1">
                                <svg class="h-5 w-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-white">Booking <span class="font-medium">#3</span> for <span class="font-medium">Garden Resto</span> was rejected</p>
                                <p class="text-xs text-gray-400 mt-1">2 days ago</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
