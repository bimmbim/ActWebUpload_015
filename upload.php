<?php

$target_dir = "uploads/";

$deleteMessage = "";

if(isset($_GET['delete'])){

    $hapus = $target_dir . basename($_GET['delete']);

    if(file_exists($hapus)){

        unlink($hapus);

        $deleteMessage = "File berhasil dihapus.";
    }
}

if(isset($_POST['submit'])){

    $namaAsli = $_FILES["fileToUpload"]["name"];

    $namaFile = preg_replace("/[^a-zA-Z0-9.\-_]/", "_", $namaAsli);

    $target_file = $target_dir . $namaFile;

    $ukuran = $_FILES["fileToUpload"]["size"];

    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed = ["jpg", "jpeg", "png", "gif"];

    $uploadOk = 1;

    if(!in_array($fileType, $allowed)){

        $uploadOk = 0;

        $error = "Format file tidak didukung.";
    }

    if($ukuran > 500000){

        $uploadOk = 0;

        $error = "Ukuran file terlalu besar.";
    }

    $counter = 1;

    while(file_exists($target_file)){

        $namaTanpaExt = pathinfo($namaFile, PATHINFO_FILENAME);

        $ext = pathinfo($namaFile, PATHINFO_EXTENSION);

        $namaBaru = $namaTanpaExt . "_" . $counter . "." . $ext;

        $target_file = $target_dir . $namaBaru;

        $counter++;
    }

    if($uploadOk == 1){

        if(move_uploaded_file(
            $_FILES["fileToUpload"]["tmp_name"],
            $target_file
        )){

            $success = true;

            $previewFile = $target_file;

            $namaTampil = basename($target_file);

        } else {

            $error = "Upload gagal.";
        }
    }
}
?>

<!doctype html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Upload Result</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{
            background:#0b0b0b;
            color:white;
            padding:40px;
        }

        .container{
            width:900px;
            margin:auto;
        }

        .card{
            background:#141414;
            border:1px solid #242424;
            border-radius:18px;
            padding:30px;
            margin-bottom:30px;
        }

        h1{
            margin-bottom:10px;
            font-size:34px;
        }

        .success{
            color:#4ade80;
            margin-bottom:20px;
            font-size:16px;
        }

        .delete-message{
            background:#2a0f12;
            border:1px solid #5f1d24;
            color:#f87171;
            padding:16px;
            border-radius:14px;
            margin-bottom:20px;
            font-size:15px;
        }

        .error{
            color:#f87171;
            margin-bottom:20px;
            font-size:16px;
        }

        .preview{
            margin-top:20px;
        }

        .preview img{
            width:300px;
            border-radius:14px;
            border:1px solid #2f2f2f;
        }

        .info{
            margin-top:12px;
            color:#a1a1aa;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:#141414;
            border-radius:18px;
            overflow:hidden;
        }

        th{
            background:#1c1c1c;
        }

        th, td{
            padding:18px;
            text-align:center;
            border-bottom:1px solid #242424;
        }

        tr:hover{
            background:#1a1a1a;
        }

        .btn{
            display:inline-block;
            text-decoration:none;
            padding:10px 16px;
            border-radius:10px;
            margin:0 5px;
            font-size:14px;
            transition:0.2s;
        }

        .download{
            background:white;
            color:black;
        }

        .download:hover{
            opacity:0.85;
        }

        .delete{
            background:#202020;
            color:white;
            border:1px solid #343434;
        }

        .delete:hover{
            background:#2a2a2a;
        }

        .back{
            display:inline-block;
            margin-top:30px;
            background:white;
            color:black;
            padding:12px 18px;
            text-decoration:none;
            border-radius:12px;
            font-weight:bold;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>Upload Result</h1>

        <?php if($deleteMessage != ""){ ?>

            <div class="delete-message">
                <?php echo $deleteMessage; ?>
            </div>

        <?php } ?>

        <?php if(isset($success)){ ?>

            <div class="success">
                File berhasil diupload.
            </div>

            <div class="preview">

                <img src="<?php echo $previewFile; ?>">

            </div>

            <div class="info">
                Nama file:
                <?php echo $namaTampil; ?>
            </div>

            <div class="info">
                Ukuran:
                <?php echo round($ukuran / 1024, 2); ?> KB
            </div>

            <div class="info">
                Waktu upload:
                <?php echo date("d M Y - H:i"); ?>
            </div>

        <?php } ?>

        <?php if(isset($error)){ ?>

            <div class="error">
                <?php echo $error; ?>
            </div>

        <?php } ?>

    </div>

    <table>

        <tr>
            <th>No</th>
            <th>Nama File</th>
            <th>Aksi</th>
        </tr>

        <?php

        $files = scandir($target_dir);

        $no = 1;

        foreach($files as $file){

            if($file != "." && $file != ".."){

                echo "
                <tr>

                    <td>$no</td>

                    <td>$file</td>

                    <td>

                        <a class='btn download'
                           href='uploads/$file'
                           download>

                           Download

                        </a>

                        <a class='btn delete'
                           href='upload.php?delete=$file'>

                           Delete

                        </a>

                    </td>

                </tr>
                ";

                $no++;
            }
        }

        ?>

    </table>

    <a href='index.html' class='back'>
        Upload Lagi
    </a>

</div>

</body>
</html>