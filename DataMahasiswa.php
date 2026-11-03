<?php
declare(strict_types=1);

require_once __DIR__ . '/Mahasiswa.php';

class DataMahasiswa
{
    private const KEY = 'data_mahasiswa';

    public function __construct()
    {
        if (!isset($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = [];
        }
    }

    public function tambah(Mahasiswa $m): void
    {
        $nim = $m->getNim();
        if (isset($_SESSION[self::KEY][$nim])) {
            throw new InvalidArgumentException("NIM sudah terdaftar");
        }
        $_SESSION[self::KEY][$nim] = $m;
    }

    public function semua(): array
    {
        return $_SESSION[self::KEY];
    }

    public function cari(string $nim): ?Mahasiswa
    {
        return $_SESSION[self::KEY][$nim] ?? null;
    }

    // NIM tidak berubah, jadi object diperbarui pada key yang sama
    public function ubah(Mahasiswa $m): void
    {
        $nim = $m->getNim();
        if (!isset($_SESSION[self::KEY][$nim])) {
            throw new InvalidArgumentException("Data mahasiswa tidak ditemukan");
        }
        $_SESSION[self::KEY][$nim] = $m;
    }

    public function hapus(string $nim): void
    {
        unset($_SESSION[self::KEY][$nim]);
    }
}
