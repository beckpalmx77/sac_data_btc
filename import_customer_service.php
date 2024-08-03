<?php
include('includes/Header.php');
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    ?>

    <!DOCTYPE html>
    <html lang="th">
    <body id="page-top">
    <div id="wrapper">
        <?php
        include('includes/Side-Bar.php');
        ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php
                include('includes/Top-Bar.php');
                ?>
                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?php echo urldecode($_GET['s']) ?></h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo $_SESSION['dashboard_page'] ?>">Home</a>
                            </li>
                            <li class="breadcrumb-item"><?php echo urldecode($_GET['m']) ?></li>
                            <li class="breadcrumb-item active"
                                aria-current="page"><?php echo urldecode($_GET['s']) ?></li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-12">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                </div>
                                <div class="card-body">
                                    <section class="container-fluid">
                                        <div class="col-md-12 col-md-offset-2">
                                            <table id='TableRecordList' class='display dataTable'>
                                                <thead>
                                                <tr>
                                                    <th>เลขที่เอกสาร</th>
                                                    <th>วันที่</th>
                                                    <th>ชื่อลูกค้า</th>
                                                    <th>โทรศัพท์</th>
                                                    <th>เลขทะเบียนรถ</th>
                                                    <th>Action</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>เลขที่เอกสาร</th>
                                                    <th>วันที่</th>
                                                    <th>ชื่อลูกค้า</th>
                                                    <th>โทรศัพท์</th>
                                                    <th>เลขทะเบียนรถ</th>
                                                    <th>Action</th>
                                                    <th>Action</th>
                                                </tr>
                                                </tfoot>
                                            </table>

                                            <div id="result"></div>

                                        </div>

                                        <div class="modal fade" id="recordModal">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Modal title</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true">×
                                                        </button>
                                                    </div>
                                                    <form method="post" id="recordForm">
                                                        <div class="modal-body">
                                                            <div class="modal-body">

                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <label for="DI_REF" class="control-label">เลขที่เอกสาร</label>
                                                                        <input type="DI_REF" class="form-control"
                                                                               id="DI_REF" name="DI_REF"
                                                                               readonly="true"
                                                                               placeholder="เลขที่เอกสาร">
                                                                    </div>

                                                                    <div class="col-sm-4">
                                                                        <label for="DI_DATE"
                                                                               class="control-label">วันที่</label>
                                                                        <input type="text" class="form-control"
                                                                               id="DI_DATE"
                                                                               name="DI_DATE"
                                                                               readonly="true"
                                                                               placeholder="วันที่">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-8">
                                                                        <label for="ADDB_COMPANY"
                                                                               class="control-label">ชื่อลูกค้า</label>
                                                                        <input type="text" class="form-control"
                                                                               id="ADDB_COMPANY"
                                                                               name="ADDB_COMPANY"
                                                                               readonly="true"
                                                                               placeholder="ชื่อลูกค้า">
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <label for="ADDB_PHONE"
                                                                               class="control-label">โทรศัพท์</label>
                                                                        <input type="text" class="form-control"
                                                                               id="ADDB_PHONE"
                                                                               name="ADDB_PHONE"
                                                                               readonly="true"
                                                                               placeholder="โทรศัพท์">
                                                                    </div>

                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-10">
                                                                        <label for="CAR_NO"
                                                                               class="control-label">ทะเบียนรถ</label>
                                                                        <input type="text" class="form-control"
                                                                               id="CAR_NO"
                                                                               name="CAR_NO"
                                                                               readonly="true"
                                                                               placeholder="ทะเบียนรถ">
                                                                    </div>

                                                                </div>


                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" id="id"/>
                                                            <input type="hidden" name="action" id="action" value=""/>
                                                            <span class="icon-input-btn">
                                                                <i class="fa fa-check"></i>
                                                            <input type="submit" name="save" id="save"
                                                                   class="btn btn-primary" value="Save"/>
                                                            </span>
                                                            <button type="button" class="btn btn-danger"
                                                                    data-dismiss="modal">Close <i
                                                                        class="fa fa-window-close"></i>
                                                            </button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php
    include('includes/Modal-Logout.php');
    include('includes/Footer.php');
    ?>


    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/myadmin.min.js"></script>

    <script src="js/popup.js"></script>

    <script src="vendor/datatables/v11/bootbox.min.js"></script>
    <script src="vendor/datatables/v11/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="vendor/datatables/v11/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="vendor/datatables/v11/buttons.dataTables.min.css"/>


    <style>

        .icon-input-btn {
            display: inline-block;
            position: relative;
        }

        .icon-input-btn input[type="submit"] {
            padding-left: 2em;
        }

        .icon-input-btn .fa {
            display: inline-block;
            position: absolute;
            left: 0.65em;
            top: 30%;
        }
    </style>

    <script>
        $(document).ready(function () {
            $(".icon-input-btn").each(function () {
                let btnFont = $(this).find(".btn").css("font-size");
                let btnColor = $(this).find(".btn").css("color");
                $(this).find(".fa").css({'font-size': btnFont, 'color': btnColor});
            });
        });
    </script>

    <script>

        $("#DI_REF").blur(function () {
            let method = $('#action').val();
            if (method === "ADD") {
                let DI_REF = $('#DI_REF').val();
                let formData = {action: "SEARCH", DI_REF: DI_REF};
                $.ajax({
                    url: 'model/manage_document_service_process.php',
                    method: "POST",
                    data: formData,
                    success: function (data) {
                        if (data == 2) {
                            alert("Duplicate มีข้อมูลนี้แล้วในระบบ กรุณาตรวจสอบ");
                        }
                    }
                })
            }
        });

    </script>

    <script>
        $(document).ready(function () {
            let formData = {action: "GET_DOCUMENT", sub_action: "GET_MASTER"};
            let dataRecords = $('#TableRecordList').DataTable({
                'lengthMenu': [[5, 10, 20, 50, 100], [5, 10, 20, 50, 100]],
                'language': {
                    search: 'ค้นหา', lengthMenu: 'แสดง _MENU_ รายการ',
                    info: 'หน้าที่ _PAGE_ จาก _PAGES_',
                    infoEmpty: 'ไม่มีข้อมูล',
                    zeroRecords: "ไม่มีข้อมูลตามเงื่อนไข",
                    infoFiltered: '(กรองข้อมูลจากทั้งหมด _MAX_ รายการ)',
                    paginate: {
                        previous: 'ก่อนหน้า',
                        last: 'สุดท้าย',
                        next: 'ต่อไป'
                    }
                },
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'model/manage_document_service_process.php',
                    'data': formData
                },
                'columns': [
                    {data: 'DI_REF'},
                    {data: 'DI_DATE'},
                    {data: 'ADDB_COMPANY'},
                    {data: 'ADDB_PHONE'},
                    {data: 'CAR_NO'},
                    {data: 'upload'},
                    {data: 'update'}
                ]
            });

            <!-- *** FOR SUBMIT FORM *** -->
            $("#recordModal").on('submit', '#recordForm', function (event) {
                event.preventDefault();
                $('#save').attr('disabled', 'disabled');
                let formData = $(this).serialize();
                $.ajax({
                    url: 'model/manage_document_service_process.php',
                    method: "POST",
                    data: formData,
                    success: function (data) {
                        alertify.success(data);
                        $('#recordForm')[0].reset();
                        $('#recordModal').modal('hide');
                        $('#save').attr('disabled', false);
                        dataRecords.ajax.reload();
                    }
                })
            });
            <!-- *** FOR SUBMIT FORM *** -->
        });
    </script>

    <script>

        $("#TableRecordList").on('click', '.update', function () {
            let id = $(this).attr("id");
            //alert(id);
            let formData = {action: "GET_DATA", id: id};
            $.ajax({
                type: "POST",
                url: 'model/manage_document_service_process.php',
                dataType: "json",
                data: formData,
                success: function (response) {
                    let len = response.length;
                    for (let i = 0; i < len; i++) {
                        let id = response[i].id;
                        let DI_REF = response[i].DI_REF;
                        let DI_DATE = response[i].DI_DATE;
                        let ADDB_COMPANY = response[i].ADDB_COMPANY;
                        let ADDB_PHONE = response[i].ADDB_PHONE;
                        let CAR_NO = response[i].CAR_NO;

                        $('#recordModal').modal('show');
                        $('#id').val(id);
                        $('#DI_REF').val(DI_REF);
                        $('#DI_DATE').val(DI_DATE);
                        $('#ADDB_COMPANY').val(ADDB_COMPANY);
                        $('#ADDB_PHONE').val(ADDB_PHONE);
                        $('#CAR_NO').val(CAR_NO);
                        $('.modal-title').html("<i class='fa fa-plus'></i> Edit Record");
                        $('#action').val('UPDATE');
                        $('#save').val('Save');
                    }
                },
                error: function (response) {
                    alertify.error("error : " + response);
                }
            });
        });

    </script>

    <script>

        $("#TableRecordList").on('click', '.upload', function () {
            let id = $(this).attr("id");
            let formData = {action: "GET_DATA", id: id};
            $.ajax({
                type: "POST",
                url: 'model/manage_document_service_process.php',
                dataType: "json",
                data: formData,
                success: function (response) {
                    let len = response.length;
                    for (let i = 0; i < len; i++) {
                        let id = response[i].id;
                        let DI_REF = response[i].DI_REF;
                        let DI_DATE = response[i].DI_DATE;
                        let ADDB_COMPANY = response[i].ADDB_COMPANY;
                        let ADDB_PHONE = response[i].ADDB_PHONE;
                        let CAR_NO = response[i].CAR_NO;

                        let main_menu = "นำเข้าข้อมูล";
                        let sub_menu = "ข้อมูลเช็ครถเบื้องต้น BTC";
                        let originalURL = "upload_document_data.php?title=ข้อมูลเช็ครถเบื้องต้น (BTC)"
                            + '&main_menu=' + main_menu + '&sub_menu=' + sub_menu
                            + '&id=' + id
                            + '&DI_REF=' + DI_REF + '&DI_DATE=' + DI_DATE
                            + '&CAR_NO=' + CAR_NO
                            + '&ADDB_PHONE=' + ADDB_PHONE
                            + '&ADDB_COMPANY=' + ADDB_COMPANY
                            + '&action=UPDATE';

                        OpenPopupCenter(originalURL, "", "");

                    }
                },
                error: function (response) {
                    alertify.error("error : " + response);
                }
            });
        });

    </script>



    <!--script>

        $("#TableRecordList").on('click', '.upload', function () {
            let id = $(this).attr("id");
            let formData = {action: "GET_DATA", id: id};
            $.ajax({
                type: "POST",
                url: 'model/manage_leave_document_process.php',
                dataType: "json",
                data: formData,
                success: function (response) {
                    let len = response.length;
                    for (let i = 0; i < len; i++) {
                        let id = response[i].id;
                        let DI_REF = response[i].DI_REF;
                        let DI_DATE = response[i].DI_DATE;
                        let ADDB_COMPANY = response[i].ADDB_COMPANY;
                        let ADDB_PHONE = response[i].ADDB_PHONE;
                        let CAR_NO = response[i].CAR_NO;
                        let main_menu = "นำเข้าข้อมูล";
                        let sub_menu = "ข้อมูลเช็ครถเบื้องต้น BTC";

                        let originalURL = "upload_document_data.php?title=ข้อมูลเช็ครถเบื้องต้น (BTC)"
                            + '&main_menu=' + main_menu + '&sub_menu=' + sub_menu
                            + '&id=' + id
                            + '&DI_REF=' + DI_REF + '&DI_DATE=' + DI_DATE
                            + '&ADDB_COMPANY=' + ADDB_COMPANY + '&ADDB_PHONE=' + ADDB_PHONE
                            + '&CAR_NO=' + CAR_NO
                            + '&action=UPDATE';

                        OpenPopupCenter(originalURL, "", "");

                    }
                },
                error: function (response) {
                    alertify.error("error : " + response);
                }
            });
        });

    </script-->


    </body>
    </html>

<?php } ?>