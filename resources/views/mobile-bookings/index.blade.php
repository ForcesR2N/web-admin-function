<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mobile App Booking Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-blue-500">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-500 bg-opacity-10 text-blue-500 mr-4">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Requests</p>
                                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ App\Models\MobileBooking::count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10 text-yellow-500 mr-4">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
                                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ App\Models\MobileBooking::where('status', 'pending')->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-green-500">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-500 bg-opacity-10 text-green-500 mr-4">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Approved</p>
                                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ App\Models\MobileBooking::where('status', 'approved')->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-red-500">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-red-500 bg-opacity-10 text-red-500 mr-4">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Rejected</p>
                                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ App\Models\MobileBooking::where('status', 'rejected')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="bookingTabs" role="tablist">
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg border-blue-600 text-blue-600 active" id="all-tab" data-tabs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">All Requests</button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="pending-tab" data-tabs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false">Pending</button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="approved-tab" data-tabs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="false">Approved</button>
                            </li>
                            <li role="presentation">
                                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="rejected-tab" data-tabs-target="#rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false">Rejected</button>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab content -->
                    <div id="bookingTabContent">
                        <!-- All Bookings Tab -->
                        <div class="block p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="all" role="tabpanel" aria-labelledby="all-tab">
                            <div class="overflow-x-auto relative">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="py-3 px-6">ID</th>
                                            <th scope="col" class="py-3 px-6">Venue</th>
                                            <th scope="col" class="py-3 px-6">User</th>
                                            <th scope="col" class="py-3 px-6">Dates</th>
                                            <th scope="col" class="py-3 px-6">Capacity</th>
                                            <th scope="col" class="py-3 px-6">Status</th>
                                            <th scope="col" class="py-3 px-6">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings as $booking)
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <td class="py-4 px-6">#{{ $booking->id }}</td>
                                                <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">{{ $booking->venue_name }}</td>
                                                <td class="py-4 px-6">{{ $booking->user_name }}</td>
                                                <td class="py-4 px-6">
                                                    <div class="text-xs">
                                                        <p><span class="font-semibold">Start:</span> {{ $booking->start_date->format('d M Y, H:i') }}</p>
                                                        <p><span class="font-semibold">End:</span> {{ $booking->end_date->format('d M Y, H:i') }}</p>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6">{{ $booking->capacity }} people</td>
                                                <td class="py-4 px-6">
                                                    @if($booking->status == 'pending')
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                                    @elseif($booking->status == 'approved')
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                                    @else
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-6">
                                                    @if($booking->status == 'pending')
                                                        <div class="flex space-x-2">
                                                            <form action="{{ route('mobile-bookings.approve', $booking) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded text-xs">
                                                                    Approve
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('mobile-bookings.reject', $booking) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-xs">
                                                                    Reject
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-500 italic text-xs">Processed on {{ $booking->processed_at->format('d M Y, H:i') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pending Tab -->
                        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                            <div class="overflow-x-auto relative">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="py-3 px-6">ID</th>
                                            <th scope="col" class="py-3 px-6">Venue</th>
                                            <th scope="col" class="py-3 px-6">User</th>
                                            <th scope="col" class="py-3 px-6">Dates</th>
                                            <th scope="col" class="py-3 px-6">Capacity</th>
                                            <th scope="col" class="py-3 px-6">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings->where('status', 'pending') as $booking)
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <td class="py-4 px-6">#{{ $booking->id }}</td>
                                                <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">{{ $booking->venue_name }}</td>
                                                <td class="py-4 px-6">{{ $booking->user_name }}</td>
                                                <td class="py-4 px-6">
                                                    <div class="text-xs">
                                                        <p><span class="font-semibold">Start:</span> {{ $booking->start_date->format('d M Y, H:i') }}</p>
                                                        <p><span class="font-semibold">End:</span> {{ $booking->end_date->format('d M Y, H:i') }}</p>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6">{{ $booking->capacity }} people</td>
                                                <td class="py-4 px-6">
                                                    <div class="flex space-x-2">
                                                        <form action="{{ route('mobile-bookings.approve', $booking) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded text-xs">
                                                                Approve
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('mobile-bookings.reject', $booking) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-xs">
                                                                Reject
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Approved Tab -->
                        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                            <div class="overflow-x-auto relative">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="py-3 px-6">ID</th>
                                            <th scope="col" class="py-3 px-6">Venue</th>
                                            <th scope="col" class="py-3 px-6">User</th>
                                            <th scope="col" class="py-3 px-6">Dates</th>
                                            <th scope="col" class="py-3 px-6">Capacity</th>
                                            <th scope="col" class="py-3 px-6">Approved On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings->where('status', 'approved') as $booking)
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <td class="py-4 px-6">#{{ $booking->id }}</td>
                                                <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">{{ $booking->venue_name }}</td>
                                                <td class="py-4 px-6">{{ $booking->user_name }}</td>
                                                <td class="py-4 px-6">
                                                    <div class="text-xs">
                                                        <p><span class="font-semibold">Start:</span> {{ $booking->start_date->format('d M Y, H:i') }}</p>
                                                        <p><span class="font-semibold">End:</span> {{ $booking->end_date->format('d M Y, H:i') }}</p>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6">{{ $booking->capacity }} people</td>
                                                <td class="py-4 px-6">{{ $booking->processed_at->format('d M Y, H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Rejected Tab -->
                        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                            <div class="overflow-x-auto relative">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="py-3 px-6">ID</th>
                                            <th scope="col" class="py-3 px-6">Venue</th>
                                            <th scope="col" class="py-3 px-6">User</th>
                                            <th scope="col" class="py-3 px-6">Dates</th>
                                            <th scope="col" class="py-3 px-6">Capacity</th>
                                            <th scope="col" class="py-3 px-6">Rejected On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings->where('status', 'rejected') as $booking)
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <td class="py-4 px-6">#{{ $booking->id }}</td>
                                                <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">{{ $booking->venue_name }}</td>
                                                <td class="py-4 px-6">{{ $booking->user_name }}</td>
                                                <td class="py-4 px-6">
                                                    <div class="text-xs">
                                                        <p><span class="font-semibold">Start:</span> {{ $booking->start_date->format('d M Y, H:i') }}</p>
                                                        <p><span class="font-semibold">End:</span> {{ $booking->end_date->format('d M Y, H:i') }}</p>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6">{{ $booking->capacity }} people</td>
                                                <td class="py-4 px-6">{{ $booking->processed_at->format('d M Y, H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Tab Functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('[role="tab"]');
            const tabContents = document.querySelectorAll('[role="tabpanel"]');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Deactivate all tabs
                    tabs.forEach(t => {
                        t.classList.remove('border-blue-600', 'text-blue-600', 'active');
                        t.classList.add('border-transparent');
                        t.setAttribute('aria-selected', 'false');
                    });

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Activate the clicked tab
                    tab.classList.remove('border-transparent');
                    tab.classList.add('border-blue-600', 'text-blue-600', 'active');
                    tab.setAttribute('aria-selected', 'true');

                    // Show the associated tab content
                    const tabContentId = tab.getAttribute('data-tabs-target').substring(1);
                    document.getElementById(tabContentId).classList.remove('hidden');
                });
            });
        });
    </script>
</x-app-layout>
