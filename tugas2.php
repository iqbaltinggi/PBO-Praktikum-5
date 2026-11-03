<?php
class Barang {
    private string $kodeBarang;
    private string $namaBarang;
    private int $persediaan;
 
    public function __construct(string $kodeBarang, string $namaBarang, int $persediaan = 0) {
        $kodeBarang = trim($kodeBarang);
        if ($kodeBarang === "") {
            throw new InvalidArgumentException("Kode barang wajib diisi");
        }
        if ($persediaan < 0) {
            throw new InvalidArgumentException("Persediaan awal tidak boleh negatif");
        }
        $this->kodeBarang = $kodeBarang;
        $this->setNamaBarang($namaBarang);
        $this->persediaan = $persediaan;
    }
 
    public function getKodeBarang(): string { return $this->kodeBarang; }
    public function getNamaBarang(): string { return $this->namaBarang; }
    public function getPersediaan(): int    { return $this->persediaan; }
    // tidak ada setKodeBarang() dan tidak ada setPersediaan()
 
    public function setNamaBarang(string $namaBarang): void {
        $namaBarang = trim($namaBarang);
        if ($namaBarang === "") {
            throw new InvalidArgumentException("Nama barang wajib diisi");
        }
        $this->namaBarang = $namaBarang;
    }
 
    public function masukkanBarang(int $jumlah): void {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException("Jumlah barang masuk harus lebih dari 0");
        }
        $this->persediaan += $jumlah;
    }
 
    public function keluarkanBarang(int $jumlah): void {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException("Jumlah barang keluar harus lebih dari 0");
        }
        if ($jumlah > $this->persediaan) {
            throw new InvalidArgumentException(
                "Persediaan tidak mencukupi. Persediaan saat ini: " . $this->persediaan);
        }
        $this->persediaan -= $jumlah;
    }
}
 
echo "<b>PENGUJIAN DATA VALID</b><br>";
$brg = new Barang("BRG-205", "Keyboard Mekanik", 15);
echo "Valid 1 -> " . $brg->getKodeBarang() . " | " . $brg->getNamaBarang()
   . " | persediaan awal " . $brg->getPersediaan() . "<br>";
$brg->masukkanBarang(20);
$brg->keluarkanBarang(7);
echo "Valid 2 -> persediaan setelah +20 dan -7: " . $brg->getPersediaan() . "<br><br>";
 
echo "<b>PENGUJIAN DATA TIDAK VALID</b><br>";
$uji = [
    "Tidak valid 1 (nama spasi)"              => fn() => $brg->setNamaBarang("   "),
    "Tidak valid 2 (keluarkan 60)"            => fn() => $brg->keluarkanBarang(60),
    "Tidak valid 3 (masukkan -9)"             => fn() => $brg->masukkanBarang(-9),
    "Tidak valid 4 (persediaan awal negatif)" => fn() => new Barang("BRG-206", "Mouse Gaming", -2),
];
foreach ($uji as $nama => $aksi) {
    try {
        $aksi();
    } catch (InvalidArgumentException $e) {
        echo $nama . " -> Ditolak: " . $e->getMessage() . "<br>";
    }
}
echo "Persediaan akhir tetap: " . $brg->getPersediaan();
?>