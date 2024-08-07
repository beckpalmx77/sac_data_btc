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
        <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
              integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
              crossorigin="anonymous"-->
    </head>
    <body>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Container Fluid-->
            <div class="container-fluid" id="container-wrapper">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"><span id="title"></span></h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo $_SESSION['dashboard_page'] ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item"><span id="main_menu"></li>
                        <li class="breadcrumb-item active"
                            aria-current="page"><span id="sub_menu"></li>
                    </ol>
                </div>

                <div class="col-md-8">

                    <form method="post" id="uploadForm" name = "uploadForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="modal-body">


                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="text"
                                               class="control-label">เลขที่เอกสาร</label>
                                        <input type="DI_REF" class="form-control"
                                               id="DI_REF" name="DI_REF"
                                               readonly="true"
                                               placeholder="">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="DI_DATE"
                                               class="control-label">วันที่เอกสาร</label>
                                        <i class="fa fa-calendar"
                                           aria-hidden="true"></i>
                                        <input type="text" class="form-control"
                                               id="DI_DATE"
                                               name="DI_DATE"
                                               readonly="true"
                                               placeholder="วันที่เอกสาร">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="ADDB_COMPANY"
                                               class="control-label">ชื่อลูกค้า</label>
                                        <input type="text" class="form-control"
                                               id="ADDB_COMPANY" name="ADDB_COMPANY"
                                               readonly="true"
                                               required="required"
                                               value=""
                                               placeholder="">
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="ADDB_PHONE"
                                               class="control-label">โทรศัพท์</label>
                                        <input type="text" class="form-control"
                                               id="ADDB_PHONE" name="ADDB_PHONE"
                                               readonly="true"
                                               value=""
                                               placeholder="">
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <label for="CAR_NO"
                                               class="control-label">ทะเบียนรถ</label>
                                        <input type="text" class="form-control"
                                               id="CAR_NO" name="CAR_NO"
                                               readonly="true"
                                               value=""
                                               placeholder="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <!--label for="picture"
                                               class="control-label">ชื่อไฟล์</label-->
                                        <input type="hidden" class="form-control"
                                               id="picture" name="picture"
                                               readonly="true"
                                               required="required"
                                               value=""
                                               placeholder="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <label for="uploadImage"
                                               class="control-label">เลือกไฟล์ EXCEL หรือ PDF ที่ต้องการ Upload</label>
                                        <!--input class="form-control" type="file" id="uploadImage" accept="image/*"
                                               name="image"
                                               onchange="readURL(this)" multiple/-->
                                        <input class="form-control" type="file" name="fileToUpload" id="fileToUpload" accept=".xls,.xlsx,.pdf">
                                        <div>Upload File (ไฟล์ .XLS & XLSX หรือ PDF เท่านั้น)</div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">
                            <input type="hidden" name="id" id="id"/>
                            <input type="hidden" name="action" id="action" value="UPLOAD_FILE"/>
                            <span class="icon-input-btn">
                                <input class="btn btn-success" type="button" value="Upload" onclick="uploadFile()">
                            </span>
                            <button type="button" class="btn btn-danger"
                                    id="btnClose">Close <i
                                        class="fa fa-window-close"></i>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        </form>

        <div id="err"></div>

    </div>

    <style>

        .preview {
            width: 200px;
            height: 200px;
            border: 0;
            margin: 0 auto;
            background: white;
        }

    </style>

    <script>
        function encodeURL(url) {
            return encodeURIComponent(url);
        }

        function decodeURL(url) {
            return encodeURIComponent(url);
        }
    </script>

    <script>
        function bigImg(x) {
            x.style.height = "100%";
            x.style.width = "100%";
        }

        function normalImg(x) {
            x.style.height = "200px";
            x.style.width = "200px";
        }
    </script>

    <style>
        .enlarge {
            height: 100%;
            width: 100%;
            float: left;
            -webkit-transition: all 0.5s ease-in-out;
            -moz-transition: all 0.5s ease-in-out;
            transition: all 0.5s ease-in-out;
        }

        .enlarge:hover {
            height: 300%;
            width: 300%;
            -webkit-transition: all 0.5s ease-in-out;
            -moz-transition: all 0.5s ease-in-out;
            transition: all 0.5s ease-in-out;
            cursor: pointer;
        }
    </style>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#img')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>


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

                //let image_load = "img_doc/" + queryString["picture"];

                //$("#img").attr("src", image_load);

                //$('#picture').val(queryString["picture"]);

            }
        });
    </script>

    <!--script>

        $(document).ready(function (e) {
            $("#upload_form").on('submit', (function (e) {
                e.preventDefault();
                let fd = new FormData();
                $.ajax({
                    url: "upload_ajax_file.php",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        $("#err").fadeOut();
                    },
                    success: function (data) {
                        if (data === 'invalid') {
                            $("#err").html("กรุณาเลือกไฟล์ที่ถูกต้องเพื่อ Upload !").fadeIn();
                            alertify.alert('กรุณาเลือกไฟล์ที่ถูกต้องเพื่อ Upload !');
                        } else {
                            alertify
                                .alert("Upload รูปภาพเรียบร้อยแล้ว", function () {
                                    alertify.message('OK');
                                });
                        }
                    },
                    error: function (e) {
                        $("#err").html(e).fadeIn();
                    }
                });
            }));
        });
    </script-->

    <script>
        function uploadFile() {
            //let id = $('#id').val();
            //let DI_REF = $('#DI_REF').val();
            let formData = $(this).serialize();

            //alert(id + " | " + DI_REF);

            $.ajax({
                type: 'POST',
                url: 'upload_ajax_file.php',
                data: formData,
                success: function(response) {
                    $('#message').html(response);
                },
                error: function(xhr, status, error) {
                    $('#message').html('Upload failed: ' + error);
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

