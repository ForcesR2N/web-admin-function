<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Jadwal Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('booking.store') }}" method="POST">
                    @csrf
                    <div class="grid gap-6 mb-6 md:grid-cols-2">
                        <div>
                            <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Penanggung Jawab</label>
                            <input type="text" id="nama" name="nama"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Nama" required />
                        </div>
                        <div>
                            <label for="acara" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Acara</label>
                            <input type="text" id="acara" name="acara"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Acara" required />
                        </div>
                        <div>
                            <label for="id_rooms" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Ruang</label>
                            <select id="id_rooms" name="id_rooms"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                                <option value="1">Ruang Pangripta</option>
                                <option value="2">Ruang Aula</option>
                                <option value="3">Ruang Tengah</option>
                                <option value="4">Ruang Krenova</option>                                
                                <option value="5">Ruang Inovasi</option>
                            </select>
                        </div>
                        <div>
                            <label for="asalbidang" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Asal Bidang</label>
                                <select id="asalbidang" name="asalbidang" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    <option value="SEKRETARIAT">SEKRETARIAT</option>
                                    <option value="RENDALEV">RENDALEV</option>
                                    <option value="IKA">IKA</option>
                                    <option value="PPM">PPM</option>
                                    <option value="PSDA">PSDA</option>
                                    <option value="LITBANG">LITBANG</option>
                                    <option value="LAINNYA">LAINYA</option>
                                </select>
                        </div>
                        <div>
                            <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                            <input type="date" id="date" name="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                        </div>
                        <div>
                            <label for="start" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Waktu Mulai</label>
                            <input type="time" id="start" name="start"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                        <div>
                            <label for="finish" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Waktu Selesai</label>
                            <input type="time" id="finish" name="finish"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                        <div>
                            <label for="jumlah_peserta" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah Peserta</label>
                            <input type="number" id="peserta" name="peserta"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Jumlah Peserta" required />
                        </div>
                        <input type="hidden" id="nama_rooms" name="nama_rooms" value="" />
                    </div>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.getElementById('id_rooms').addEventListener('change', function() {
            document.getElementById('nama_rooms').value = this.options[this.selectedIndex].text;
        });

        // Set initial value
        document.getElementById('nama_rooms').value = document.getElementById('id_rooms').options[document.getElementById('id_rooms').selectedIndex].text;

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
