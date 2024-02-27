<?php
$my_text_file = "myfile.txt";
$all_lines = file($my_text_file);
echo $all_lines[1];