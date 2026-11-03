<?php
declare(strict_types=1);

require_once __DIR__ . '/Mahasiswa.php';   // class harus dimuat sebelum session_start
require_once __DIR__ . '/DataMahasiswa.php';
session_start();

function aman(mixed $teks): string
{
    return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8');
}

$repo   = new DataMahasiswa();
$error  = "";
$sukses = "";
$edit   = null;

// Mode edit: ambil object lewat repository
if (isset($_GET['edit'])) {
    $edit = $repo->cari((string)$_GET['edit']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    try {
        if ($aksi === 'tambah') {
            $semester = filter_var($_POST['semester'] ?? '', FILTER_VALIDATE_INT);
            if ($semester === false) {
                throw new InvalidArgumentException("Semester harus berupa angka bulat");
            }
            $m = new Mahasiswa(
                (string)($_POST['nim'] ?? ''),
                (string)($_POST['nama'] ?? ''),
                (string)($_POST['fakultas'] ?? ''),
                (string)($_POST['prodi'] ?? ''),
                (string)($_POST['asalSekolah'] ?? ''),
                $semester,
                (string)($_POST['hobi'] ?? ''),
                (string)($_POST['agama'] ?? '')
            );
            $repo->tambah($m);
            $sukses = "Data mahasiswa berhasil ditambahkan";
        } elseif ($aksi === 'ubah') {
            $asli = $repo->cari((string)($_POST['nim'] ?? ''));
            if ($asli === null) {
                throw new InvalidArgumentException("Data mahasiswa tidak ditemukan");
            }
            $m = clone $asli; // ubah salinan dulu; simpan hanya jika semua setter lolos
            $semester = filter_var($_POST['semester'] ?? '', FILTER_VALIDATE_INT);
            if ($semester === false) {
                throw new InvalidArgumentException("Semester harus berupa angka bulat");
            }
            $m->setNama((string)($_POST['nama'] ?? ''));
            $m->setFakultas((string)($_POST['fakultas'] ?? ''));
            $m->setProdi((string)($_POST['prodi'] ?? ''));
            $m->setAsalSekolah((string)($_POST['asalSekolah'] ?? ''));
            $m->setSemester($semester);
            $m->setHobi((string)($_POST['hobi'] ?? ''));
            $m->setAgama((string)($_POST['agama'] ?? ''));
            $repo->ubah($m);
            $sukses = "Data mahasiswa berhasil diperbarui";
        } elseif ($aksi === 'hapus') {
            $repo->hapus((string)($_POST['nim'] ?? ''));
            $sukses = "Data mahasiswa berhasil dihapus";
        }
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>CRUD Mahasiswa - Enkapsulasi</title>
<style>
 *{box-sizing:border-box}
 body{font-family:Arial,sans-serif;margin:0;padding:24px;background:#f5f7fa}
 .wrap{max-width:1100px;margin:0 auto}
 h2,h3{text-align:center}
 .card{max-width:460px;margin:0 auto 24px;background:#fff;padding:20px 24px;border:1px solid #ddd;border-radius:8px}
 .card h3{margin-top:0}
 table{border-collapse:collapse;width:100%;background:#fff}
 th,td{border:1px solid #bbb;padding:6px 8px;font-size:14px}
 th{background:#1f3a5f;color:#fff}
 .error{background:#fde2e2;color:#9b1c1c;padding:8px;margin:10px auto;max-width:460px}
 .ok{background:#def7e5;color:#14532d;padding:8px;margin:10px auto;max-width:460px}
 label{display:block;margin-top:10px;font-weight:bold}
 input,select{padding:7px;width:100%}
 button[type=submit]{padding:7px 18px;cursor:pointer}
 .aksi-form{text-align:center}
 .tabel-wrap{overflow-x:auto}
</style>
</head>
<body>
<div class="wrap">
<h2>CRUD Data Mahasiswa (Enkapsulasi)</h2>

<?php if ($error !== ""): ?><div class="error">Error: <?= aman($error) ?></div><?php endif; ?>
<?php if ($sukses !== ""): ?><div class="ok"><?= aman($sukses) ?></div><?php endif; ?>

<div class="card">
<h3><?= $edit ? "Edit Mahasiswa" : "Tambah Mahasiswa" ?></h3>
<form method="post">
 <input type="hidden" name="aksi" value="<?= $edit ? 'ubah' : 'tambah' ?>">

 <label>NIM</label>
 <?php if ($edit): ?>
   <input type="hidden" name="nim" value="<?= aman($edit->getNim()) ?>">
   <input type="text" value="<?= aman($edit->getNim()) ?>" readonly>
 <?php else: ?>
   <input type="text" name="nim">
 <?php endif; ?>

 <label>Nama</label>
 <input type="text" name="nama" value="<?= aman($edit?->getNama() ?? '') ?>">
 <label>Fakultas</label>
 <input type="text" name="fakultas" value="<?= aman($edit?->getFakultas() ?? '') ?>">
 <label>Prodi</label>
 <input type="text" name="prodi" value="<?= aman($edit?->getProdi() ?? '') ?>">
 <label>Asal Sekolah</label>
 <input type="text" name="asalSekolah" value="<?= aman($edit?->getAsalSekolah() ?? '') ?>">
 <label>Semester</label>
 <input type="text" name="semester" value="<?= aman($edit?->getSemester() ?? '') ?>">
 <label>Hobi</label>
 <input type="text" name="hobi" value="<?= aman($edit?->getHobi() ?? '') ?>">
 <label>Agama</label>
 <select name="agama">
  <option value="">-- pilih --</option>
  <?php foreach (Mahasiswa::AGAMA_VALID as $a): ?>
   <option value="<?= aman($a) ?>" <?= ($edit?->getAgama() === $a) ? 'selected' : '' ?>><?= aman($a) ?></option>
  <?php endforeach; ?>
 </select>
 <br><br>
 <div class="aksi-form">
  <button type="submit"><?= $edit ? "Simpan Perubahan" : "Tambah" ?></button>
  <?php if ($edit): ?><a href="index.php">Batal</a><?php endif; ?>
 </div>
</form>
</div>

<h3>Daftar Mahasiswa</h3>
<div class="tabel-wrap">
<table>
 <tr><th>NIM</th><th>Nama</th><th>Fakultas</th><th>Prodi</th><th>Asal Sekolah</th><th>Semester</th><th>Hobi</th><th>Agama</th><th>Aksi</th></tr>
 <?php foreach ($repo->semua() as $m): ?>
 <tr>
  <td><?= aman($m->getNim()) ?></td>
  <td><?= aman($m->getNama()) ?></td>
  <td><?= aman($m->getFakultas()) ?></td>
  <td><?= aman($m->getProdi()) ?></td>
  <td><?= aman($m->getAsalSekolah()) ?></td>
  <td><?= aman($m->getSemester()) ?></td>
  <td><?= aman($m->getHobi()) ?></td>
  <td><?= aman($m->getAgama()) ?></td>
  <td>
   <a href="?edit=<?= urlencode($m->getNim()) ?>">Edit</a>
   <form method="post" style="display:inline" onsubmit="return confirm('Hapus data ini?')">
    <input type="hidden" name="aksi" value="hapus">
    <input type="hidden" name="nim" value="<?= aman($m->getNim()) ?>">
    <button type="submit">Hapus</button>
   </form>
  </td>
 </tr>
 <?php endforeach; ?>
</table>
</div>
</div>
</body>
</html>
