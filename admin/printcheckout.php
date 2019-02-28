<?php
require("header.php");//checks session and login
$page = 'p1';
//include "top.php";//includes html top and menu
?>

<link href="../css/bootstrap.min.css" rel="stylesheet">

<link href="../css/style.css" rel="stylesheet">

<div class="printdiv">


    <h1>فرستنده</h1>

    <p class="printing">

        <?php
        $DB = new database;
        $DB->Select('setting', '*');
        $url = $DB->Select_Result[0]['site_URL'];
        echo nlToBr($DB->Select_Result[0]['postaladdress']);
        ?>

    </p>

    <hr style="width:60%; border:1px solid #000;">

    <h1>گیرنده</h1>

    <p class="printing">

        <?php
        $id = $DB->Escape($_GET['id']);
        $DB->Select('customer', '*', 'id = ' . $id);
        echo nlToBr($DB->Select_Result[0]['addr']), '<br />';
        echo nlToBr($DB->Select_Result[0]['company']), '&nbsp; - &nbsp;';
        echo nlToBr($DB->Select_Result[0]['name']), '<br />';
        echo nlToBr($DB->Select_Result[0]['phone']), '&nbsp; - &nbsp;';
        echo nlToBr($DB->Select_Result[0]['cell']);
        echo " </p> ";
        if (isset($_GET['in']) && $_GET['in'] == 1) {
            echo "<p>

 فاکتور خود را در این آدرس مشاهده کنید:

 $url

 </p>";
        }
        ?>

</div>

<!-- /container -->


</body></html>