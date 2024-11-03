<table>
    <tr>
        <th>Nama Peminjam</th>
        <th>Nama Buku</th>
        <th>Jumlah Buku</th>
        <th>Tanggal Pinjam</th>
        <th>Tanggal Kembali</th>
        <th>Denda</th>
    </tr>
    @foreach ($pinjamBuku as $item)
    <tr>
        <td>{{ $item->nama_peminjam }}</td>
        <td>{{ $item->nama_buku }}</td>
        <td>{{ $item->jumlah_buku }}</td>
        <td>{{ $item->tanggal_pinjam }}</td>
        <td>{{ $item->tanggal_kembali ?? 'Belum Dikembalikan' }}</td>
        <td>{{ $item->denda ? 'Rp ' . number_format($item->denda, 0, ',', '.') : 'Tidak Ada' }}</td>
    </tr>
    @endforeach
</table>
