<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRUD Operations</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>CRUD Operations</h2>
        <button class="btn btn-primary" data-toggle="modal" data-target="#createModal">Add New</button>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Peminjam</th>
                    <th>Nama Buku</th>
                    <th>Jumlah Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Denda</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="itemsTable">
                @foreach($items as $item)
                <tr data-id="{{ $item->id }}">
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->nama_peminjam }}</td>
                    <td>{{ $item->nama_buku }}</td>
                    <td>{{ $item->jumlah_buku }}</td>
                    <td>{{ $item->tanggal_pinjam }}</td>
                    <td>{{ $item->tanggal_kembali }}</td>
                    <td class="fine-cell"></td> <!-- We'll calculate fine using JavaScript -->
                    <td>
                        <button class="btn btn-warning edit-btn" data-toggle="modal" data-target="#editModal">Edit</button>
                        <button class="btn btn-danger delete-btn">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Add New Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createForm">
                        @csrf
                        <div class="form-group">
                            <label for="nama_peminjam">Nama Peminjam</label>
                            <input type="text" class="form-control" id="nama_peminjam" name="nama_peminjam" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_buku">Nama Buku</label>
                            <input type="text" class="form-control" id="nama_buku" name="nama_buku" required>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_buku">Jumlah Buku</label>
                            <input type="number" class="form-control" id="jumlah_buku" name="jumlah_buku" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_pinjam">Tanggal Pinjam</label>
                            <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_kembali">Tanggal Kembali</label>
                            <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="createBtn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group">
                            <label for="edit_nama_peminjam">Nama Peminjam</label>
                            <input type="text" class="form-control" id="edit_nama_peminjam" name="nama_peminjam" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_nama_buku">Nama Buku</label>
                            <input type="text" class="form-control" id="edit_nama_buku" name="nama_buku" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_jumlah_buku">Jumlah Buku</label>
                            <input type="number" class="form-control" id="edit_jumlah_buku" name="jumlah_buku" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_tanggal_pinjam">Tanggal Pinjam</label>
                            <input type="date" class="form-control" id="edit_tanggal_pinjam" name="tanggal_pinjam" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_tanggal_kembali">Tanggal Kembali</label>
                            <input type="date" class="form-control" id="edit_tanggal_kembali" name="tanggal_kembali">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning" id="editBtn">Update</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Function to calculate fine
            function calculateFine(tanggal_kembali, tanggal_pinjam) {
                const today = new Date();
                const returnDate = new Date(tanggal_kembali || today);
                const borrowDate = new Date(tanggal_pinjam);
                const daysLate = Math.floor((returnDate - borrowDate) / (1000 * 60 * 60 * 24)) - 7; // 7 days grace

                if (daysLate > 0) {
                    return daysLate * 10000; // Fine of 10,000 rupiah for each day after 7 days
                }
                return 0; // No fine
            }

            // Calculate and display fines for existing items
            $('#itemsTable tr').each(function() {
                const tanggal_kembali = $(this).find('td:nth-child(6)').text();
                const tanggal_pinjam = $(this).find('td:nth-child(5)').text();
                const fine = calculateFine(tanggal_kembali, tanggal_pinjam);
                $(this).find('.fine-cell').text(fine);
            });

            // Create new item
            $('#createBtn').on('click', function() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route("crud.store") }}',
                    data: $('#createForm').serialize(),
                    success: function(response) {
                        $('#createModal').modal('hide');
                        $('#itemsTable').append(`
                            <tr data-id="${response.item.id}">
                                <td>${response.item.id}</td>
                                <td>${response.item.nama_peminjam}</td>
                                <td>${response.item.nama_buku}</td>
                                <td>${response.item.jumlah_buku}</td>
                                <td>${response.item.tanggal_pinjam}</td>
                                <td>${response.item.tanggal_kembali}</td>
                                <td class="fine-cell">${calculateFine(response.item.tanggal_kembali, response.item.tanggal_pinjam)}</td>
                                <td>
                                    <button class="btn btn-warning edit-btn" data-toggle="modal" data-target="#editModal">Edit</button>
                                    <button class="btn btn-danger delete-btn">Delete</button>
                                </td>
                            </tr>
                        `);
                        alert(response.success);
                    },
                    error: function(xhr) {
                        alert('Error adding item: ' + xhr.responseJSON.message);
                    }
                });
            });

            // Edit item
            $(document).on('click', '.edit-btn', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');
                $('#edit_id').val(id);
                $('#edit_nama_peminjam').val(row.find('td:nth-child(2)').text());
                $('#edit_nama_buku').val(row.find('td:nth-child(3)').text());
                $('#edit_jumlah_buku').val(row.find('td:nth-child(4)').text());
                $('#edit_tanggal_pinjam').val(row.find('td:nth-child(5)').text());
                $('#edit_tanggal_kembali').val(row.find('td:nth-child(6)').text());
            });

            $('#editBtn').on('click', function() {
                const id = $('#edit_id').val();
                $.ajax({
                    type: 'PUT',
                    url: `{{ url('crud') }}/${id}`,
                    data: $('#editForm').serialize(),
                    success: function(response) {
                        $('#editModal').modal('hide');
                        const row = $(`tr[data-id="${id}"]`);
                        row.find('td:nth-child(2)').text(response.item.nama_peminjam);
                        row.find('td:nth-child(3)').text(response.item.nama_buku);
                        row.find('td:nth-child(4)').text(response.item.jumlah_buku);
                        row.find('td:nth-child(5)').text(response.item.tanggal_pinjam);
                        row.find('td:nth-child(6)').text(response.item.tanggal_kembali);
                        row.find('.fine-cell').text(calculateFine(response.item.tanggal_kembali, response.item.tanggal_pinjam));
                        alert(response.success);
                    },
                    error: function(xhr) {
                        alert('Error updating item: ' + xhr.responseJSON.message);
                    }
                });
            });

            // Delete item
            $(document).on('click', '.delete-btn', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');
                if (confirm('Are you sure you want to delete this item?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: `{{ url('crud') }}/${id}`,
                        success: function(response) {
                            row.remove();
                            alert(response.success);
                        },
                        error: function(xhr) {
                            alert('Error deleting item: ' + xhr.responseJSON.message);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
