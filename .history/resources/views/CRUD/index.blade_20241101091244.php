<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Borrowing System</title>
    <style>
        /* Styling for the modal */
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
        .close-btn, .action-btn {
            cursor: pointer;
            padding: 5px 10px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .action-btn {
            background: #28a745;
            color: #fff;
            border: none;
        }
        .delete-btn {
            background: #dc3545;
            color: #fff;
            border: none;
        }
    </style>
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

    function handleCreate(event) {
        event.preventDefault();
        // Implement AJAX request to send data to backend and refresh table
    }

    function editItem(id) {
        // Load item data into form and open edit modal
    }

    function deleteItem(id) {
        // Implement deletion logic
    }
</script>
</body>
</html>
