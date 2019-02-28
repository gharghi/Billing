<?php
require("header.php");//checks session and login
$page = 'p1';
include "top.php";//includes html top and menu
//here we do our php actions
if (isset($_POST['sub'])) {
    $DB1 = new database;
    $set['name'] = $DB1->Escape($_POST['name']);
    $set['company'] = $DB1->Escape($_POST['company']);
    $set['idNum'] = $DB1->Escape($_POST['idNum']);
    $set['cell'] = $DB1->Escape($_POST['cell']);
    $set['phone'] = $DB1->Escape($_POST['phone']);
    $set['vatNum'] = $DB1->Escape($_POST['vatNum']);
    $set['email'] = $DB1->Escape($_POST['email']);
    $set['grp'] = $DB1->Escape($_POST['grp']);
    $set['username'] = $DB1->Escape($_POST['username']);
    $set['pass'] = $DB1->Escape($_POST['pass']);
    $set['addr'] = $DB1->Escape($_POST['addr']);
    $add = $DB1->Update('customer', $set, 'id = ' . $DB1->Escape($_POST['id']));
    if ($add) {
        $status = '<div class="alert alert-success">

			مشترک  مورد نظر با موفقیت ویرایش شد .

			</div>';
    } else {
        $status = '<div class="alert alert-danger">

			خطایی در ارسال پارامتر ها به وجود آمده است! دوباره امتحان کنید .

			</div>';
    }
}
$DB = new database;
$DB->Select('customer', '*', 'id = ' . $DB->Escape($_GET['id']));
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
                               placeholder="نام و نام خانوادگی">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="company" class="control-label">شرکت</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="company" id="company"
                               value="<?php echo $DB->Select_Result[0]['company']; ?>" class="form-control"
                               placeholder="نام شرکت یا سازمان">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="idNum" class="control-label">شناسه ملی</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="idNum" id="idNum"
                               value="<?php echo $DB->Select_Result[0]['idNum']; ?>" class="form-control"
                               placeholder="1234567891">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="cell" class="control-label">موبایل</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="cell" id="cell"
                               value="<?php echo $DB->Select_Result[0]['cell']; ?>" class="form-control"
                               placeholder="9171114466">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="phone" class="control-label">تلفن</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="phone" id="phone"
                               value="<?php echo $DB->Select_Result[0]['phone']; ?>" class="form-control"
                               placeholder="7112301616">

                    </div>

                </div>
                <!-- /clearfix -->


                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="vatNum" class="control-label">کد اقتصادی</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="vatNum" id="vatNum"
                               value="<?php echo $DB->Select_Result[0]['vatNum']; ?>" class="form-control"
                               placeholder="کد اقتصادی ">

                    </div>

                </div>
                <!-- /clearfix -->


                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="email" class="control-label">پست الکترونیک</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="40" name="email" id="email"
                               value="<?php echo $DB->Select_Result[0]['email']; ?>" class="form-control"
                               placeholder="name@domain.com">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="grp" class="control-label">گروه</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <select name="grp" id="grp" class="form-control">

                            <option
                                value="<?php echo $DB->Select_Result[0]['grp']; ?>"><?php echo findGrp($DB->Select_Result[0]['grp']); ?> </option>

                            <option value="1">ISP</option>

                            <option value="2"> شرکت</option>

                            <option value="3"> شخصی</option>

                        </select>


                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="username" class="control-label">نام کاربری</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="username" id="username"
                               value="<?php echo $DB->Select_Result[0]['username']; ?>" class="form-control"
                               placeholder="نام کاربری به لاتین">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="pass" class="control-label">رمز عبور</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="pass" id="pass"
                               value="<?php echo $DB->Select_Result[0]['pass']; ?>" class="form-control">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="addr" class="control-label">آدرس پستی</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <textarea rows="4" size="40" name="addr" id="addr"
                                  class="form-control"> <?php echo nlToEnter($DB->Select_Result[0]['addr']); ?></textarea>

                    </div>

                </div>

                <!-- /clearfix -->


                <div class="form-group">

                    <div class="col-sm-offset-2 col-sm-10">

                        <input type="submit" value="ذخیره" name="sub" class="btn btn-primary">&nbsp;

                        <input type="button" value="پرینت برگ ارسال" class="btn btn-success"
                               onClick="window.open('printcheckout.php?id=<?php echo $DB->Select_Result[0]['id']; ?>&in=1','_blank')">&nbsp;

                        <button class="btn btn-default" type="button" onClick="document.location.href='listuser.php'">
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