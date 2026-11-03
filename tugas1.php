<?php
class Produk {
    public string $nama;
    protected string $kategori;
    private int $harga;

    public function __construct(string $nama, string $kategori, int $harga) {
        $this->nama = $nama;
        $this->kategori = $kategori;
        $this->harga = $harga;
    }

    // jalur resmi untuk membaca property private
    public function getHarga(): int {
        return $this->harga;
    }
}

class ProdukDigital extends Produk {
    public function infoKategori(): string {
        return "Kategori: " . $this->kategori; // protected boleh diakses subclass
    }

    public function cobaHarga(): int {
        return $this->getHarga(); // private diakses melalui getter
    }
}

$p = new ProdukDigital("Paket Diamond 86 DM", "Digital", 50000);

// 1. public dari luar class
echo "Nama     : " . $p->nama . "<br>";
$p->nama = "Paket Diamond 172 DM";
echo "Nama baru: " . $p->nama . "<br>";

// 2. protected lewat method subclass
echo $p->infoKategori() . "<br>";

// 3. private lewat getter di class induk
echo "Harga    : Rp " . number_format($p->getHarga(), 0, ',', '.') . "<br>";

// 4. protected langsung dari luar -> error
// echo $p->kategori;

// 5. private langsung dari luar -> error
// echo $p->harga;
?>