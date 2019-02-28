<?php
require("header.php");//checks session and login
$page = 'p3';
//here we do our php actions
if (isset($_POST['sub'])) {
    getPost();
    $DB = new database;
    $add = $DB->Insert('notification', 'name,pre_sms,post_sms,pre_interval,post_interval,pre_warn_date', '( "' . $name . '","' . $pre_sms . '","' . $post_sms . '","' . $pre_interval . '","' . $post_interval . '","' . $pre_warn_date . '" )');
    if ($add) {
        $status = '<div class="alert alert-success">

			قانون  مورد نظر با موفقیت افزوده شد .

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

    <h1>افزودن قانون اطلاع رسانی تازه</h1>

</div>

<div class="row">

    <div class="col-lg-12">

        <?php if (isset($status)) echo $status; ?>

        <form method="POST" role="form" class="form-horizontal">


            <fieldset>


                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="name" class="control-label">نام</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="name" id="name" class="form-control"
                               placeholder="نام قانون ">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="pre_sms" class="control-label">ارسال قبل از سررسید</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="pre_sms" id="pre_sms" class="form-control"
                               placeholder="تعداد اطلاع رسانی قبل از سررسید ">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="post_sms" class="control-label">ارسال بعد از سررسید</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="post_sms" id="post_sms" class="form-control"
                               placeholder="تعداد اطلاع رسانی بعد از سررسید ">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="pre_interval" class="control-label">بازه قبل از سررسید</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="pre_interval" id="pre_interval" class="form-control"
                               placeholder="اطلاع رسانی قبل سر رسید (روز)">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="post_interval" class="control-label">بازه بعد از سررسید</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="post_interval" id="post_interval" class="form-control"
                               placeholder="اطلاع رسانی بعد سر رسید (روز)">

                    </div>

                </div>
                <!-- /clearfix -->


                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="pre_warn_date" class="control-label">شروع اخطار</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="pre_warn_date" id="pre_warn_date" class="form-control"
                               placeholder="بر حسب روز قبل از صورتحساب">

                    </div>

                </div>
                <!-- /clearfix -->


                <div class="form-group">

                    <div class="col-sm-offset-2 col-sm-10">

                        <input type="submit" value="ذخیره" name="sub" class="btn btn-primary">&nbsp;

                        <button class="btn btn-default" type="button" onClick="document.location.href='listnotif.php'">
                            لغو
                        </button>

                    </div>

                </div>


            </fieldset>


        </form>

    </div>


</div>

</div>


<?php
include("../footer.php");
?>


</div> <!-- /container -->


</body>

</html>