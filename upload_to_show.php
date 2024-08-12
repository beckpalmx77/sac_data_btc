<?php
include 'includes/Header.php';

if (isset($_GET['filename'])) {

}

?>

<style>
    body {
        margin: 0;
        padding: 0;
        height: 100%;
        display: table;
        width: 100%;
        text-align: center;
    }

    img {
        vertical-align: middle;
        display: inline-block;
    }

    .center-container {
        display: table-cell;
        vertical-align: middle;
    }
</style>

<body>
<div class="center-container">
    <img src="<?php echo $_GET['filename']; ?>">
</div>