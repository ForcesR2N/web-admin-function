<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Jadwal Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex p-1 m-3">
                @if (Auth::user()->role == 'admin')
                <button id="downloadExcel" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Download Excel
                </button>
                <button id="downloadPDF" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Download PDF
                </button>
                @endif
                <a href="{{ route('booking.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Tambah Jadwal
                </a>
            </div>            
            @foreach ($bookings as $room => $roomBookings)
                <div class="p-4 relative overflow-x-auto shadow-md sm:rounded-lg mb-8">
                    <h3 class="uppercase text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ $room }}</h3>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider max-w-acara">
                                    Acara
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    Jumlah Peserta
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    Ruang
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    Penanggung Jawab
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    Pengguna
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    Mulai
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    Selesai
                                </th>
                                @if (Auth::user()->role == 'admin')
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    Action
                                </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($roomBookings->sortBy('date') as $booking)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 max-w-acara">
                                        {{ $booking->acara }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                                        {{ $booking->peserta }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                                        {{ $booking->nama_rooms }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                                        {{ $booking->nama }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                                        {{ $booking->asalbidang }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($booking->date)->format('d-m-Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                                        {{ $booking->start }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                                        {{ $booking->finish }}
                                    </td>
                                    @if (Auth::user()->role == 'admin')
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex">
                                            <a href="{{ route('booking.edit', $booking) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('booking.destroy', $booking) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach                        
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Ensure these scripts are loaded before your custom script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.22/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif

            $('#downloadExcel').on('click', function() {
                var bookings = @json($bookings);
                var wb = XLSX.utils.book_new();

                for (var room in bookings) {
                    if (bookings.hasOwnProperty(room)) {
                        var ws_data = [['Acara', 'Jumlah Peserta', 'Ruang', 'Penanggung Jawab', 'Pengguna', 'Tanggal', 'Mulai', 'Selesai']];

                        bookings[room].forEach(function(booking) {
                            ws_data.push([
                                booking.acara,
                                booking.peserta,
                                booking.nama_rooms,
                                booking.nama,
                                booking.asalbidang,
                                new Date(booking.date).toLocaleDateString('en-GB'),
                                booking.start,
                                booking.finish
                            ]);
                        });

                        var ws = XLSX.utils.aoa_to_sheet(ws_data);
                        XLSX.utils.book_append_sheet(wb, ws, room);
                    }
                }

                XLSX.writeFile(wb, 'bookings.xlsx');
            });

            document.getElementById('downloadPDF').addEventListener('click', function() {
    const { jsPDF } = window.jspdf;

    var doc = new jsPDF();

    var margin = { top: 20, right: 20, bottom: 20, left: 20 };
    var pageWidth = doc.internal.pageSize.width - margin.left - margin.right;
    var pageHeight = doc.internal.pageSize.height - margin.top - margin.bottom;
    var startY = margin.top;

    doc.setFontSize(18);
    doc.text('Jadwal Kegiatan', margin.left, startY);
    startY += 10;

    var bookings = @json($bookings);

    for (var room in bookings) {
        if (bookings.hasOwnProperty(room)) {
            doc.setFontSize(14);
            doc.text(room, margin.left, startY);

            var rows = [];
            bookings[room].forEach(function(booking) {
                rows.push([
                    booking.acara,
                    booking.peserta,
                    booking.nama_rooms,
                    booking.nama,
                    booking.asalbidang,
                    new Date(booking.date).toLocaleDateString('en-GB'),
                    booking.start,
                    booking.finish
                ]);
            });

            doc.autoTable({
                head: [['Acara', 'Jumlah Peserta', 'Ruang', 'Penanggung Jawab', 'Pengguna', 'Tanggal', 'Mulai', 'Selesai']],
                body: rows,
                startY: startY + 10,
                margin: { left: margin.left, right: margin.right },
                tableWidth: pageWidth,
                styles: { overflow: 'linebreak' },
                headStyles: { fillColor: [220, 220, 220] },
                columnStyles: {
                    0: { cellWidth: 'auto', maxCellWidth: 40 },
                    1: { cellWidth: 'auto' },
                    2: { cellWidth: 'auto' },
                    3: { cellWidth: 'auto' },
                    4: { cellWidth: 'auto' },
                    5: { cellWidth: 'auto' },
                    6: { cellWidth: 'auto' },
                    7: { cellWidth: 'auto' },
                },
                didDrawPage: function(data) {
                    var pageCount = doc.internal.getNumberOfPages();
                    doc.setFontSize(10);
                    doc.text('Page ' + String(data.pageNumber) + ' of ' + String(pageCount), margin.left, pageHeight + margin.top + 10);
                }
            });

            startY = doc.autoTable.previous.finalY + 10;

            if (startY > pageHeight) {
                doc.addPage();
                startY = margin.top;
            }
        }
    }

    doc.save('bookings.pdf');
});

        });
    </script>
</x-app-layout>
