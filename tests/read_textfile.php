<?php
$filename = "myfile.txt";
$searchWord = "checktype";

try {
    $fileHandle = fopen($filename, 'r');
    if ($fileHandle === false) {
        throw new Exception("Error opening the file.");
    }

    $lineNumber = 1;
    $found = false;
    echo "Specific word: " . $searchWord;
    while (($line = fgets($fileHandle)) !== false) {
        if (stripos($line, $searchWord) !== false) {
            echo "Word found on line " . $lineNumber . ": " . $line;
            $found = true;
        }

        $lineNumber++;
    }

    if (!$found) {
        echo "Word not found in the file.";
    }

    fclose($fileHandle);
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
?>
