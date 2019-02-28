<?php
session_name('kAdmin');
session_save_path('../tmp');
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}
function __autoload($class_name)
{
    include_once '../inc/class.' . $class_name . '.inc.php';
}

include("../inc/func.php");
?>
<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">