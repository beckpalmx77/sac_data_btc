<?php
include 'includes/Header.php';
if (isset($_GET['filename'])) {
?>
<style>
    body {
        margin: 0;
        padding: 0;
        height: 100%;
        display: table;
        width: 100%;
        text-align: center;
        background-color: #f0f0f0;
    }
    .top-panel {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: #333;
        color: white;
        padding: 10px;
        text-align: right;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
    }
    .top-panel button {
        background-color: #fa3c3c;
        border: none;
        color: white;
        padding: 5px 10px;
        cursor: pointer;
        font-size: 16px;
        border-radius: 3px;
    }
    .top-panel button:hover {
        background-color: #ff0000;
    }
    .center-container {
        display: table-cell;
        vertical-align: middle;
        margin-top: 50px; /* เพิ่ม margin-top เพื่อให้ไม่ทับกับ Panel ด้านบน */
    }
    img {
        vertical-align: middle;
        display: inline-block;
        max-width: 100%;
        height: auto;
    }
</style>
<body>
<div class="top-panel">
    <button onclick="window.close()">ปิด (Close)</button>
</div>
<div class="center-container">
    <img src="<?php echo htmlspecialchars($_GET['filename']); ?>">
</div>

<?php } else { ?>

    <div class="top-panel">
        <button onclick="window.close()">ปิด (Close)</button>
    </div>
    <div class="center-container">
        <img src="img/file_not_found.png">
    </div>

<?php } ?>

</body>
