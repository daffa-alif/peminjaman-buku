<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Borrowing System</title>
    <style>
        /* CSS for modal and table styling */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; }
        .modal-content { background: #fff; padding: 20px; border-radius: 5px; width: 400px; max-width: 90%; }
        .modal-header, .modal-footer { display: flex; justify-content: space-between; align-items: center; }
        .modal-header h2 { margin: 0; }
        .close-btn, .action-btn { cursor: pointer; padding: 5px 10px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { padding: 10px; border: 1px solid #ddd; }
        .action-btn { background: #28a745; color: #fff; border: none; }
        .delete-btn { background: #dc3545; color: #fff; border: none; }
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
            <span class="close-btn" onclick="closeModal('createModal')">×</span>
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
            <span class="close-btn" onclick="closeModal('editModal')">×</span>
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
            tanggal_kembali: document.getElementById('tanggal_kembali').value,
            denda: document.getElementById('denda').value
        };

        fetch('/crud', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Append new item to the table
                const newRow = `
                    <tr id="item-${data.item.id}">
                        <td>${data.item.nama_peminjam}</td>
                        <td>${data.item.nama_buku}</td>
                        <td>${data.item.jumlah_buku}</td>
                        <td>${data.item.tanggal_pinjam}</td>
                        <td>${data.item.tanggal_kembali || 'Not Returned'}</td>
                        <td>Rp ${data.item.denda.toLocaleString('id-ID')}</td>
                        <td>
                            <button class="action-btn" onclick="editItem(${data.item.id})">Edit</button>
                            <button class="delete-btn" onclick="deleteItem(${data.item.id})">Delete</button>
                        </td>
                    </tr>
                `;
                document.getElementById('itemTable').insertAdjacentHTML('beforeend', newRow);
                closeModal('createModal');
            } else {
                alert(data.message);
            }
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
            });
    }

    function handleEdit(event) {
        event.preventDefault();
        const id = document.getElementById('edit_id').value;
        const formData = {
            nama_peminjam: document.getElementById('edit_nama_peminjam').value,
            nama_buku: document.getElementById('edit_nama_buku').value,
            jumlah_buku: document.getElementById('edit_jumlah_buku').value,
            tanggal_pinjam: document.getElementById('edit_tanggal_pinjam').value,
            tanggal_kembali: document.getElementById('edit_tanggal_kembali').value,
            denda: document.getElementById('edit_denda').value
        };

        fetch(`/crud/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`item-${id}`);
                row.cells[0].innerText = data.item.nama_peminjam;
                row.cells[1].innerText = data.item.nama_buku;
                row.cells[2].innerText = data.item.jumlah_buku;
                row.cells[3].innerText = data.item.tanggal_pinjam;
                row.cells[4].innerText = data.item.tanggal_kembali || 'Not Returned';
                row.cells[5].innerText = `Rp ${data.item.denda.toLocaleString('id-ID')}`;
                closeModal('editModal');
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Delete functionality
    function deleteItem(id) {
        if (confirm('Are you sure you want to delete this record?')) {
            fetch(`/crud/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById(`item-${id}`);
                    row.remove();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>
