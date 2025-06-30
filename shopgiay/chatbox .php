<?php
$file = 'messages.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $msg = strip_tags($_POST['message']);
    $msg = htmlspecialchars($msg);
    file_put_contents($file, "<div class='message'>Bạn: $msg</div>\n", FILE_APPEND);
} else {
    if (file_exists($file)) {
        echo file_get_contents($file);
    }
}
?>