<!DOCTYPE html>
<html>
<head>
    <title>Unggah File Sederhana</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        .preview-container {
            margin: 20px 0;
            text-align: center;
        }
        .preview-image {
            max-width: 300px;
            max-height: 200px;
            border: 1px solid #ddd;
            display: none;
        }
        .file-list {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .file-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .file-item a {
            color: #0066cc;
            text-decoration: none;
        }
        .file-item a:hover {
            text-decoration: underline;
        }
        .file-actions {
            display: flex;
            gap: 10px;
        }
        .file-actions a {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.8em;
            text-decoration: none;
        }
        .download-btn {
            background-color: #4CAF50;
            color: white;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
        }
        .no-files {
            color: #666;
            font-style: italic;
        }
        .upload-form {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .upload-form input[type="file"] {
            margin: 10px 0;
        }
        .upload-form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .upload-form input[type="submit"]:hover {
            background-color: #45a049;
        }
        .file-info {
            margin: 10px 0;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<h2>Unggah File</h2>
<div class="upload-form">
    <form action="upload.php" method="post" enctype="multipart/form-data">
        Pilih file untuk diunggah:
        <input type="file" name="fileToUpload" id="fileToUpload" onchange="previewFile()" required>
        <div class="preview-container">
            <img id="preview" class="preview-image" alt="Preview gambar akan muncul di sini">
            <div id="fileInfo"></div>
        </div>
        <input type="submit" value="Unggah File" name="submit">
    </form>
</div>

<div class="file-list">
    <h3>File yang Telah Diunggah</h3>
    <?php
    // List uploaded files
    $upload_dir = 'uploads/';
    if (is_dir($upload_dir)) {
        $files = scandir($upload_dir);
        $file_count = 0;
        
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $file_url = $upload_dir . $file;
                $file_size = filesize($upload_dir . $file);
                $file_date = date("d-m-Y H:i:s", filemtime($upload_dir . $file));
                $file_type = mime_content_type($upload_dir . $file);
                
                echo '<div class="file-item">';
                echo '<div>';
                echo '<a href="' . $file_url . '" target="_blank">' . htmlspecialchars($file) . '</a>';
                echo '<div class="file-info">';
                echo '<strong>Ukuran:</strong> ' . formatFileSize($file_size) . ' | ';
                echo '<strong>Tipe:</strong> ' . $file_type . ' | ';
                echo '<strong>Tanggal:</strong> ' . $file_date;
                echo '</div>';
                echo '</div>';
                
                echo '<div class="file-actions">';
                echo '<a href="' . $file_url . '" class="download-btn" download>Unduh</a>';
                echo '<a href="upload.php?delete=' . urlencode($file) . '" class="delete-btn" onclick="return confirm(\'Yakin ingin menghapus file ini?\')">Hapus</a>';
                echo '</div>';
                echo '</div>';
                
                $file_count++;
            }
        }
        
        if ($file_count === 0) {
            echo '<p class="no-files">Belum ada file yang diunggah.</p>';
        }
    } else {
        echo '<p class="no-files">Direktori upload tidak ditemukan.</p>';
    }
    
    function formatFileSize($bytes) {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
    ?>
</div>

<script>
function previewFile() {
    var preview = document.getElementById('preview');
    var fileInfo = document.getElementById('fileInfo');
    var fileInput = document.getElementById('fileToUpload');
    var file = fileInput.files[0];
    
    // Reset preview
    preview.style.display = 'none';
    fileInfo.innerHTML = '';
    
    if (file) {
        // Show file info
        fileInfo.innerHTML = 
            '<p><strong>Nama File:</strong> ' + file.name + '</p>' +
            '<p><strong>Ukuran:</strong> ' + formatFileSize(file.size) + '</p>' +
            '<p><strong>Tipe:</strong> ' + (file.type || 'Tidak diketahui') + '</p>';
        
        // Preview image if it's an image file
        if (file.type.match('image.*')) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            
            reader.readAsDataURL(file);
        }
    }
}

function formatFileSize(bytes) {
    if (bytes >= 1073741824) {
        return (bytes / 1073741824).toFixed(2) + ' GB';
    } else if (bytes >= 1048576) {
        return (bytes / 1048576).toFixed(2) + ' MB';
    } else if (bytes >= 1024) {
        return (bytes / 1024).toFixed(2) + ' KB';
    } else {
        return bytes + ' bytes';
    }
}
</script>

</body>
</html>