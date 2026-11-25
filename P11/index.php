
<?php

// Data awal mahasiswa
$mahasiswa = [
    [
        'nim' => '2310001',
        'nama' => 'Alya',
        'email' => 'alya@gmail.com',
        'tugas' => 85,
        'uts' => 80,
        'uas' => 90
    ],
    [
        'nim' => '2310002',
        'nama' => 'Bima',
        'email' => 'bima@gmail.com',
        'tugas' => 70,
        'uts' => 75,
        'uas' => 68
    ],
    [
        'nim' => '2310003',
        'nama' => 'Citra',
        'email' => 'citra@gmail.com',
        'tugas' => 92,
        'uts' => 88,
        'uas' => 95
    ],
    [
        'nim' => '2310004',
        'nama' => 'Danu',
        'email' => 'danu@gmail.com',
        'tugas' => 60,
        'uts' => 65,
        'uas' => 70
    ]
];

$pesan = "";

// Fungsi menghitung nilai
function hitungNilai($tugas, $uts, $uas) {
    return ($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4);
}

// Fungsi predikat
function predikat($nilai) {
    if ($nilai >= 85) {
        return "A";
    } elseif ($nilai >= 75) {
        return "B";
    } elseif ($nilai >= 60) {
        return "C";
    } else {
        return "D";
    }
}

// Proses form POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nim = trim($_POST['nim'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $tugas = $_POST['tugas'] ?? '';
    $uts = $_POST['uts'] ?? '';
    $uas = $_POST['uas'] ?? '';
    $email = trim($_POST['email'] ?? '');

    // Validasi PHP
    if (
        $nim == "" ||
        $nama == "" ||
        $email == "" ||
        $tugas === "" ||
        $uts === "" ||
        $uas === ""
    ) {
        $pesan = "Semua data wajib diisi!";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $pesan = "Format Gmail tidak valid!";

    } elseif (
        !is_numeric($tugas) ||
        !is_numeric($uts) ||
        !is_numeric($uas)
    ) {
        $pesan = "Nilai harus berupa angka!";

    } elseif (
        $tugas < 0 || $tugas > 100 ||
        $uts < 0 || $uts > 100 ||
        $uas < 0 || $uas > 100
    ) {
        $pesan = "Nilai harus berada antara 0 sampai 100!";

    } else {

        // Cek NIM duplikat
        $duplikat = false;

        foreach ($mahasiswa as $mhs) {
            if ($mhs['nim'] == $nim) {
                $duplikat = true;
                break;
            }
        }

        if ($duplikat) {
            $pesan = "NIM sudah terdaftar!";

        } else {

            // Tambahkan data mahasiswa
            $mahasiswa[] = [
                'nim' => $nim,
                'nama' => $nama,
                'email' => $email,
                'tugas' => (float)$tugas,
                'uts' => (float)$uts,
                'uas' => (float)$uas
            ];

            $pesan = "Data mahasiswa berhasil ditambahkan!";
        }
    }
}

// Proses pencarian GET
$keyword = trim($_GET['cari'] ?? '');

$hasil = [];

foreach ($mahasiswa as $mhs) {
    if (
        $keyword == "" ||
        stripos($mhs['nim'], $keyword) !== false ||
        stripos($mhs['nama'], $keyword) !== false ||
        stripos($mhs['email'], $keyword) !== false
    ) {
        $hasil[] = $mhs;
    }
}

// Fungsi keamanan output
function aman($data) {
    return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Simulasi Data Mahasiswa</title>
</head>

<body>

<!-- 1. PENCARIAN MAHASISWA -->
<h3>1. Pencarian Mahasiswa (GET)</h3>

<form method="GET" action="">

    Cari berdasarkan NIM, nama, atau Gmail:

    <input
        type="text"
        name="cari"
        placeholder="Masukkan NIM, nama, atau Gmail"
        value="<?= aman($keyword) ?>"
    >

    <button type="submit">Cari</button>

    <a href="index.php">Reset pencarian</a>

</form>

<hr>

<!-- 2. DAFTAR MAHASISWA -->
<h3>2. Daftar Mahasiswa</h3>

<table border="1" cellpadding="5" cellspacing="0">

    <tr>
        <th>NIM</th>
        <th>Nama</th>
        <th>Gmail</th>
        <th>Tugas</th>
        <th>UTS</th>
        <th>UAS</th>
        <th>Nilai Akhir</th>
        <th>Predikat</th>
        <th>Status</th>
    </tr>

    <?php if (count($hasil) > 0): ?>

        <?php foreach ($hasil as $mhs): ?>

            <?php
                $nilai = hitungNilai(
                    $mhs['tugas'],
                    $mhs['uts'],
                    $mhs['uas']
                );

                $grade = predikat($nilai);

                $status = ($nilai >= 70)
                    ? "Lulus"
                    : "Belum Lulus";
            ?>

            <tr>
                <td><?= aman($mhs['nim']) ?></td>
                <td><?= aman($mhs['nama']) ?></td>
                <td><?= aman($mhs['email']) ?></td>
                <td><?= aman($mhs['tugas']) ?></td>
                <td><?= aman($mhs['uts']) ?></td>
                <td><?= aman($mhs['uas']) ?></td>
                <td><?= number_format($nilai, 2, '.', '') ?></td>
                <td><?= aman($grade) ?></td>
                <td><?= aman($status) ?></td>
            </tr>

        <?php endforeach; ?>

    <?php else: ?>

        <tr>
            <td colspan="9">
                Data mahasiswa tidak ditemukan.
            </td>
        </tr>

    <?php endif; ?>

</table>

<hr>

<!-- 3. FORM INPUT MAHASISWA -->
<h3>3. Form Input Mahasiswa (POST)</h3>

<?php if ($pesan != ""): ?>

    <p>
        <b><?= aman($pesan) ?></b>
    </p>

<?php endif; ?>

<form method="POST" action="" onsubmit="return cekForm()">

    NIM:<br>
    <input type="text" name="nim" id="nim">
    <br><br>

    Nama mahasiswa:<br>
    <input type="text" name="nama" id="nama">
    <br><br>

    Nilai tugas:<br>
    <input type="number" name="tugas" id="tugas">
    <br><br>

    Nilai UTS:<br>
    <input type="number" name="uts" id="uts">
    <br><br>

    Nilai UAS:<br>
    <input type="number" name="uas" id="uas">
    <br><br>

    Gmail:<br>
    <input type="text" name="email" id="email">
    <br><br>

    <button type="submit">
        Proses Nilai
    </button>

    <button type="reset" onclick="resetNotif()">
        Reset form
    </button>

</form>

<hr>

<p>Simulasi PHP Form Processing dan Validasi Data - P11</p>

<!-- 4. VALIDASI NOTIFIKASI JAVASCRIPT -->
<script>

function cekForm() {

    let kolom = [
        "nim",
        "nama",
        "tugas",
        "uts",
        "uas",
        "email"
    ];

    let kosong = false;

    kolom.forEach(function(id) {

        let input = document.getElementById(id);

        if (input.value.trim() === "") {

            input.placeholder = "Harus diisi";
            kosong = true;

        } else {

            input.placeholder = "";

        }

    });

    if (kosong) {

        let pertama = kolom.find(function(id) {
            return document.getElementById(id).value.trim() === "";
        });

        document.getElementById(pertama).focus();

        return false;
    }

    return true;
}

// Menghapus notifikasi saat tombol reset diklik
function resetNotif() {

    let kolom = [
        "nim",
        "nama",
        "tugas",
        "uts",
        "uas",
        "email"
    ];

    kolom.forEach(function(id) {
        document.getElementById(id).placeholder = "";
    });

}

</script>

</body>
</html>
