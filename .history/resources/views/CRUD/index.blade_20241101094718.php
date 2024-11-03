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
                        <button class="btn btn-success btn-sm editBtn" data-id="{{ $item->id }}">Edit</button>
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
                    <!-- Fields for the form -->
                    <!-- Repeat similar structure for each form field -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal (similar to Create Modal) -->
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
                    <!-- Fields for the form -->
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
        // Handle form submission
    });

    // Edit record
    $(document).on('click', '.editBtn', function() {
        // Populate form with data and show modal
    });

    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        // Handle form submission
    });

    // Delete record
    $(document).on('click', '.deleteBtn', function() {
        // Handle deletion
    });
});
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
