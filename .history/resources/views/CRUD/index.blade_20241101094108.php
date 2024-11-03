<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Operations</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Tailwind CSS modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 10; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h2 class="text-2xl font-bold">CRUD Operations</h2>
        <button onclick="document.getElementById('createModal').style.display='block'" class="bg-blue-500 text-white px-4 py-2 rounded">Add New</button>
        
        <table class="min-w-full mt-5 bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="py-2 px-4 border">ID</th>
                    <th class="py-2 px-4 border">Nama Peminjam</th>
                    <th class="py-2 px-4 border">Nama Buku</th>
                    <th class="py-2 px-4 border">Jumlah Buku</th>
                    <th class="py-2 px-4 border">Tanggal Pinjam</th>
                    <th class="py-2 px-4 border">Tanggal Kembali</th>
                    <th class="py-2 px-4 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td class="py-2 px-4 border">{{ $item->id }}</td>
                    <td class="py-2 px-4 border">{{ $item->nama_peminjam }}</td>
                    <td class="py-2 px-4 border">{{ $item->nama_buku }}</td>
                    <td class="py-2 px-4 border">{{ $item->jumlah_buku }}</td>
                    <td class="py-2 px-4 border">{{ $item->tanggal_pinjam }}</td>
                    <td class="py-2 px-4 border">{{ $item->tanggal_kembali }}</td>
                    <td class="py-2 px-4 border">
                        <button onclick="openEditModal({{ $item->id }}, '{{ $item->nama_peminjam }}', '{{ $item->nama_buku }}', {{ $item->jumlah_buku }}, '{{ $item->tanggal_pinjam }}', '{{ $item->tanggal_kembali }}')" class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</button>
                        <form action="{{ route('crud.destroy', $item->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="modal">
        <div class="modal-content">
            <span onclick="document.getElementById('createModal').style.display='none'" class="close cursor-pointer">&times;</span>
            <h3 class="text-lg font-semibold">Add New Item</h3>
            <form action="{{ route('crud.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="nama_peminjam" class="block text-gray-700">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" class="border border-gray-400 rounded w-full px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="nama_buku" class="block text-gray-700">Nama Buku</label>
                    <input type="text" name="nama_buku" class="border border-gray-400 rounded w-full px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="jumlah_buku" class="block text-gray-700">Jumlah Buku</label>
                    <input type="number" name="jumlah_buku" class="border border-gray-400 rounded w-full px-3 py-2" min="1" required>
                </div>
                <div class="mb-4">
                    <label for="tanggal_pinjam" class="block text-gray-700">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="border border-gray-400 rounded w-full px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="tanggal_kembali" class="block text-gray-700">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" class="border border-gray-400 rounded w-full px-3 py-2">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span onclick="document.getElementById('editModal').style.display='none'" class="close cursor-pointer">&times;</span>
            <h3 class="text-lg font-semibold">Edit Item</h3>
            <form id="editForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit_nama_peminjam" class="block text-gray-700">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" id="edit_nama_peminjam" class="border border-gray-400 rounded w-full px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="edit_nama_buku" class="block text-gray-700">Nama Buku</label>
                    <input type="text" name="nama_buku" id="edit_nama_buku" class="border border-gray-400 rounded w-full px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="edit_jumlah_buku" class="block text-gray-700">Jumlah Buku</label>
                    <input type="number" name="jumlah_buku" id="edit_jumlah_buku" class="border border-gray-400 rounded w-full px-3 py-2" min="1" required>
                </div>
                <div class="mb-4">
                    <label for="edit_tanggal_pinjam" class="block text-gray-700">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" id="edit_tanggal_pinjam" class="border border-gray-400 rounded w-full px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="edit_tanggal_kembali" class="block text-gray-700">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" id="edit_tanggal_kembali" class="border border-gray-400 rounded w-full px-3 py-2">
                </div>
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
            </form>
        </div>
    </div>

    <script>
        // Function to open edit modal and fill in data
        function openEditModal(id, nama_peminjam, nama_buku, jumlah_buku, tanggal_pinjam, tanggal_kembali) {
            document.getElementById('editModal').style.display = 'block';
            document.getElementById('editForm').action = '/crud/' + id; // Adjust the action URL
            document.getElementById('edit_nama_peminjam').value = nama_peminjam;
            document.getElementById('edit_nama_buku').value = nama_buku;
            document.getElementById('edit_jumlah_buku').value = jumlah_buku;
            document.getElementById('edit_tanggal_pinjam').value = tanggal_pinjam;
            document.getElementById('edit_tanggal_kembali').value = tanggal_kembali || ''; // Handle null value
        }

        // Close modals when clicking outside of them
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = "none";
            }
        }
    </script>
</body>
</html>
