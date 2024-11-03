<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Borrowing System</title>
    <style>
        /* CSS for modal and table styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        h2 {
            text-align: center;
        }

        button {
            cursor: pointer;
            margin-top: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
            max-width: 90%;
        }

        .modal-header, .modal-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
        }

        .close-btn {
            background: transparent;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .action-btn {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .delete-btn {
            background: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container">
    <h2>Book Borrowing System</h2>
    <button class="action-btn" onclick="openModal('createModal')">Add Record</button>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Book</th>
                <th>Quantity</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
                <th>Fine</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="itemTable">
            @foreach($items as $item)
                <tr id="item-{{ $item->id }}">
                    <td>{{ $item->nama_peminjam }}</td>
                    <td>{{ $item->nama_buku }}</td>
                    <td>{{ $item->jumlah_buku }}</td>
                    <td>{{ $item->tanggal_pinjam }}</td>
                    <td>{{ $item->tanggal_kembali ?? 'Not Returned' }}</td>
                    <td>Rp {{ number_format($item->denda, 0, ',', '.') }}</td>
                    <td>
                        <button class="action-btn" onclick="editItem({{ $item->id }})">Edit</button>
                        <button class="delete-btn" onclick="deleteItem({{ $item->id }})">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Create Modal -->
<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Record</h2>
            <button class="close-btn" onclick="closeModal('createModal')">×</button>
        </div>
        <form id="createForm" onsubmit="handleCreate(event)">
            <label>Name:</label>
            <input type="text" id="nama_peminjam" required><br><br>

            <label>Book:</label>
            <input type="text" id="nama_buku" required><br><br>

            <label>Quantity:</label>
            <input type="number" id="jumlah_buku" required><br><br>

            <label>Borrow Date:</label>
            <input type="date" id="tanggal_pinjam" required><br><br>

            <label>Return Date:</label>
            <input type="date" id="tanggal_kembali" onchange="calculateFine()"><br><br>

            <label>Fine:</label>
            <input type="text" id="denda" readonly><br><br>

            <div class="modal-footer">
                <button type="button" class="close-btn" onclick="closeModal('createModal')">Cancel</button>
                <button type="submit" class="action-btn">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Record</h2>
            <button class="close-btn" onclick="closeModal('editModal')">×</button>
        </div>
        <form id="editForm" onsubmit="handleEdit(event)">
            <input type="hidden" id="edit_id">
            <label>Name:</label>
            <input type="text" id="edit_nama_peminjam" required><br><br>

            <label>Book:</label>
            <input type="text" id="edit_nama_buku" required><br><br>

            <label>Quantity:</label>
            <input type="number" id="edit_jumlah_buku" required><br><br>

            <label>Borrow Date:</label>
            <input type="date" id="edit_tanggal_pinjam" required><br><br>

            <label>Return Date:</label>
            <input type="date" id="edit_tanggal_kembali" onchange="calculateEditFine()"><br><br>

            <label>Fine:</label>
            <input type="text" id="edit_denda" readonly><br><br>

            <div class="modal-footer">
                <button type="button" class="close-btn" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="action-btn">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    function calculateFine() {
        const borrowDate = new Date(document.getElementById('tanggal_pinjam').value);
        const returnDate = new Date(document.getElementById('tanggal_kembali').value);
        
        if (!borrowDate || !returnDate) return;

        const diffTime = Math.abs(returnDate - borrowDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        let fine = 0;
        if (diffDays > 7) {
            fine = (diffDays - 7) * 10000;
        }

        document.getElementById('denda').value = fine > 0 ? `Rp ${fine.toLocaleString('id-ID')}` : 'None';
    }

    function calculateEditFine() {
        const borrowDate = new Date(document.getElementById('edit_tanggal_pinjam').value);
        const returnDate = new Date(document.getElementById('edit_tanggal_kembali').value);
        
        if (!borrowDate || !returnDate) return;

        const diffTime = Math.abs(returnDate - borrowDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        let fine = 0;
        if (diffDays > 7) {
            fine = (diffDays - 7) * 10000;
        }

        document.getElementById('edit_denda').value = fine > 0 ? `Rp ${fine.toLocaleString('id-ID')}` : 'None';
    }

    // Create functionality
    function handleCreate(event) {
        event.preventDefault();
        const formData = {
            nama_peminjam: document.getElementById('nama_peminjam').value,
            nama_buku: document.getElementById('nama_buku').value,
            jumlah_buku: document.getElementById('jumlah_buku').value,
            tanggal_pinjam: document.getElementById('tanggal_pinjam').value,
            tanggal_kembali: document.getElementById('tanggal_kembali').value
        };

        fetch('/crud', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            // Add the new item to the table (not shown here for brevity)
            closeModal('createModal');
        })
        .catch(error => console.error('Error:', error));
    }

    // Edit functionality
    function editItem(id) {
        fetch(`/crud/${id}`)
            .then(response => response.json())
            .then(data => {
                const item = data.item;
                document.getElementById('edit_id').value = item.id;
                document.getElementById('edit_nama_peminjam').value = item.nama_peminjam;
                document.getElementById('edit_nama_buku').value = item.nama_buku;
                document.getElementById('edit_jumlah_buku').value = item.jumlah_buku;
                document.getElementById('edit_tanggal_pinjam').value = item.tanggal_pinjam;
                document.getElementById('edit_tanggal_kembali').value = item.tanggal_kembali || '';
                calculateEditFine();
                openModal('editModal');
            })
            .catch(error => console.error('Error:', error));
    }

    function handleEdit(event) {
        event.preventDefault();
        const id = document.getElementById('edit_id').value;
        const formData = {
            nama_peminjam: document.getElementById('edit_nama_peminjam').value,
            nama_buku: document.getElementById('edit_nama_buku').value,
            jumlah_buku: document.getElementById('edit_jumlah_buku').value,
            tanggal_pinjam: document.getElementById('edit_tanggal_pinjam').value,
            tanggal_kembali: document.getElementById('edit_tanggal_kembali').value
        };

        fetch(`/crud/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            // Update the item in the table (not shown here for brevity)
            closeModal('editModal');
        })
        .catch(error => console.error('Error:', error));
    }

    // Delete functionality
    function deleteItem(id) {
        if (confirm('Are you sure you want to delete this item?')) {
            fetch(`/crud/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    document.getElementById(`item-${id}`).remove();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>
</body>
</html>
