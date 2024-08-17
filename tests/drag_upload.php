<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drag and Drop File Upload</title>
    <style>
        .drop-zone {
            max-width: 500px;
            height: 200px;
            padding: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 20px;
            cursor: pointer;
            color: #999;
            border: 4px dashed #009578;
            border-radius: 10px;
        }
        .drop-zone--over {
            border-style: solid;
        }
    </style>
</head>
<body>

<div class="drop-zone" id="drop-zone">Drag & Drop Files Here</div>

<script>
    const dropZone = document.getElementById('drop-zone');

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('drop-zone--over');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('drop-zone--over');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('drop-zone--over');
        const files = e.dataTransfer.files;
        uploadFiles(files);
    });

    function uploadFiles(files) {
        const formData = new FormData();
        for (const file of files) {
            formData.append('files[]', file);
        }

        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                alert(result.message);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>

</body>
</html>
