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

            .card {
                display: inline-block;
                margin-right: 10px;
            }

            .card-img {
                width: 100%;
                height: auto;
                object-fit: cover;
                max-height: 50px;
            }
        </style>

        <style>

            /* Container to hold the preview */
            #preview {
                display: flex;
                flex-wrap: wrap;
                gap: 10px; /* Adjusts the gap between images */
            }

            /* Image preview with zoom effect */
            .preview-img {
                max-width: 100px; /* Adjust the size of the image preview */
                max-height: 100px;
                transition: transform 0.3s ease; /* Smooth transition */
            }

            /* Hover effect to zoom the image */
            .preview-img:hover {
                transform: scale(3); /* Adjust the scale as needed */
            }

        </style>

        <style>
            /* Image preview with zoom effect */
            .zoom-image {
                max-width: 100%;
                max-height: 200px; /* Adjust the size as needed */
                transition: transform 0.3s ease;
            }

            /* Hover effect to zoom the image */
            .zoom-image:hover {
                transform: scale(1.5); /* Adjust the scale as needed */
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
                                <div class="col-sm-4">
                                    <label for="DI_REF" class="control-label">เลขที่เอกสาร</label>
                                    <input type="text" class="form-control" id="DI_REF" name="DI_REF" readonly="true"
                                           placeholder="">
                                </div>
                                <input type="hidden" class="form-control" id="DI_DATE" name="DI_DATE" readonly="true"
                                       placeholder="วันที่เอกสาร">
                                <div class="col-sm-8">
                                    <label for="ADDB_COMPANY" class="control-label">ชื่อลูกค้า</label>
                                    <input type="text" class="form-control" id="ADDB_COMPANY" name="ADDB_COMPANY"
                                           readonly="true" required="required" placeholder="">
                                </div>
                                <div class="col-sm-4">
                                    <input type="hidden" class="form-control" id="ADDB_PHONE" name="ADDB_PHONE"
                                           readonly="true" placeholder="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="CAR_NO" class="control-label">ทะเบียนรถ</label>
                                    <input type="text" class="form-control" id="CAR_NO" name="CAR_NO" readonly="true"
                                           placeholder="">
                                </div>
                                <div class="col-sm-8">
                                    <label for="REMARK" class="control-label">หมายเหตุ</label>
                                    <input type="text" class="form-control" id="REMARK" name="REMARK" readonly="true"
                                           placeholder="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label for="files" class="control-label">เลือกไฟล์ EXCEL หรือ PDF หรือ รูปภาพ
                                        JPG หรือ PNG ที่ต้องการ Upload (สูงสุด 7 ไฟล์)</label>
                                    <input class="form-control" type="file" name="files[]" id="files"
                                           accept=".xls,.xlsx,.pdf,.jpg,.png" multiple>
                                </div>
                            </div>

                            <!-- เพิ่ม radio buttons เพื่อเลือกฟิลด์ที่จะอัปโหลดไฟล์ -->
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label class="control-label">เลือกตำแหน่งที่จะอัปโหลดไฟล์:</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="uploadField"
                                               id="uploadField1" value="FILE_UPLOAD1" checked>
                                        <label class="form-check-label" for="uploadField1">ใบรับงาน</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="uploadField"
                                               id="uploadField2" value="FILE_UPLOAD2">
                                        <label class="form-check-label" for="uploadField2">ใบกำกับฯ</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="uploadField"
                                               id="uploadField3" value="FILE_UPLOAD3">
                                        <label class="form-check-label" for="uploadField3">ทะเบียนรถ</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="uploadField"
                                               id="uploadField4" value="FILE_UPLOAD4">
                                        <label class="form-check-label" for="uploadField4">เลขไมล์</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="uploadField"
                                               id="uploadField5" value="FILE_UPLOAD5">
                                        <label class="form-check-label" for="uploadField5">รูปรถ</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="uploadField"
                                               id="uploadField6" value="FILE_UPLOAD6">
                                        <label class="form-check-label" for="uploadField6">อะไหล่ที่เปลี่ยน</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="uploadField"
                                               id="uploadField7" value="FILE_UPLOAD7">
                                        <label class="form-check-label" for="uploadField7">ยางที่เปลี่ยน</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row" id="preview"></div>
                            <input type="hidden" name="id" id="id"/>
                            <button type="button" class="btn btn-primary" id="btnUpload" onclick="uploadFile()">Upload
                            </button>
                            <button type="button" class="btn btn-danger" id="btnClose">Close</button>
                        </div>

                        <div class="col-sm-10">
                            <input type="hidden" class="form-control" id="FILE_UPLOAD1" name="FILE_UPLOAD1"
                                   readonly="true" placeholder="">
                        </div>
                        <div class="col-sm-10">
                            <input type="hidden" class="form-control" id="FILE_UPLOAD2" name="FILE_UPLOAD2"
                                   readonly="true" placeholder="">
                        </div>
                        <div class="col-sm-10">
                            <input type="hidden" class="form-control" id="FILE_UPLOAD3" name="FILE_UPLOAD3"
                                   readonly="true" placeholder="">
                        </div>
                        <div class="col-sm-10">
                            <input type="hidden" class="form-control" id="FILE_UPLOAD4" name="FILE_UPLOAD4"
                                   readonly="true" placeholder="">
                        </div>
                        <div class="col-sm-10">
                            <input type="hidden" class="form-control" id="FILE_UPLOAD5" name="FILE_UPLOAD5"
                                   readonly="true" placeholder="">
                        </div>

                        <div class="col-sm-10">
                            <input type="hidden" class="form-control" id="FILE_UPLOAD6" name="FILE_UPLOAD6"
                                   readonly="true" placeholder="">
                        </div>
                        <div class="col-sm-10">
                            <input type="hidden" class="form-control" id="FILE_UPLOAD7" name="FILE_UPLOAD7"
                                   readonly="true" placeholder="">
                        </div>

                        <div class="col-sm-12">
                            <div id="fileLink"></div>
                        </div>

                    </form>
                </div>
            </div>
        </div>


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
                $('#REMARK').val((queryString["REMARK"] === null || queryString["REMARK"] === 'null') ? '-' : queryString["REMARK"]);
                $('#ADDB_COMPANY').val(queryString["ADDB_COMPANY"]);
                $('#ADDB_PHONE').val(queryString["ADDB_PHONE"]);
                $('#CAR_NO').val(queryString["CAR_NO"]);
                loadFile();
            }
        });

    </script>

    <script>
        function uploadFile() {

            let formData = new FormData(document.getElementById('uploadForm'));
            // ดึงค่าฟิลด์ที่เลือกจาก radio button
            const uploadField = document.querySelector('input[name="uploadField"]:checked').value;

            //alert("uploadField = " + uploadField);

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
            $('#files').on('change', function () {
                let files = this.files;
                let preview = $('#preview');
                preview.empty();

                if (files.length > 0) {
                    for (let i = 0; i < files.length; i++) {
                        let file = files[i];
                        let fileType = file.type.toLowerCase();

                        let reader = new FileReader();

                        reader.onload = function (e) {
                            if (fileType.startsWith('image/')) {
                                let img = $('<img>')
                                    .attr('src', e.target.result)
                                    .addClass('preview-img zoom-image'); // Add 'zoom-image' class
                                preview.append(img);
                            } else {
                                let fileName = $('<div>').text(file.name);
                                preview.append(fileName);
                            }
                        }

                        reader.readAsDataURL(file);
                    }
                }
            });
        });
    </script>


    <script>

        function loadFile_bak() {
            let rec_id = $('#id').val();
            let formData = {action: "GET_FILE", id: rec_id};

            $.ajax({
                type: "POST",
                url: 'model/manage_document_service_process.php',
                dataType: "json",
                data: formData,
                success: function (response) {
                    let len = response.length;
                    let fileBasePath = ""; // Base path ของไฟล์ที่ upload
                    let placeholderImage = "img/file.jpg"; // Path ของรูปภาพแทน

                    for (let i = 0; i < len; i++) {
                        let fileUpload1 = response[i].FILE_UPLOAD1;
                        let fileUpload2 = response[i].FILE_UPLOAD2;
                        let fileUpload3 = response[i].FILE_UPLOAD3;
                        let fileUpload4 = response[i].FILE_UPLOAD4;
                        let fileUpload5 = response[i].FILE_UPLOAD5;
                        let fileUpload6 = response[i].FILE_UPLOAD6;
                        let fileUpload7 = response[i].FILE_UPLOAD7;

                        function displayFile(file, inputId) {
                            if (file) {
                                let fileType = file.split('.').pop().toLowerCase(); // ตรวจสอบประเภทของไฟล์
                                let imgSrc = (fileType === 'jpg' || fileType === 'jpeg' || fileType === 'png' || fileType === 'gif')
                                    ? fileBasePath + file
                                    : placeholderImage;
                                // สร้าง Bootstrap Card โดยใส่ชื่อไฟล์ใน Header และรูปภาพใน Body
                                let cardElement = `
            <div class="col-md-6 mb-3">
                <div class="card" style="width: 100%;">
                    ${!(fileType === 'jpg' || fileType === 'jpeg' || fileType === 'png' || fileType === 'gif')
                                    ? '<div class="card-header">' + file + '</div>'
                                    : ''}
                    <div class="card-body">
                        <img src="${imgSrc}" class="card-img" alt="${file}">
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteFile('${file}', '${inputId}')">ลบ</button>
                </div>
            </div>
        `;
                                // ถ้า div ของไฟล์ยังไม่ถูกสร้าง ให้สร้างใหม่
                                let $targetRow = $('#fileLink .row').last();
                                // ถ้ายังไม่มี row หรือล่าสุดมีครบ 2 cards ให้สร้าง row ใหม่
                                if ($targetRow.length === 0 || $targetRow.children('.col-md-6').length >= 2) {
                                    $targetRow = $('<div class="row"></div>').appendTo('#fileLink');
                                }
                                $targetRow.append(cardElement);
                            }
                        }

                        // เรียกใช้ function displayFile สำหรับแต่ละไฟล์
                        displayFile(fileUpload1, 'FILE_UPLOAD1');
                        displayFile(fileUpload2, 'FILE_UPLOAD2');
                        displayFile(fileUpload3, 'FILE_UPLOAD3');
                        displayFile(fileUpload4, 'FILE_UPLOAD4');
                        displayFile(fileUpload5, 'FILE_UPLOAD5');
                        displayFile(fileUpload6, 'FILE_UPLOAD6');
                        displayFile(fileUpload7, 'FILE_UPLOAD7');
                    }
                },
                error: function (xhr, status, error) {
                    alertify.error("error : " + xhr.responseText);
                }
            });
        }
    </script>

    <script>
        function loadFile() {
            let rec_id = $('#id').val();
            let formData = {action: "GET_FILE", id: rec_id};

            $.ajax({
                type: "POST",
                url: 'model/manage_document_service_process.php',
                dataType: "json",
                data: formData,
                success: function (response) {
                    let fileBasePath = ""; // Base path ของไฟล์ที่ upload
                    let placeholderImage = "img/file.jpg"; // Path ของรูปภาพแทน

                    response.forEach((fileData, index) => {
                        const files = [
                            fileData.FILE_UPLOAD1,
                            fileData.FILE_UPLOAD2,
                            fileData.FILE_UPLOAD3,
                            fileData.FILE_UPLOAD4,
                            fileData.FILE_UPLOAD5,
                            fileData.FILE_UPLOAD6,
                            fileData.FILE_UPLOAD7,
                        ];

                        files.forEach((file, i) => {
                            if (file) {
                                let fileType = file.split('.').pop().toLowerCase();
                                let imgSrc = (['jpg', 'jpeg', 'png', 'gif'].includes(fileType))
                                    ? fileBasePath + file
                                    : placeholderImage;

                                let cardElement = `
                                <div class="col-md-6 mb-3">
                                    <div class="card" style="width: 100%;">
                                        ${!(fileType === 'jpg' || fileType === 'jpeg' || fileType === 'png' || fileType === 'gif')
                                    ? '<div class="card-header">' + file + '</div>'
                                    : ''}
                                        <div class="card-body">
                                            <img src="${imgSrc}" class="card-img zoom-image" alt="${file}">
                                        </div>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteFile('${file}', 'FILE_UPLOAD${i + 1}')">ลบ</button>
                                    </div>
                                </div>
                            `;

                                let $targetRow = $('#fileLink .row').last();
                                if ($targetRow.length === 0 || $targetRow.children('.col-md-6').length >= 2) {
                                    $targetRow = $('<div class="row"></div>').appendTo('#fileLink');
                                }
                                $targetRow.append(cardElement);
                            }
                        });
                    });
                },
                error: function (xhr, status, error) {
                    alertify.error("error : " + xhr.responseText);
                }
            });
        }
    </script>

    <script>
        function deleteFile(fileName, fileIndex) {
            if (confirm("คุณต้องการลบไฟล์นี้หรือไม่?")) {
                let id = $('#id').val();
                //alert(fileName + " "  + fileIndex + " "+ rec_id);

                $.ajax({
                    url: 'model/manage_document_service_process.php',
                    type: 'POST',
                    data: {
                        action: 'DELETE_FILE_SINGLE',
                        file_name: fileName,
                        file_index: fileIndex,
                        id: id
                    },
                    success: function (response) {
                        alertify.success("ไฟล์ถูกลบเรียบร้อยแล้ว");
                        // ลบ card ที่เกี่ยวข้องกับไฟล์นั้นๆ
                        $('#card-' + fileIndex).remove();
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        alertify.error("การลบไฟล์ล้มเหลว: " + error);
                    }
                });

            }
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
<?php } ?>
