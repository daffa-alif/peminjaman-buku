<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Borrowing System</title>
    <style>
        /* Basic table styling */
        body { font-family: Arial, sans-serif; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        h2 { text-align: center; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th { background-color: #f2f2f2; }

        /* Button styling */
        .btn {
            padding: 8px 12px;
            cursor: pointer;
            color: #fff;
            border: none;
            margin-right: 5px;
            border-radius: 5px;
        }
        .btn-primary { background-color: #007bff; }
        .btn-success { background-color: #28a745; }
        .btn-danger { background-color: #dc3545; }
        
        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 300px;
            max-width: 100%;
        }
        .modal-header, .modal-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .close {
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            color: #aaa;
        }
        .close:hover { color: #000; }
    </style>
</head>
<body>
<div class="container">
    <h2>Book Borrowing System</h2>

    <!-- Button to trigger the Create modal -->
    <button class="btn btn-primary" onclick="openModal('createModal')">Add Record</button>

    <!-- Table to display records -->
    <table>
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
            <!-- Records will be populated here -->
            @foreach($items as $item)
                <tr id="item-{{ $item->id }}">
                    <td>{{ $item->nama_peminjam }}</td>
                    <td>{{ $item->nama_buku }}</td>
                    <td>{{ $item->jumlah_buku }}</td>
                    <td>{{ $item->tanggal_pinjam }}</td>
                    <td>{{ $item->tanggal_kembali ?? 'Not Returned' }}</td>
                    <td>{{ $item->denda ? 'Rp ' . number_format($item->denda, 0, ',', '.') : 'None' }}</td>
                    <td>
                        <button class="btn btn-success" onclick="editRecord({{ $item->id }})">Edit</button>
                        <button class="btn btn-danger" onclick="deleteRecord({{ $item->id }})">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Create Modal -->
<div class="modal" id="createModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Add New Record</h5>
            <span class="close" onclick="closeModal('createModal')">&times;</span>
        </div>
        <form id="createForm" onsubmit="submitCreateForm(event)">
            <div>
                <label>Name:</label>
                <input type="text" name="nama_peminjam" required>
            </div>
            <div>
                <label>Book:</label>
                <input type="text" name="nama_buku" required>
            </div>
            <div>
                <label>Quantity:</label>
                <input type="number" name="jumlah_buku" required>
            </div>
            <div>
                <label>Borrow Date:</label>
                <input type="date" name="tanggal_pinjam" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createModal')">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Edit Record</h5>
            <span class="close" onclick="closeModal('editModal')">&times;</span>
        </div>
        <form id="editForm" onsubmit="submitEditForm(event)">
            <input type="hidden" id="editId" name="id">
            <div>
                <label>Name:</label>
                <input type="text" id="editNamaPeminjam" name="nama_peminjam" required>
            </div>
            <div>
                <label>Book:</label>
                <input type="text" id="editNamaBuku" name="nama_buku" required>
            </div>
            <div>
                <label>Quantity:</label>
                <input type="number" id="editJumlahBuku" name="jumlah_buku" required>
            </div>
            <div>
                <label>Borrow Date:</label>
                <input type="date" id="editTanggalPinjam" name="tanggal_pinjam" required>
            </div>
            <div>
                <label>Return Date:</label>
                <input type="date" id="editTanggalKembali" name="tanggal_kembali">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Functions to open and close modals
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    // Handle form submissions (replace with AJAX for actual functionality)
    function submitCreateForm(event) {
        event.preventDefault();
        alert('Create form submitted');
        closeModal('createModal');
    }
    function submitEditForm(event) {
        event.preventDefault();
        alert('Edit form submitted');
        closeModal('editModal');
    }

    // Dummy functions for edit and delete (replace with actual logic)
    function editRecord(id) {
        openModal('editModal');
        // Populate edit form fields with record data
    }
    function deleteRecord(id) {
        if (confirm('Are you sure you want to delete this record?')) {
            alert('Record deleted');
        }
    }
</script>
</body>
</html>
