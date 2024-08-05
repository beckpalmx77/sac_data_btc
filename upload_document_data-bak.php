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
        <script type="text/javascript" src="js/alertify/alertify.js"></script>
        <link rel="stylesheet" href="js/alertify/css/alertify.css">
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
                    <form method="post" id="my_form" enctype="multipart/form-data" action="upload_ajax_file.php">
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="DI_REF" class="control-label">เลขที่เอกสาร</label>
                                    <input type="text" class="form-control" id="DI_REF" name="DI_REF" readonly="true" placeholder="">
                                </div>
                                <div class="col-sm-4">
                                    <label for="DI_DATE" class="control-label">วันที่เอกสาร</label>
                                    <input type="text" class="form-control" id="DI_DATE" name="DI_DATE" readonly="true" placeholder="วันที่เอกสาร">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="ADDB_COMPANY" class="control-label">ชื่อลูกค้า</label>
                                    <input type="text" class="form-control" id="ADDB_COMPANY" name="ADDB_COMPANY" readonly="true" required="required" placeholder="">
                                </div>
                                <div class="col-sm-4">
                                    <label for="ADDB_PHONE" class="control-label">โทรศัพท์</label>
                                    <input type="text" class="form-control" id="ADDB_PHONE" name="ADDB_PHONE" readonly="true" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <label for="CAR_NO" class="control-label">ทะเบียนรถ</label>
                                    <input type="text" class="form-control" id="CAR_NO" name="CAR_NO" readonly="true" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <label for="fileToUpload" class="control-label">เลือกไฟล์ EXCEL หรือ PDF ที่ต้องการ Upload</label>
                                    <input class="form-control" type="file" name="fileToUpload" id="fileToUpload" accept=".xls,.xlsx,.pdf">
                                    <div>Upload File (ไฟล์ .XLS & XLSX หรือ PDF เท่านั้น)</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id" id="id"/>
                            <input type="hidden" name="action" id="action" value=""/>
                            <input class="btn btn-success" type="submit" value="Upload">
                            <button type="button" class="btn btn-danger" id="btnClose">Close <i class="fa fa-window-close"></i></button>
                        </div>
                    </form>
                    <div id="err"></div>
                </div>
            </div>
        </div>
    </div>

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
