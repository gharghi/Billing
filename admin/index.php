<?php
include_once '../inc/class.logindb.inc.php';
if (isset($_POST['username']) && isset($_POST['password'])) {
    $postuser = $_POST['username'];
    $postpass = md5($_POST['password']);
    $DB = new logindb;
    $loginadmin = $DB->Select('setting', 'adminuser,adminpass', "id = '1' LIMIT 1");
    $adminuser = $DB->Select_Result[0]['adminuser'];
    $adminpass = $DB->Select_Result[0]['adminpass'];
//echo $adminpass; echo '<br>'. $postpass; exit;
    if (($postuser == $adminuser) && ($postpass == $adminpass)) {
        $config = parse_ini_file("../global/config.ini");
        session_name('kAdmin');
        session_save_path('../tmp');
        session_start();
        foreach ($config as $key => $value) {
            $_SESSION[$key] = $value;
        }
        $_SESSION['user'] = $_POST['username'];
        header("location: dashboard.php");
    } else {
        $status = '<div class="alert alert-danger">

				نام کاربری یا رمز عبور وارده اشتباه است.

				</div>';
    }
}
?>

<!DOCTYPE html>

<html lang="en" dir="rtl">

<head>

    <meta charset="utf-8">

    <title>مدیریت صورت حساب</title>

    <meta name="description" content="مدیریت صورتحساب کارانشان">

    <meta name="author" content="کارانشان چارسوی فناوری">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->

    <!--[if lt IE 9]>

    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>

    <![endif]-->

    <!-- Le styles -->

    <link href="../css/bootstrap.rtl.min.css" rel="stylesheet">

    <link href="../css/bootstrap-theme.min.css" rel="stylesheet">

    <link href="../css/style.css" rel="stylesheet">

    <script type="text/javascript" src="../js/jquery.js"></script>

    <script type="text/javascript" src="../js/bootstrap.rtl.min.js"></script>

    <script type="text/javascript" src="../js/jquery.fancybox.pack.js"></script>

    <script type="text/javascript" src="../js/jquery.fancybox.js"></script>

    <link rel="stylesheet" href="../css/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen"/>

    <link rel="stylesheet" href="../css/jquery.fancybox.css" type="text/css" media="screen"/>

    <script type="text/javascript">

        $(document).ready(function () {

            $("#mailtext").cleditor()[0].focus();

        });

    </script>

</head>

<body>

<nav class="navbar navbar-default  navbar-fixed-top" role="navigation">

    <div class="container">

        <div class="navbar-header">

            <p class="loginheader">سیستم صورت حساب کارانشان</p>

        </div>

    </div>

</nav>

<div class="container">

    <div class="jumbotron">

        <div class="page-header">

            <h1>ورود كاربرها</h1>

        </div>

        <?php if (isset($status)) echo $status; ?>

        <div class="row">

            <div class="col-lg-12">

                <form method="POST" class="form-horizontal">

                    <fieldset>


                        <div class="form-group">

                            <div class="col-xs-2">

                                <label for="username" class="control-label">نام کاربری</label>

                            </div>

                            <div class="col-xs-10 inputsize">

                                <input value="" type="text" size="30" name="username" id="username" class="form-control"
                                       placeholder="نام کاربری مدیر" dir="ltr">

                            </div>

                        </div>


                        <div class="form-group">

                            <div class="col-xs-2">

                                <label for="password" class="control-label">رمز عبور</label>

                            </div>

                            <div class="col-xs-10 inputsize">

                                <input value="" type="password" size="30" name="password" id="password"
                                       class="form-control" placeholder="رمز عبور" dir="ltr">

                            </div>

                        </div>

                        <div class="form-group">

                            <div class="col-sm-offset-2 col-sm-10">

                                <input type="submit" value="ورود" class="btn btn-primary">&nbsp;
                                <button class="btn btn-default" type="reset">لغو</button>

                            </div>

                        </div>

                    </fieldset>

                </form>

            </div>

        </div>

    </div>

</div>

<?php
include("../footer.php");
?>

</body>

</html>

