<?php
require("header.php");//checks session and login
$page = 'p1';
//here we do our php actions
if (isset($_POST['sub'])) {
    getPost();
    $DB = new database;
    $add = $DB->Insert('customer', 'name,company,cell,phone,email,idNum,vatNum,grp,username,pass,addr', '( "' . $name . '","' . $company . '","' . $cell . '","' . $phone . '","' . $email . '","' . $idNum . '","' . $vatNum . '","' . $grp . '","' . $username . '","' . $pass . '","' . $addr . '" )');
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

    <h1>افزودن مشتری تازه</h1>

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
                               placeholder="نام و نام خانوادگی">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="company" class="control-label">شرکت</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="company" id="company" class="form-control"
                               placeholder="نام شرکت یا سازمان">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="idNum" class="control-label">شناسه ملی</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="idNum" id="idNum" class="form-control"
                               placeholder="1234567891">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="cell" class="control-label">موبایل</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="cell" id="cell" class="form-control"
                               placeholder="9171114466">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="phone" class="control-label">تلفن</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="phone" id="phone" class="form-control"
                               placeholder="7112301616">

                    </div>

                </div>
                <!-- /clearfix -->


                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="vatNum" class="control-label">کد اقتصادی</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="vatNum" id="vatNum" class="form-control"
                               placeholder="کد اقتصادی ">

                    </div>

                </div>
                <!-- /clearfix -->


                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="email" class="control-label">پست الکترونیک</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="40" name="email" id="email" class="form-control"
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

                        <input type="text" size="30" name="username" id="username" class="form-control"
                               placeholder="نام کاربری به لاتین">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="pass" class="control-label">رمز عبور</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="pass" id="pass" class="form-control">

                    </div>

                </div>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="addr" class="control-label">آدرس پستی</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <textarea size="40" name="addr" id="addr" class="form-control"> </textarea>

                    </div>

                </div>

                <!-- /clearfix -->


                <div class="form-group">

                    <div class="col-sm-offset-2 col-sm-10">

                        <input type="submit" value="ذخیره" name="sub" class="btn btn-primary">&nbsp;

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


<?php
include("../footer.php");
?>


</div> <!-- /container -->


</body>

</html>