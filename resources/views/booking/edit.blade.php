<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Detail Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('booking.update', $booking) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-6 mb-6 md:grid-cols-2">
                        <div>
                            <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Penanggung Jawab</label>
                            <input type="text" id="nama" name="nama" value="{{ $booking->nama }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                        </div>
                        <div>
                            <label for="acara" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Acara</label>
                            <input type="text" id="acara" name="acara" value="{{ $booking->acara }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                        </div>
                        <div>
                            <label for="id_rooms" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Ruang</label>
                            <select id="id_rooms" name="id_rooms" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                <option value="1" {{ $booking->nama_rooms == 'Ruang Pangripta' ? 'selected' : '' }}>Ruang Pangripta</option>
                                <option value="2" {{ $booking->nama_rooms == 'Ruang Aula' ? 'selected' : '' }}>Ruang Aula</option>
                                <option value="3" {{ $booking->nama_rooms == 'Ruang Tengah' ? 'selected' : '' }}>Ruang Tengah</option>
                                <option value="4" {{ $booking->nama_rooms == 'Ruang Krenova' ? 'selected' : '' }}>Ruang Krenova</option>
                                <option value="5" {{ $booking->nama_rooms == 'Ruang Inovasi' ? 'selected' : '' }}>Ruang Inovasi</option>
                            </select>
                        </div>                        
                        <div>
                            <label for="asalbidang" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Asal Bidang</label>
                            <select id="asalbidang" name="asalbidang" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                <option value="SEKRETARIAT" {{ $booking->asalbidang == 'SEKRETARIAT' ? 'selected' : '' }}>SEKRETARIAT</option>
                                <option value="RENDALEV" {{ $booking->asalbidang == 'RENDALEV' ? 'selected' : '' }}>RENDALEV</option>
                                <option value="IKA" {{ $booking->asalbidang == 'IKA' ? 'selected' : '' }}>IKA</option>
                                <option value="PPM" {{ $booking->asalbidang == 'PPM' ? 'selected' : '' }}>PPM</option>
                                <option value="PSDA" {{ $booking->asalbidang == 'PSDA' ? 'selected' : '' }}>PSDA</option>
                                <option value="LITBANG" {{ $booking->asalbidang == 'LITBANG' ? 'selected' : '' }}>LITBANG</option>
                                <option value="LAINNYA" {{ $booking->asalbidang == 'LAINNYA' ? 'selected' : '' }}>LAINYA</option>
                            </select>
                        </div>
                        <div>
                            <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                            <input type="date" id="date" name="date" value="{{ $booking->date }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                        </div>
                        <div>
                            <label for="start" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Waktu Mulai</label>
                            <input type="time" id="start" name="start" value="{{ $booking->start }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                        </div>
                        <div>
                            <label for="finish" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Waktu Selesai</label>
                            <input type="time" id="finish" name="finish" value="{{ $booking->finish }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                        </div>
                        <div>
                            <label for="jumlah_peserta" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah Peserta</label>
                            <input type="number" id="peserta" name="peserta" value="{{ $booking->peserta }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Jumlah Peserta" required />
                        </div>
                        <input type="hidden" id="nama_rooms" name="nama_rooms" value="{{ $booking->nama_rooms }}" />
                    </div>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.getElementById('id_rooms').addEventListener('change', function() {
            const roomNames = {
                "1": "Ruang Pangripta",
                "2": "Ruang Aula",
                "3": "Ruang Tengah"
            };
            document.getElementById('nama_rooms').value = roomNames[this.value];
        });

        // Set initial value based on the selected room
        const initialRoomId = document.getElementById('id_rooms').value;
        const roomNames = {
            "1": "Ruang Pangripta",
            "2": "Ruang Aula",
            "3": "Ruang Tengah"
        };
        document.getElementById('nama_rooms').value = roomNames[initialRoomId];

        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>
</x-app-layout>
