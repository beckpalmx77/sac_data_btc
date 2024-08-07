<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Upload Files</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="files" class="form-label">Select up to 5 files to upload</label>
            <input type="file" class="form-control" id="files" name="files[]" multiple required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
