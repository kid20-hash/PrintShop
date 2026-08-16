<?php
require "includes/auth.php";
require "config/database.php";
require "vendor/autoload.php";
require "logger.php";


$message = "";

if (isset($_POST['upload'])) {

    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {

       $allowed = [
    'pdf',
    'doc',
    'docx',
    'ppt',
    'pptx',
    'wps',
    'jpg',
    'jpeg',
    'png',
    'gif',
    'webp'
];

        $filename = $_FILES['document']['name'];
        $tmp = $_FILES['document']['tmp_name'];
        $size = $_FILES['document']['size'];

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowed)) {

            $message = "Invalid file type.";

        } elseif ($size > 20 * 1024 * 1024) {

            $message = "Maximum file size is 20MB.";

        } else {

            if (!is_dir("uploads")) {
                mkdir("uploads", 0777, true);
            }

            $newName = uniqid() . "." . $extension;
            $destination = "uploads/" . $newName;

            if (move_uploaded_file($tmp, $destination)) {

               

 $stmt = $pdo->prepare("
    INSERT INTO orders
    (
        user_id,
        queue_number,
        filename,
        filepath,
        copies,
        paper_size,
        print_color,
        print_side,
        total_pages
    )
    VALUES
    (?,?,?,?,?,?,?,?,?)
");

$stmt->execute([
    $_SESSION['user_id'],
    '', // temporary
    $filename,
    $newName,
    $_POST['copies'],
    $_POST['paper_size'],
    $_POST['print_color'],
    $_POST['print_side'],
    0
]);

$orderId = $pdo->lastInsertId();
$queue = "Q" . str_pad($orderId, 3, "0", STR_PAD_LEFT);

$update = $pdo->prepare("UPDATE orders SET queue_number=? WHERE id=?");
$update->execute([$queue, $orderId]);

$message = "Uploaded successfully! Queue No: <strong>$queue</strong>";

                if ($message == "") {
                    $message = "Document uploaded successfully.";
                }

            } else {

                $message = "Failed to upload file.";

            }

        }

    } else {

        $message = "Please choose a document.";

    }

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Upload Document</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f8f9fa;
}

/* ===== MOBILE VIEW ONLY ===== */
@media (max-width: 768px) {

    .container {
        padding-left: 12px;
        padding-right: 12px;
    }

    .card {
        margin-top: 15px;
        border-radius: 12px;
    }

    .card-header h3 {
        font-size: 20px;
        text-align: center;
    }

    .form-control,
    .form-select {
        font-size: 16px;
        padding: 10px;
    }

    button.btn,
    a.btn {
        width: 100%;
        margin-top: 10px;
    }

    .card-body {
        padding: 18px;
    }
}
</style>

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header">



<h3>Upload Document</h3>

</div>

<div class="card-body">

<?php
if($message!=""){
echo "<div class='alert alert-info'>$message</div>";
}
?>

<form
method="POST"
enctype="multipart/form-data">

<div class="mb-3">

<label>Select Document</label>

<input
type="file"
name="document"
id="document"
class="form-control"
accept=".pdf,.doc,.docx,.ppt,.pptx,.wps,.jpg,.jpeg,.png,.gif,.webp"
required>



</div>

<div class="mb-3">

<label>Copies</label>

<input
type="number"
name="copies"
class="form-control"
value="1"
min="1"
required>

</div>

<div class="mb-3">

<label>Paper Size</label>

<select
name="paper_size"
class="form-select">

<option>A4</option>
<option>Long</option>
<option>Short</option>

</select>

</div>

<div class="mb-3">

<label>Print Color</label>

<select
name="print_color"
class="form-select">

<option>Black & White</option>
<option>Colored</option>

</select>

</div>

<div class="mb-3">

<label>Print Side</label>

<select
name="print_side"
class="form-select">

<option>Single</option>
<option>Double</option>

</select>

</div>

<button
class="btn btn-primary"
name="upload">

Upload

</button>

<a
href="dashboard.php"
class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

</div>


<script>
document.addEventListener("DOMContentLoaded", function () {

    pdfjsLib.GlobalWorkerOptions.workerSrc =
        "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

    const fileInput = document.getElementById("document");
    const pageCount = document.getElementById("pageCount");

    fileInput.addEventListener("change", function () {

        pageCount.innerHTML = "";

        const file = this.files[0];

        if (!file) return;

        if (file.type !== "application/pdf") {
            pageCount.innerHTML = "Page counting is only available for PDF files.";
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {

            const typedArray = new Uint8Array(e.target.result);

            pdfjsLib.getDocument({ data: typedArray }).promise
                .then(function (pdf) {
                    pageCount.innerHTML = "<strong>Total Pages: " + pdf.numPages + "</strong>";
                })
                .catch(function (err) {
                    console.log(err);
                    pageCount.innerHTML = "Unable to read PDF.";
                });

        };

        reader.readAsArrayBuffer(file);

    });

});
</script>
</body>

</html>