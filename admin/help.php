<?php
require("header.php");//checks session and login
$page = 'p1';
//here we do our php actions
if (isset($_POST['sub'])) {
    getPost();
    $DB = new database;
    $add = $DB->Insert('customer', 'name,company,cell,phone,email,idNum,vatNum,grp,username,pass', '( "' . $name . '","' . $company . '","' . $cell . '","' . $phone . '","' . $email . '","' . $idNum . '","' . $vatNum . '","' . $grp . '","' . $username . '","' . $pass . '" )');
    if ($add) {
        $status = '<div class="alert alert-success">

		مشترک  مورد نظر با موفقیت افزوده شد .

			</div>';
    } else {
        $status = '<div class="alert alert-danger">

			خطایی در ارسال پارامتر ها به وجود آمده است! دوباره امتحان کنید .

			</div>';
    }
}
include "top.php";//includes html top and menu
?>

<div class="page-header">

    <h1>راهنمای سیستم</h1>

</div>

<div class="row">

    <div class="col-lg-12">


        <div>تنها مورد موجود برای کار کردن در سیستم راه اندازی CRON JOBS می باشد.

            <br/>

            برای ارسال ایمیل به مشتریان باید فایل mailjob.php را در سیستم کرون جاب وارد کنید

            <br/>در صورتی که قصد دارید سیستم پیامک راه اندازی شود باید فایل smsjob.php را در سیستم کرون جاب وارد کنید در
            غیر اینصورت نیازی به انجام این کار نیست

        </div>

        <div>
            <a href="http://forum.persianscript.ir/f133/آموزش-فعال-سازی-کرون-جابز-cron-jobs-در-دایرکت-ادمین-directadmin-8688/"
               target="_blank">راهنمای راه اندازی کرون جاب در دایرکت ادمین Direct Admin</a></div>

        <div>
            <a href="http://forum.persianscript.ir/f133/%D8%A2%D9%85%D9%88%D8%B2%D8%B4-%D9%81%D8%B9%D8%A7%D9%84-%D8%B3%D8%A7%D8%B2%DB%8C-%DA%A9%D8%B1%D9%88%D9%86-%D8%AC%D8%A7%D8%A8%D8%B2-cron-jobs-%D8%AF%D8%B1-%D8%B3%DB%8C-%D9%BE%D9%86%D9%84-cpanel-8689/"
               target="_blank">راهنمای راه اندازی کرون جاب در سی پنل CPanel</a></div>


    </div>


</div>

</div>

</div>

<?php
include("../footer.php");
?>

</body>

</html>

