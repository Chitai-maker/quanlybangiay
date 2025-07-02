
<?php
include "chucnang/connectdb.php";
$masize = intval($_GET['masize']);
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT tensize FROM sizegiay WHERE masize=$masize"));
echo $row ? $row['tensize'] : $masize;