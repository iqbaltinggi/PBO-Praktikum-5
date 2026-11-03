<?php
declare(strict_types=1);

class Mahasiswa
{
    public const AGAMA_VALID = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];

    private string $nim;
    private string $nama;
    private string $fakultas;
    private string $prodi;
    private string $asalSekolah;
    private int $semester;
    private string $hobi;
    private string $agama;

    public function __construct(
        string $nim, string $nama, string $fakultas, string $prodi,
        string $asalSekolah, int $semester, string $hobi, string $agama
    ) {
        $nim = trim($nim);
        if ($nim === "") {
            throw new InvalidArgumentException("NIM wajib diisi");
        }
        $this->nim = $nim; // identitas tetap, tidak ada setNim()
        $this->setNama($nama);
        $this->setFakultas($fakultas);
        $this->setProdi($prodi);
        $this->setAsalSekolah($asalSekolah);
        $this->setSemester($semester);
        $this->setHobi($hobi);
        $this->setAgama($agama);
    }

    public function getNim(): string { return $this->nim; }
    public function getNama(): string { return $this->nama; }
    public function getFakultas(): string { return $this->fakultas; }
    public function getProdi(): string { return $this->prodi; }
    public function getAsalSekolah(): string { return $this->asalSekolah; }
    public function getSemester(): int { return $this->semester; }
    public function getHobi(): string { return $this->hobi; }
    public function getAgama(): string { return $this->agama; }

    public function setNama(string $nama): void
    {
        $nama = trim($nama);
        if ($nama === "") {
            throw new InvalidArgumentException("Nama tidak boleh kosong");
        }
        $this->nama = $nama;
    }

    public function setFakultas(string $fakultas): void
    {
        $fakultas = trim($fakultas);
        if ($fakultas === "") {
            throw new InvalidArgumentException("Fakultas wajib diisi");
        }
        $this->fakultas = $fakultas;
    }

    public function setProdi(string $prodi): void
    {
        $prodi = trim($prodi);
        if ($prodi === "") {
            throw new InvalidArgumentException("Prodi wajib diisi");
        }
        $this->prodi = $prodi;
    }

    public function setAsalSekolah(string $asalSekolah): void
    {
        $asalSekolah = trim($asalSekolah);
        if ($asalSekolah === "") {
            throw new InvalidArgumentException("Asal sekolah wajib diisi");
        }
        $this->asalSekolah = $asalSekolah;
    }

    public function setSemester(int $semester): void
    {
        if ($semester < 1 || $semester > 14) {
            throw new InvalidArgumentException("Semester harus berada pada rentang 1-14");
        }
        $this->semester = $semester;
    }

    public function setHobi(string $hobi): void
    {
        $this->hobi = trim($hobi);
    }

    public function setAgama(string $agama): void
    {
        if (!in_array($agama, self::AGAMA_VALID, true)) {
            throw new InvalidArgumentException("Agama tidak termasuk dalam daftar yang diizinkan");
        }
        $this->agama = $agama;
    }
}
