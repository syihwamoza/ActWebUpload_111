<?php
$upload_dir = 'uploads/';

// Buat direktori uploads jika belum ada
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Proses upload file
if (isset($_POST['submit'])) {
    $target_file = $upload_dir . basename($_FILES['fileToUpload']['name']);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file sudah ada
    if (file_exists($target_file)) {
        echo "<script>alert('Maaf, file sudah ada.');</script>";
        $uploadOk = 0;
    }

    // Batasi ukuran file (5MB)
    if ($_FILES['fileToUpload']['size'] > 5000000) {
        echo "<script>alert('Maaf, file terlalu besar. Maksimal 5MB.');</script>";
        $uploadOk = 0;
    }

    // Izinkan hanya format file tertentu
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'mp3', 'mp4'];
    if (!in_array($fileType, $allowedTypes)) {
        echo "<script>alert('Maaf, hanya file JPG, JPEG, PNG, GIF, PDF, DOC, TXT, MP3, dan MP4 yang diizinkan.');</script>";
        $uploadOk = 0;
    }

    // Cek jika $uploadOk bernilai 0 karena ada error
    if ($uploadOk == 0) {
        echo "<script>alert('Maaf, file Anda tidak terunggah.');</script>";
    } else {
        // Jika semua kondisi terpenuhi, coba upload file
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
            echo "<script>alert('File ". htmlspecialchars(basename($_FILES['fileToUpload']['name'])). " berhasil diunggah.');</script>";
        } else {
            echo "<script>alert('Maaf, terjadi kesalahan saat mengunggah file.');</script>";
        }
    }
}

// Proses hapus file
if (isset($_GET['delete'])) {
    $fileToDelete = $upload_dir . $_GET['delete'];
    if (file_exists($fileToDelete)) {
        if (unlink($fileToDelete)) {
            echo "<script>alert('File berhasil dihapus.');</script>";
            echo "<script>window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus file.');</script>";
        }
    } else {
        echo "<script>alert('File tidak ditemukan.');</script>";
    }
}

header("Location: index.php");
?>