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
                                        <div class="row">
                                            <div class="col-md-12 col-md-offset-2">
                                                <div class="panel">
                                                    <div class="panel-body">

                                                        <form id="from_data" method="post"
                                                              action="model/query_process_data_history_customer.php"
                                                              enctype="multipart/form-data">

                                                            <div class="form-group row">

                                                                <div class="col-sm-3">
                                                                    <label for="doc_date_start"
                                                                           class="control-label">จากวันที่</label>
                                                                    <i class="fa fa-calendar"
                                                                       aria-hidden="true"></i>
                                                                    <input type="text" class="form-control"
                                                                           id="doc_date_start"
                                                                           name="doc_date_start"
                                                                           required="required"
                                                                           readonly="true"
                                                                           placeholder="จากวันที่">
                                                                </div>

                                                                <div class="col-sm-3">
                                                                    <label for="doc_date_to"
                                                                           class="control-label">ถึงวันที่</label>
                                                                    <i class="fa fa-calendar"
                                                                       aria-hidden="true"></i>
                                                                    <input type="text" class="form-control"
                                                                           id="doc_date_to"
                                                                           name="doc_date_to"
                                                                           required="required"
                                                                           readonly="true"
                                                                           placeholder="ถึงวันที่">
                                                                </div>


                                                            </div>

                                                            <div class="form-group has-success">
                                                                <label for="success" class="control-label">ค้นหาตามทะเบียนรถยนต์</label>
                                                                <div class="">
                                                                    <input type="text" name="car_no"
                                                                           class="form-control"
                                                                           id="car_no" value="">
                                                                </div>
                                                            </div>

                                                            <div class="form-group has-success">
                                                                <input type="hidden" name="AR_CODE" id="AR_CODE"
                                                                       class="form-control">
                                                                <input type="hidden" name="AR_NAME" id="AR_NAME"
                                                                       class="form-control">
                                                                <select id='selCustomer' class='form-control'
                                                                        onchange="Onchange_AR_CODE();">
                                                                    <option value='0'>- ค้นหารายชื่อลูกค้า -
                                                                    </option>
                                                                </select>
                                                            </div>

                                                            <input type="hidden" name="sku_name"
                                                                   class="form-control"
                                                                   id="sku_name" value="">

                                                            <div class="modal-footer">
                                                                <input type="hidden" name="id" id="id"/>
                                                                <input type="hidden" name="save_status"
                                                                       id="save_status"/>
                                                                <input type="hidden" name="action" id="action"
                                                                       value=""/>
                                                                <button type="submit" class="btn btn-primary"
                                                                        id="btnQuery"> ค้นหาข้อมูล <i
                                                                            class="fa fa-check"></i>
                                                                </button>
                                                                <!--button type="button" class="btn btn-success"
                                                                        id="btnExport"> Export <i
                                                                            class="fa fa-check"></i>
                                                                </button-->
                                                            </div>


                                                        </form>


                                                        <div id="result"></div>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col-md-8 col-md-offset-2 -->
                                        </div>
                                        <!-- /.row -->

                                    </section>


                                </div>

                            </div>

                        </div>

                    </div>
                    <!--Row-->

                    <!-- Row -->

                </div>

                <!---Container Fluid-->

            </div>

            <?php
            include('includes/Modal-Logout.php');
            include('includes/Footer.php');
            ?>

        </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="vendor/select2/dist/js/select2.min.js"></script>

    <!-- select2 css -->
    <link href='js/select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

    <!-- select2 script -->
    <script src='js/select2/dist/js/select2.min.js'></script>

    <!-- Bootstrap Datepicker -->
    <script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <!-- Bootstrap Touchspin -->
    <script src="vendor/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js"></script>
    <!-- ClockPicker -->
    <script src="vendor/clock-picker/clockpicker.js"></script>
    <!-- RuangAdmin Javascript -->
    <script src="js/myadmin.min.js"></script>
    <!-- Javascript for this page -->

    <script src="vendor/date-picker-1.9/js/bootstrap-datepicker.js"></script>
    <script src="vendor/date-picker-1.9/locales/bootstrap-datepicker.th.min.js"></script>
    <!--link href="vendor/date-picker-1.9/css/date_picker_style.css" rel="stylesheet"/-->
    <link href="vendor/date-picker-1.9/css/bootstrap-datepicker.css" rel="stylesheet"/>

    <script src="js/MyFrameWork/framework_util.js"></script>

    <script src="js/util.js"></script>

    <script>
        $(document).ready(function () {
            let today = new Date();
            let doc_date = getDay2Digits(today) + "-" + getMonth2Digits(today) + "-" + today.getFullYear();
            $('#doc_date_start').val(doc_date);
            $('#doc_date_to').val(doc_date);
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#doc_date_start').datepicker({
                format: "dd-mm-yyyy",
                todayHighlight: true,
                language: "th",
                autoclose: true
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#doc_date_to').datepicker({
                format: "dd-mm-yyyy",
                todayHighlight: true,
                language: "th",
                autoclose: true
            });
        });
    </script>


    <script>
        $(document).ready(function () {

            $("#selCustomer").select2({
                ajax: {
                    url: "model/get_customer_ajax.php",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
        });

    </script>


    <script>

        function Onchange_AR_CODE() {

            $('#AR_NAME').val($("#selCustomer").val());

        }
    </script>

    <script>
        $(document).ready(function () {
            $('#doc_date_to').datepicker({
                format: "dd-mm-yyyy",
                todayHighlight: true,
                language: "th",
                autoclose: true
            });
        });
    </script>


    </body>

    </html>

<?php } ?>