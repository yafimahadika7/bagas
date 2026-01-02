<?php
$host       = "localhost";
$user       = "root";
$pass       = "janganangel";
$db         = "data_karyawan";

$koneksi = mysqli_connect($host,$user,$pass,$db);
if(!$koneksi){
    die("Tidak bisa terkoneksi ke database");
}

$id_karyawan = "";
$nama_karyawan = "";
$jabatan = "";
$tanggal_masuk = "";
$tanggal_habis_kontrak = "";
$alamat = "";
$sukses = "";
$error = "";

if(isset($_GET['op'])){
    $op = $_GET['op'];
}else{
    $op = "";
}
if($op == 'delete'){
$id_karyawan = $_GET['id_karyawan'];
$sql1 = "DELETE FROM karyawan WHERE id_karyawan='$id_karyawan'";
$q1 = mysqli_query($koneksi,$sql1);
if($q1){
$sukses = "Berhasil hapus data";
}else{
$error = "Gagal melakukan delete data";
}

}

if($op == 'edit'){
$id_karyawan = $_GET['id_karyawan'];
$sql = "SELECT * FROM karyawan WHERE id_karyawan='$id_karyawan'";
$q1 = mysqli_query($koneksi,$sql);
$r1 = mysqli_fetch_array($q1);
$id_karyawan = $r1['id_karyawan'];
$nama_karyawan = $r1['nama_karyawan'];
$jabatan = $r1['jabatan'];
$tanggal_masuk = $r1['tanggal_masuk'];
$tanggal_habis_kontrak = $r1['tanggal_habis_kontrak'];
$alamat = $r1['alamat'];
}

if(isset($_POST['simpan'])){
    $id_karyawan = $_POST['id_karyawan'];
    $nama_karyawan = $_POST['nama_karyawan'];
    $jabatan = $_POST['jabatan'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $tanggal_habis_kontrak = $_POST['tanggal_habis_kontrak'];
    $alamat = $_POST['alamat'];

    if($id_karyawan && $nama_karyawan && $jabatan && $tanggal_masuk && $tanggal_habis_kontrak && $alamat){
        if($op == 'edit'){
            $sql = "UPDATE karyawan SET 
                    id_karyawan='$id_karyawan',
                    nama_karyawan='$nama_karyawan',
                    jabatan='$jabatan',
                    tanggal_masuk='$tanggal_masuk',
                    tanggal_habis_kontrak='$tanggal_habis_kontrak',
                    alamat='$alamat'
                    WHERE id_karyawan='$id_karyawan'";
            mysqli_query($koneksi,$sql);
            $sukses = "Data berhasil diupdate";
        } else {
            $sql = "INSERT INTO karyawan 
                    (id_karyawan,nama_karyawan,jabatan,tanggal_masuk,tanggal_habis_kontrak,alamat)
                    VALUES 
                    ('$id_karyawan','$nama_karyawan','$jabatan','$tanggal_masuk','$tanggal_habis_kontrak','$alamat')";
            mysqli_query($koneksi,$sql);
            $sukses = "Data berhasil ditambahkan";
        }
    } else {
        $error = "Semua data wajib diisi";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Karyawan</title>
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
crossorigin="anonymous">
<style>
body{
  background: #74ebd5;
}
.mx-auto{width:900px}
.card{margin-top:20px}
</style>
</head>

<body>
<div class="mx-auto">

<div class="card">
<div class="card-header">
    Form Data Karyawan
</div>
<div class="card-body">

<?php if($error){ ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php header("refresh:2;url=karyawan.php");
        } 
?>

<?php if($sukses){ ?>
<div class="alert alert-success"><?= $sukses ?></div>
<?php header("refresh:2;url=karyawan.php");
        } 
?>

<form method="POST">
<input class="form-control mb-2" name="id_karyawan" placeholder="ID Karyawan" value="<?= $id_karyawan ?>">
<input class="form-control mb-2" name="nama_karyawan" placeholder="Nama Karyawan" value="<?= $nama_karyawan ?>">
<input class="form-control mb-2" name="jabatan" placeholder="Jabatan" value="<?= $jabatan ?>">
<label>Tanggal Masuk</label>
<input type="date" class="form-control mb-2" name="tanggal_masuk" value="<?= $tanggal_masuk ?>">
<label>Tanggal Habis Kontrak</label>
<input type="date" class="form-control mb-2" name="tanggal_habis_kontrak" value="<?= $tanggal_habis_kontrak ?>">
<textarea class="form-control mb-3" name="alamat" placeholder="Alamat"><?= $alamat ?></textarea>
<input type="submit" name="simpan" class="btn btn-primary" value="Simpan Data">
</form>

</div>
</div>

<div class="card">
<div class="card-header bg-secondary text-white">Data Karyawan</div>
<div class="card-body">
<table class="table table-bordered">
<tr>
<th>#</th>
<th>ID</th>
<th>Nama</th>
<th>Jabatan</th>
<th>Tgl Masuk</th>
<th>Tgl Habis</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php
$q = mysqli_query($koneksi,"SELECT * FROM karyawan ORDER BY id_karyawan DESC");
$no=1;
while($r=mysqli_fetch_array($q)){
$status = (date('Y-m-d') > $r['tanggal_habis_kontrak']) 
? "<span class='text-danger'>Kontrak Habis</span>" 
: "<span class='text-success'>Aktif</span>";
?>

<tr>
<td><?= $no++ ?></td>
<td><?= $r['id_karyawan'] ?></td>
<td><?= $r['nama_karyawan'] ?></td>
<td><?= $r['jabatan'] ?></td>
<td><?= $r['tanggal_masuk'] ?></td>
<td><?= $r['tanggal_habis_kontrak'] ?></td>
<td><?= $status ?></td>
<td>
<a href="?op=edit&id_karyawan=<?= $r['id_karyawan'] ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="?op=delete&id_karyawan=<?= $r['id_karyawan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')">Delete</a>
</td>
</tr>

<?php } ?>
</table>
</div>
</div>

</div>
</body>
</html>
