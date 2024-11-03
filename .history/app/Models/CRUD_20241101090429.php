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

    public function calculateDenda()
    {
        $today = Carbon::today();
        $tanggalPinjam = Carbon::parse($this->tanggal_pinjam);
        $tanggalKembali = $this->tanggal_kembali ? Carbon::parse($this->tanggal_kembali) : null;

        if (!$tanggalKembali) {
            // If the book is missing
            $this->denda = 100000;
        } else {
            $diff = $tanggalPinjam->diffInDays($tanggalKembali);
            if ($diff > 7) {
                // Calculate fine for late return
                $this->denda = ($diff - 7) * 10000;
            } else {
                $this->denda = 0;
            }
        }
        $this->save();
    }
}
