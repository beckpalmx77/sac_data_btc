<?php
session_start();
error_reporting(0);
include('includes/Header.php');
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    ?>

    <!doctype html>
    <html>
    <head lang="th">
        <meta charset="utf-8">
        <title>แสดงรูปภาพ</title>
        <script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="js/alertify/alertify.js"></script>
        <link rel="stylesheet" href="js/alertify/css/alertify.css">
        <style>
            .preview-img {
                max-width: 100px;
                max-height: 100px;
                margin: 10px;
            }
            .delete-btn {
                display: block;
                margin: 10px auto;
            }
        </style>
    </head>
    <body>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container-fluid" id="container-wrapper">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"><span id="title"></span></h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo $_SESSION['dashboard_page'] ?>">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><span id="sub_menu"></span></li>
                    </ol>
                </div>

                <div class="col-md-8">
                    <div class="form-group row" id="preview"></div>
                    <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>"/>
                </div>
            </div>
        </div>
        <div id="err"></div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            //let id = $('#id').val();
            let id = 1012;
            loadImages(id);
        });

        function loadImages(id) {
            $.ajax({
                url: 'upload_fetch_images.php',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function (data) {
                    let preview = $('#preview');
                    preview.empty(); // Clear existing previews
                    $.each(data, function (key, value) {
                        if (value) {
                            let imgDiv = $('<div class="col-sm-2"></div>');
                            let img;
                            let fileExtension = value.split('.').pop().toLowerCase();
                            let filePath = 'uploads/' + value;
                            if (fileExtension === 'jpg' || fileExtension === 'jpeg' || fileExtension === 'png') {
                                img = $('<img>').attr('src', filePath).addClass('preview-img').attr('id', key);
                            } else {
                                img = $('<img>').attr('src', 'img/icon/file.jpg').addClass('preview-img').attr('id', key);
                            }
                            let fileLink = $('<a>').attr('href', filePath).attr('target', '_blank').append(img);
                            let deleteBtn = $('<button class="delete-btn">ลบ</button>').attr('onclick', 'deleteFile("' + key + '", "' + id + '")');
                            imgDiv.append(fileLink).append(deleteBtn);
                            preview.append(imgDiv);
                        }
                    });
                }
            });
        }

        function deleteFile(key, id) {
            $.ajax({
                url: 'upload_delete_image.php',
                type: 'POST',
                data: { key: key, id: id },
                success: function (data) {
                    loadImages(id);
                    alertify.success("รูปภาพถูกลบเรียบร้อยแล้ว");
                },
                error: function (xhr, status, error) {
                    alertify.alert('Delete failed: ' + error);
                }
            });
        }
    </script>
    </body>
    </html>

    <?php
}
?>

