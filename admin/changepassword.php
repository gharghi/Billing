<?php
require("header.php");//checks session and login
$page = 'p6';
//here we do our php actions
if (isset($_POST['sub'])) {
    $DB1 = new database;
    if (($_POST['newpass'] == $_POST['renewpass'])) {
        $newadminpassword = md5($DB1->Escape($_POST['newpass']));
        $set['adminuser'] = $DB1->Escape($_POST['newuser']);
        $set['adminpass'] = $newadminpassword;
        $edit = $DB1->Update('setting', $set, 'id = 1');
        if ($edit) {
            $status = '<div class="alert alert-success">

				تغییرات با موفقیت اعمال شد.

				</div>';
        } else {
            $status = '<div class="alert alert-danger">

				خطایی در ارسال پارامتر ها به وجود آمده است! دوباره امتحان کنید .

				</div>';
        }
    } else {
        $status = '<div class="alert alert-danger">

				رمز عبور جدید و تکرار آن با یکدیگر تطابق ندارند.

				</div>';
    }
}
$DB = new database;
$DB->Select('setting', '*', 'id = 1');
include "top.php";//includes html top and menu
?>

<div class="page-header">

    <h1>تغییر نام کاربری و رمز عبور مدیریت</h1>

</div>

<div class="row">

    <div class="col-lg-12">

        <div class="alert alert-warning" role="alert">

            عملیات تغییر رمز عبور مدیریت غیر قابل بازگشت است! لطفا توجه نمایید

        </div>

        <?php if (isset($status)) echo $status; ?>

        <form method="POST" role="form" class="form-horizontal">

            <input type="hidden" name="go" value="changepassword">

            <fieldset>


                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="newuser" class="control-label">نام کاربری جدید</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input value="<?php echo $DB->Select_Result[0]['adminuser']; ?>" type="text" size="30"
                               name="newuser" id="newuser" class="form-control" placeholder="نام کاربری جدید" dir="ltr">

                    </div>

                </div>

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="newpass" class="control-label">رمز عبور جدید</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input value="" type="password" size="30" name="newpass" id="newpass" class="form-control"
                               placeholder="رمز عبور جدید" dir="ltr">

                    </div>

                </div>

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="renewpass" class="control-label">تکرار رمز عبور جدید</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input value="" type="password" size="30" name="renewpass" id="renewpass" class="form-control"
                               placeholder="تکرار رمز عبور جدید" dir="ltr">

                    </div>

                </div>

                <div class="form-group">

                    <div class="col-sm-offset-2 col-sm-10">

                        <input type="submit" value="ذخیره" name="sub" class="btn btn-primary">&nbsp;

                        <button class="btn btn-default" type="reset" onClick="document.location.href='dashboard.php'">
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

</body>

</html>

