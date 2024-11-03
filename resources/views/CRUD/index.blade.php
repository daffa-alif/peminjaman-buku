<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Operations with Modal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Book Borrowing System</h2>

    <!-- Button to trigger the Create modal -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createModal">Add Record</button>

    <!-- Table to display records -->
    <table class="table table-bordered">
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
                    <td>{{ $item->denda ? 'Rp ' . number_format($item->denda, 0, ',', '.') : 'None' }}</td>
                    <td>
                        <button class="btn btn-success btn-sm editBtn" data-id="{{ $item->id }}" 
                            data-name="{{ $item->nama_peminjam }}"
                            data-book="{{ $item->nama_buku }}"
                            data-quantity="{{ $item->jumlah_buku }}"
                            data-borrow-date="{{ $item->tanggal_pinjam }}"
                            data-return-date="{{ $item->tanggal_kembali }}"
                            data-fine="{{ $item->denda }}">Edit</button>
                        <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $item->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="createForm">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Record</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="createName">Name</label>
                        <input type="text" class="form-control" id="createName" required>
                    </div>
                    <div class="form-group">
                        <label for="createBook">Book</label>
                        <input type="text" class="form-control" id="createBook" required>
                    </div>
                    <div class="form-group">
                        <label for="createQuantity">Quantity</label>
                        <input type="number" class="form-control" id="createQuantity" required>
                    </div>
                    <div class="form-group">
                        <label for="createBorrowDate">Borrow Date</label>
                        <input type="date" class="form-control" id="createBorrowDate" required>
                    </div>
                    <div class="form-group">
                        <label for="createReturnDate">Return Date</label>
                        <input type="date" class="form-control" id="createReturnDate">
                    </div>
                    <div class="form-group">
                        <label for="createFine">Fine</label>
                        <input type="number" class="form-control" id="createFine">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editForm">
                <input type="hidden" id="editId">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Record</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editName">Name</label>
                        <input type="text" class="form-control" id="editName" required>
                    </div>
                    <div class="form-group">
                        <label for="editBook">Book</label>
                        <input type="text" class="form-control" id="editBook" required>
                    </div>
                    <div class="form-group">
                        <label for="editQuantity">Quantity</label>
                        <input type="number" class="form-control" id="editQuantity" required>
                    </div>
                    <div class="form-group">
                        <label for="editBorrowDate">Borrow Date</label>
                        <input type="date" class="form-control" id="editBorrowDate" required>
                    </div>
                    <div class="form-group">
                        <label for="editReturnDate">Return Date</label>
                        <input type="date" class="form-control" id="editReturnDate">
                    </div>
                    <div class="form-group">
                        <label for="editFine">Fine</label>
                        <input type="number" class="form-control" id="editFine">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Create record
    $('#createForm').on('submit', function(e) {
        e.preventDefault();

        let newData = {
            nama_peminjam: $('#createName').val(),
            nama_buku: $('#createBook').val(),
            jumlah_buku: $('#createQuantity').val(),
            tanggal_pinjam: $('#createBorrowDate').val(),
            tanggal_kembali: $('#createReturnDate').val(),
            denda: $('#createFine').val(),
        };

        $.post('/crud', newData, function(response) {
            if (response.success) {
                // Append new item to the table
                $('#itemTable').append(`
                    <tr id="item-${response.item.id}">
                        <td>${response.item.nama_peminjam}</td>
                        <td>${response.item.nama_buku}</td>
                        <td>${response.item.jumlah_buku}</td>
                        <td>${response.item.tanggal_pinjam}</td>
                        <td>${response.item.tanggal_kembali || 'Not Returned'}</td>
                        <td>${response.item.denda ? 'Rp ' + response.item.denda.toLocaleString() : 'None'}</td>
                        <td>
                            <button class="btn btn-success btn-sm editBtn" data-id="${response.item.id}" 
                                data-name="${response.item.nama_peminjam}"
                                data-book="${response.item.nama_buku}"
                                data-quantity="${response.item.jumlah_buku}"
                                data-borrow-date="${response.item.tanggal_pinjam}"
                                data-return-date="${response.item.tanggal_kembali}"
                                data-fine="${response.item.denda}">Edit</button>
                            <button class="btn btn-danger btn-sm deleteBtn" data-id="${response.item.id}">Delete</button>
                        </td>
                    </tr>
                `);
                $('#createModal').modal('hide');
            } else {
                alert('Error: ' + response.error);
            }
        });
    });

    // Edit record
    $(document).on('click', '.editBtn', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const book = $(this).data('book');
        const quantity = $(this).data('quantity');
        const borrowDate = $(this).data('borrow-date');
        const returnDate = $(this).data('return-date');
        const fine = $(this).data('fine');

        $('#editId').val(id);
        $('#editName').val(name);
        $('#editBook').val(book);
        $('#editQuantity').val(quantity);
        $('#editBorrowDate').val(borrowDate);
        $('#editReturnDate').val(returnDate);
        $('#editFine').val(fine);
        $('#editModal').modal('show');
    });

    $('#editForm').on('submit', function(e) {
        e.preventDefault();

        let updatedData = {
            nama_peminjam: $('#editName').val(),
            nama_buku: $('#editBook').val(),
            jumlah_buku: $('#editQuantity').val(),
            tanggal_pinjam: $('#editBorrowDate').val(),
            tanggal_kembali: $('#editReturnDate').val(),
            denda: $('#editFine').val(),
        };

        const id = $('#editId').val();

        $.ajax({
            url: '/crud/' + id,
            type: 'PUT',
            data: updatedData,
            success: function(response) {
                if (response.success) {
                    // Update the row in the table
                    $(`#item-${id} td:nth-child(1)`).text(updatedData.nama_peminjam);
                    $(`#item-${id} td:nth-child(2)`).text(updatedData.nama_buku);
                    $(`#item-${id} td:nth-child(3)`).text(updatedData.jumlah_buku);
                    $(`#item-${id} td:nth-child(4)`).text(updatedData.tanggal_pinjam);
                    $(`#item-${id} td:nth-child(5)`).text(updatedData.tanggal_kembali || 'Not Returned');
                    $(`#item-${id} td:nth-child(6)`).text(updatedData.denda ? 'Rp ' + updatedData.denda.toLocaleString() : 'None');

                    $('#editModal').modal('hide');
                }
            }
        });
    });

    // Delete record
    $(document).on('click', '.deleteBtn', function() {
        const id = $(this).data('id');

        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                url: '/crud/' + id,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        // Remove row from table
                        $('#item-' + id).remove();
                    }
                }
            });
        }
    });
});
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
