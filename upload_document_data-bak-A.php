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
    <head lang="en">
        <meta charset="utf-8">
        <title>สงวนออโต้คาร์</title>
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
                        <li class="breadcrumb-item"><span id="main_menu"></span></li>
                        <li class="breadcrumb-item active" aria-current="page"><span id="sub_menu"></span></li>
                    </ol>
                </div>

                <div class="col-md-8">
                    <form method="post" id="uploadForm" name="uploadForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="DI_REF" class="control-label">เลขที่เอกสาร</label>
                                    <input type="text" class="form-control" id="DI_REF" name="DI_REF" readonly="true"
                                           placeholder="">
                                </div>
                                <div class="col-sm-4">
                                    <label for="DI_DATE" class="control-label">วันที่เอกสาร</label>
                                    <input type="text" class="form-control" id="DI_DATE" name="DI_DATE" readonly="true"
                                           placeholder="วันที่เอกสาร">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="ADDB_COMPANY" class="control-label">ชื่อลูกค้า</label>
                                    <input type="text" class="form-control" id="ADDB_COMPANY" name="ADDB_COMPANY"
                                           readonly="true" required="required" placeholder="">
                                </div>
                                <div class="col-sm-4">
                                    <label for="ADDB_PHONE" class="control-label">โทรศัพท์</label>
                                    <input type="text" class="form-control" id="ADDB_PHONE" name="ADDB_PHONE"
                                           readonly="true" placeholder="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <label for="CAR_NO" class="control-label">ทะเบียนรถ</label>
                                    <input type="text" class="form-control" id="CAR_NO" name="CAR_NO" readonly="true"
                                           placeholder="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <label for="files" class="control-label">เลือกไฟล์ EXCEL หรือ PDF หรือ รูปภาพ
                                        JPG หรือ PNG ที่ต้องการ Upload (สูงสุด 5 ไฟล์)</label>
                                    <input class="form-control" type="file" name="files[]" id="files"
                                           accept=".xls,.xlsx,.pdf,.jpg,.png" multiple>
                                </div>
                            </div>

                            <div class="form-group row" id="preview"></div>
                            <input type="hidden" name="id" id="id"/>
                            <button type="button" class="btn btn-primary" onclick="uploadFile()">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="fileLink"></div> <!-- Add this div for showing file link -->

        <div id="err"></div>
    </div>

    <script type="text/javascript">
        let queryString = new Array();
        $(function () {
            if (queryString.length == 0) {
                if (window.location.search.split('?').length > 1) {
                    let params = window.location.search.split('?')[1].split('&');
                    for (let i = 0; i < params.length; i++) {
                        let key = params[i].split('=')[0];
                        let value = decodeURIComponent(params[i].split('=')[1]);
                        queryString[key] = value;
                    }
                }
            }

            let data = "<b>" + queryString["title"] + "</b>";
            $("#title").html(data);
            $("#main_menu").html(queryString["main_menu"]);
            $("#sub_menu").html(queryString["sub_menu"]);
            $('#action').val(queryString["action"]);

            if (queryString["id"] != null && queryString["DI_REF"] != null) {
                $('#id').val(queryString["id"]);
                $('#DI_REF').val(queryString["DI_REF"]);
                $('#DI_DATE').val(queryString["DI_DATE"]);
                $('#ADDB_COMPANY').val(queryString["ADDB_COMPANY"]);
                $('#ADDB_PHONE').val(queryString["ADDB_PHONE"]);
                $('#CAR_NO').val(queryString["CAR_NO"]);
            }
        });

        document.getElementById('files').addEventListener('change', function (event) {
            let preview = document.getElementById('preview');
            if (this.files.length > 5) {
                alert('You can only upload up to 5 files.');
                this.value = ''; // Clear the file input
                return;
            }
            Array.from(this.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        let img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('preview-img');
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    let div = document.createElement('div');
                    div.textContent = file.name;
                    preview.appendChild(div);
                }
            });
        });
    </script>

    <script>
        function uploadFile() {
            let formData = new FormData(document.getElementById('uploadForm'));
            $.ajax({
                url: 'upload_ajax_file_img.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    alertify.alert(data);
                    alertify.alert('Response', data, function () {
                        alertify.success(data);
                    });

                    let uploadedFiles = JSON.parse(data);
                    let preview = document.getElementById('preview');
                    uploadedFiles.forEach(file => {
                        if (file.type.startsWith('image/')) {
                            let img = document.createElement('img');
                            img.src = 'uploads/' + file.name;
                            img.classList.add('preview-img');
                            preview.appendChild(img);
                        } else {
                            let div = document.createElement('div');
                            div.textContent = file.name;
                            preview.appendChild(div);
                        }
                    });
                },
                error: function (xhr, status, error) {
                    alertify.alert('Upload failed: ' + error);
                }
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            $("#btnClose").click(function () {
                window.opener = self;
                window.close();
            });
        });
    </script>
    </body>
    </html>



    <?php
}
?>