<?php
require("header.php");//checks session and login
$page = 'p3';
include "top.php";//includes html top and menu
//here we do our php actions
if (isset($_POST['sub'])) {
    $DB1 = new database;
    $set['name'] = $DB1->Escape($_POST['name']);
    $set['pre_sms'] = $DB1->Escape($_POST['pre_sms']);
    $set['post_sms'] = $DB1->Escape($_POST['post_sms']);
    $set['pre_interval'] = $DB1->Escape($_POST['pre_interval']);
    $set['post_interval'] = $DB1->Escape($_POST['post_interval']);
    $set['pre_warn_date'] = $DB1->Escape($_POST['pre_warn_date']);
    $add = $DB1->Update('notification', $set, 'id = ' . $DB1->Escape($_POST['id']));
    if ($add) {
        $status = '<div class="alert alert-success">

			قانون  مورد نظر با موفقیت ویرایش شد .

			</div>';
    } else {
        $status = '<div class="alert alert-danger">

			خطایی در ارسال پارامتر ها به وجود آمده است! دوباره امتحان کنید .

			</div>';
    }
}
$DB = new database;
$DB->Select('notification', '*', 'id = ' . $DB->Escape($_GET['id']));
?>

<div class="page-header">

    <h1>ویرایش <?php echo $DB->Select_Result[0]['name']; ?></h1>

</div>

<div class="row">

    <div class="col-lg-12">


        <?php if (isset($status)) echo $status; ?>

        <form method="POST" role="form" class="form-horizontal">

            <fieldset>

                <input type="hidden" name="id" value="<?php echo $DB->Select_Result[0]['id']; ?>">

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="name" class="control-label">نام</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="name" id="name"
                               value="<?php echo $DB->Select_Result[0]['name']; ?>" class="form-control"
                               placeholder="نام قانون">

                    </div>

                </div>

                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="pre_sms" class="control-label">ارسال قبل از سررسید</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="pre_sms" id="pre_sms"
                               value="<?php echo $DB->Select_Result[0]['pre_sms']; ?>" class="form-control"
                               placeholder="تعداد اطلاع رسانی قبل از سررسید فاکتور">

                    </div>

                </div>

                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="post_sms" class="control-label">ارسال بعد از سررسید</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="post_sms" id="post_sms"
                               value="<?php echo $DB->Select_Result[0]['post_sms']; ?>" class="form-control"
                               placeholder="تعداد اطلاع رسانی بعد از سررسید فاکتور">

                    </div>

                </div>

                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="pre_interval" class="control-label">بازه قبل از سررسید</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="pre_interval" id="pre_interval"
                               value="<?php echo $DB->Select_Result[0]['pre_interval']; ?>" class="form-control"
                               placeholder="مقدار دوره اطلاع رسانی قبل سر رسید یر اساس روز">

                    </div>

                </div>

                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="post_interval" class="control-label">بازه بعد از سررسید</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="post_interval" id="post_interval"
                               value="<?php echo $DB->Select_Result[0]['post_interval']; ?>" class="form-control"
                               placeholder="مقدار دوره اطلاع رسانی بعد سر رسید یر اساس روز">

                    </div>

                </div>

                <!-- /clearfix -->


                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="pre_warn_date" class="control-label">شروع اخطار</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="pre_warn_date" id="pre_warn_date"
                               value="<?php echo $DB->Select_Result[0]['pre_warn_date']; ?>" class="form-control"
                               placeholder="بر حسب روز قبل از صورتحساب ">

                    </div>

                </div>

                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-sm-offset-2 col-sm-10">

                        <input type="submit" value="ذخیره" name="sub" class="btn btn-primary">

                        &nbsp;

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

</div>

<?php
include("../footer.php");
?>

</body></html>