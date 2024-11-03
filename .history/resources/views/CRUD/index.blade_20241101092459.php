<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Operations</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
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
        // Create new item
        $('#createBtn').on('click', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route("CRUD.store") }}',
                data: $('#createForm').serialize(),
                success: function(response) {
                    $('#createModal').modal('hide');
                    $('#createForm')[0].reset();
                    $('#itemsTable').append(`
                        <tr data-id="${response.item.id}">
                            <td>${response.item.id}</td>
                            <td>${response.item.nama_peminjam}</td>
                            <td>${response.item.nama_buku}</td>
                            <td>${response.item.jumlah_buku}</td>
                            <td>${response.item.tanggal_pinjam}</td>
                            <td>${response.item.tanggal_kembali}</td>
                            <td>
                                <button class="btn btn-warning edit-btn" data-toggle="modal" data-target="#editModal">Edit</button>
                                <button class="btn btn-danger delete-btn">Delete</button>
                            </td>
                        </tr>
                    `);
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });

        // Edit item
        $(document).on('click', '.edit-btn', function() {
            const row = $(this).closest('tr');
            const id = row.data('id');
            $.ajax({
                type: 'GET',
                url: `{{ url('CRUD') }}/${id}`,
                success: function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_nama_peminjam').val(data.nama_peminjam);
                    $('#edit_nama_buku').val(data.nama_buku);
                    $('#edit_jumlah_buku').val(data.jumlah_buku);
                    $('#edit_tanggal_pinjam').val(data.tanggal_pinjam);
                    $('#edit_tanggal_kembali').val(data.tanggal_kembali);
                },
                error: function(xhr) {
                    alert('Error fetching data: ' + xhr.responseJSON.message);
                }
            });
        });

        // Update item
        $('#editBtn').on('click', function() {
            const id = $('#edit_id').val();
            $.ajax({
                type: 'PUT',
                url: `{{ url('CRUD') }}/${id}`,
                data: $('#editForm').serialize(),
                success: function(response) {
                    $('#editModal').modal('hide');
                    const row = $(`tr[data-id="${id}"]`);
                    row.find('td:nth-child(2)').text(response.item.nama_peminjam);
                    row.find('td:nth-child(3)').text(response.item.nama_buku);
                    row.find('td:nth-child(4)').text(response.item.jumlah_buku);
                    row.find('td:nth-child(5)').text(response.item.tanggal_pinjam);
                    row.find('td:nth-child(6)').text(response.item.tanggal_kembali);
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });

        // Delete item
        $(document).on('click', '.delete-btn', function() {
            const row = $(this).closest('tr');
            const id = row.data('id');
            $.ajax({
                type: 'DELETE',
                url: `{{ url('CRUD') }}/${id}`,
                success: function(response) {
                    row.remove();
                    alert(response.success);
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });
    });
</script>
</body>
</html>
