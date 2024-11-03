<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Operations</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    @section('content')
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
            <tbody>
                @foreach($items as $item)
                <tr data-id="{{ $item->id }}">
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->nama_peminjam }}</td>
                    <td>{{ $item->nama_buku }}</td>
                    <td>{{ $item->jumlah_buku }}</td>
                    <td>{{ $item->tanggal_pinjam }}</td>
                    <td>{{ $item->tanggal_kembali }}</td>
                    <td>
                        @php
                            $denda = \Carbon\Carbon::parse($item->tanggal_kembali)->diffInDays($item->tanggal_pinjam) > 7 
                                      ? ( \Carbon\Carbon::parse($item->tanggal_kembali)->diffInDays($item->tanggal_pinjam) - 7 ) * 10000 
                                      : 0;
                        @endphp
                        {{ $denda }} Rupiah
                    </td>
                    <td>
                        <button class="btn btn-warning edit-btn" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}">Edit</button>
                        <form action="{{ route('crud.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
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
                    <form action="{{ route('crud.store') }}" method="POST">
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
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
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
                    <form action="{{ route('crud.update', 0) }}" method="POST" id="editForm">
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
                        <button type="submit" class="btn btn-warning">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Handle edit button click
        $(document).on('click', '.edit-btn', function() {
            const row = $(this).closest('tr');
            const id = row.data('id');
            $('#edit_id').val(id);
            $('#edit_nama_peminjam').val(row.find('td:nth-child(2)').text());
            $('#edit_nama_buku').val(row.find('td:nth-child(3)').text());
            $('#edit_jumlah_buku').val(row.find('td:nth-child(4)').text());
            $('#edit_tanggal_pinjam').val(row.find('td:nth-child(5)').text());
            $('#edit_tanggal_kembali').val(row.find('td:nth-child(6)').text());
            $('#editForm').attr('action', '{{ url("crud") }}/' + id);
        });
    </script>
    @endsection
</body>
</html>
