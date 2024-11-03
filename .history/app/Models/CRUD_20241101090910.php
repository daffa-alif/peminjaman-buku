<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PinjamBuku extends Model
{
    use HasFactory;

    protected $table = 'pinjam_buku';
    protected $fillable = ['nama_peminjam', 'nama_buku', 'jumlah_buku', 'tanggal_pinjam', 'tanggal_kembali', 'denda'];

    /**
     * Calculate the fine (denda) based on the return date and business rules.
     */
    public function calculateDenda()
    {
        $tanggalPinjam = Carbon::parse($this->tanggal_pinjam);
        $tanggalKembali = $this->tanggal_kembali ? Carbon::parse($this->tanggal_kembali) : null;

        if (is_null($tanggalKembali)) {
            // No return date specified, apply missing book fine
            $this->denda = 100000;
        } else {
            $daysBorrowed = $tanggalPinjam->diffInDays($tanggalKembali);
            $this->denda = $daysBorrowed > 7 ? ($daysBorrowed - 7) * 10000 : 0;
        }

        $this->save();
    }
}
