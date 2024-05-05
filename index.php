<?php
session_start();
include 'config.php';
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['upload_file'])) {
    if (isset($_FILES['file'])) {
        $fileData = $_FILES['file'];
        $fileName = basename($fileData['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'txt');

        if (in_array($fileExt, $allowedExtensions)) {
            $newFileName = $fileName;
            $uploadPath = 'uploads/' . $newFileName;

            if (move_uploaded_file($fileData['tmp_name'], $uploadPath)) {
                $insertQuery = "INSERT INTO uploaded_images (file_name, file_path) VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $insertQuery);
                mysqli_stmt_bind_param($stmt, 'ss', $fileName, $uploadPath);

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['msg'] = "File uploaded successfully!";
                } else {
                    $_SESSION['msg'] = "Database error: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt);
            } else {
                $_SESSION['msg'] = "Failed to move file.";
            }
        } else {
            $_SESSION['msg'] = "Invalid file type.";
        }
    } else {
        $_SESSION['msg'] = "Error uploading file.";
    }

    header("Location: index.php");
    exit();
}
$sql = "SELECT COUNT(*) as total_files FROM `uploaded_images`";
$result = $conn->query($sql);
$totalFiles = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalFiles = $row['total_files'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Uploader</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>



<body>
    <div class="container mt-4">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Take action</strong>
            <?php if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
            } else {
                echo "Drag and Drop files or Choose manually";
            } ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Total Files</div>
                    <div class="card-body">
                        <h4 class="card-title">
                            <?php echo $totalFiles; ?>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Card 2: URL Shortener -->
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">URL Shortener</div>
                    <div class="card-body">
                        <h4 class="card-title"><a class="text-white" href="https://tiny.desirestore.online">URL
                                Shortener</a></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">File Uploader</h5>
                </div>
                <div class="card-body">

                    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" class="dropzone" id="fileUploader"
                        method="post" enctype="multipart/form-data">
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                        <button type="submit" name="upload_file" class="btn submit btn-primary mt-4">Upload
                            Files</button>
                    </form>

                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="card">
                <div class="card-header">
                    URL List
                </div>
                <div class="card-body">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>URL</th>
                                <th colspan="2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM `uploaded_images` ";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td><a class='file-url' href='{$domain}{$row['file_path']}'>{$row['file_name']}</a></td>
                            <td>
                                <button class='btn btn-primary btn-sm copy-btn' data-clipboard-text='{$domain}{$row['file_path']}'><i class='fa-regular fa-copy'></i></button>
                            </td>
                    <td>   <button type='button' onclick='showModel()' class='btn btn-danger btn-sm'><i class='fa-solid fa-trash'></i></button>                            </td>

                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No uploaded files</td></tr>";
                            }
                            ; ?>
                        </tbody>
                    </table>

                    <script> function showModel() {
                            alert('You can not Delete This');
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script src="https://kit.fontawesome.com/3a634ddbe6.js" crossorigin="anonymous"></script>
    <script>
        document.querySelector("body").insertAdjacentHTML(
            "beforeend",
            `
        <div class="loader">
        <div class="spinner center">
    <div class="spinner-blade"></div>
    <div class="spinner-blade"></div>
    <div class="spinner-blade"></div>
    <div class="spinner-blade"></div>
    <div class="spinner-blade"></div>
    <div class="spinner-blade"></div>
    <div class="spinner-blade"></div>
    <div class="spinner-blade"></div>
    <div class="spinner-blade"></div>
    <div class="spinner-blade"></div>
    <div class="spinner-blade"></div>
    <div class="spinner-blade"></div>
</div>
        </div>
        `
        );

        window.addEventListener("load", function () {
            const loader = document.querySelector(".loader");
            if (loader) {
                loader.remove();
            }
        });</script>
    <script>
        var myDropzone = new Dropzone("#fileUploader", {
            paramName: "file",
            maxFilesize: 1000,
            acceptedFiles: ".jpg, .jpeg, .png, .txt, .pdf, .zip, .rar",
            parallelUploads: 5,
            addRemoveLinks: true,
            init: function () {
                var submitButton = document.querySelector('.submit');

                this.on("addedfile", function () {
                    submitButton.style.display = "block";
                    this.processQueue();
                });

                this.on("removedfile", function () {
                    if (this.files.length === 0) {
                        submitButton.style.display = "none";
                    }
                });

                this.on("success", function (file, response) {
                    console.log(response);
                });

                this.on("error", function (file, errorMessage) {
                    console.error(errorMessage);
                });
            }
        });

        var clipboard = new ClipboardJS('.copy-btn');
        clipboard.on('success', function (e) {
            e.trigger.title = 'Copied!';
            alert("Copied");
            e.clearSelection();
        });
        clipboard.on('error', function (e) {
            e.trigger.title = 'Press Ctrl+C or Command+C to copy';
        });
    </script>
</body>

</html>