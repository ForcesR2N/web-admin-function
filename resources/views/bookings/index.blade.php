<x-app-layout>
    <div class="min-h-screen bg-gray-900 text-white">
        <!-- Header -->
        <div class="px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold text-white">Booking Requests</h1>
            <button onclick="window.location.href='{{ route('bookings.index', ['refresh' => time()]) }}'" class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-md flex items-center space-x-2 transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>REFRESH</span>
            </button>
        </div>

        <div class="px-6 py-4">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Pending Requests Card -->
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center mr-8">
                            <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-1">Pending Requests</p>
                            <p class="text-3xl font-bold text-white">{{ $stats['pending'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Today's Pending Requests Card -->
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-8">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-1">Today's Requests</p>
                            <p class="text-3xl font-bold text-white">
                                @php
                                    $todayPendingCount = 0;
                                    if(isset($stats['today_pending'])) {
                                        $todayPendingCount = $stats['today_pending'];
                                    } elseif(isset($bookings)) {
                                        $today = date('Y-m-d');
                                        $todayPendingCount = count(array_filter($bookings, function($booking) use ($today) {
                                            return !$booking['is_confirmed'] && isset($booking['date']) && date('Y-m-d', strtotime($booking['date'])) == $today;
                                        }));
                                    }
                                @endphp
                                {{ $todayPendingCount }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Oldest Pending Request Card -->
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-8">
                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V5z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-1">Oldest Request</p>
                            <p class="text-xl font-bold text-white">
                                @php
                                    $oldestDate = "No pending";
                                    if(isset($bookings) && count($bookings) > 0) {
                                        $pendingBookings = array_filter($bookings, function($booking) {
                                            return !$booking['is_confirmed'];
                                        });

                                        if(count($pendingBookings) > 0) {
                                            usort($pendingBookings, function($a, $b) {
                                                return strtotime($a['date']) - strtotime($b['date']);
                                            });
                                            $oldestBooking = reset($pendingBookings);
                                            $oldestDate = isset($oldestBooking['formatted_date']) ? $oldestBooking['formatted_date'] : date('d M Y', strtotime($oldestBooking['date']));
                                        }
                                    }
                                @endphp
                                {{ $oldestDate }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-gray-800 rounded-lg p-6 mb-6">
                <div class="flex flex-col space-y-6 md:flex-row md:space-y-0 md:space-x-4 md:items-center justify-between">
                    <!-- Status Filter Tabs - Only show pending/today filters for this page -->
                    <div class="flex space-x-3">
                        <a href="{{ route('bookings.index') }}"
                           class="{{ !request('filter') && !request('today') ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }} px-6 py-2 rounded-md transition-colors duration-200">
                            All Pending
                        </a>
                        <a href="{{ route('bookings.index', ['filter' => 'today']) }}"
                           class="{{ request('filter') == 'today' ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }} px-6 py-2 rounded-md transition-colors duration-200">
                            Today's Pending
                        </a>
                        <a href="{{ route('dashboard') }}"
                           class="bg-gray-700 text-gray-300 hover:bg-gray-600 px-6 py-2 rounded-md transition-colors duration-200">
                            View Dashboard
                        </a>
                    </div>

                    {{-- <!-- Search Box -->
                    <div class="relative w-full md:w-64">
                        <form action="{{ route('bookings.index') }}" method="GET">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="w-full bg-gray-700 border-gray-600 rounded-md text-white pl-12 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="Search pending requests...">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                        </form>
                    </div>
                </div>
            </div> --}}

            <!-- Bookings Table - Only Showing PENDING Requests -->
            <div class="bg-gray-800 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-semibold text-white">Pending Approval Requests</h3>
                </div>

                @php
                    // Filter to only show pending bookings
                    $pendingBookings = isset($bookings) ? array_filter($bookings, function($booking) {
                        // Only include bookings that are not confirmed
                        if(isset($booking['is_confirmed']) && $booking['is_confirmed'] === false) {
                            // If today filter is applied, check date
                            if(request('filter') == 'today') {
                                $bookingDate = isset($booking['date']) ? date('Y-m-d', strtotime($booking['date'])) : '';
                                return $bookingDate == date('Y-m-d');
                            }
                            return true;
                        }
                        return false;
                    }) : [];
                @endphp

                @if(count($pendingBookings) > 0)
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
                                @foreach($pendingBookings as $booking)
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
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
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
                        <h3 class="mt-4 text-lg font-medium text-white">No pending booking requests</h3>
                        <p class="mt-2 text-sm text-gray-400">
                            @if(isset($error))
                                There was a problem connecting to the API. Please check your connection and try again.
                            @else
                                Great job! All booking requests have been processed. Check the dashboard to see confirmed bookings.
                            @endif
                        </p>
                        <div class="mt-6 flex justify-center space-x-4">
                            <a href="{{ route('bookings.index', ['refresh' => time()]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Refresh
                            </a>

                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Go to Dashboard
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
