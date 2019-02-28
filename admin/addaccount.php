<?php
require("header.php");//checks session and login
$page = 'p8';
//here we do our php actions
if (isset($_POST['sub'])) {
    getPost();
    $DB = new database;
    $add = $DB->Insert('account', 'name,grp,descr,display', '( "' . $name . '","' . $grp . '","' . $descr . '",1 )');
    if ($add) {
        $status = '<div class="alert alert-success">
			حساب  مورد نظر با موفقیت افزوده شد .
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
    <h1>افزودن حساب </h1>
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
                        <label for="grp" class="control-label">گروه</label>
                    </div>
                    <div class="col-xs-10 inputsize">
                        <select name="grp" id="grp" class="form-control">
                            <option value="1">هيئت مديره</option>
                            <option value="2"> شرکت</option>
                            <option value="3"> كارمندها</option>
                        </select>
                    </div>
                </div>
                <!-- /clearfix -->
                <div class="form-group">
                    <div class="col-xs-2">
                        <label for="descr" class="control-label">توضيحات</label>
                    </div>
                    <div class="col-xs-10 inputsize">
                        <textarea size="40" name="descr" id="descr" class="form-control"> </textarea>
                    </div>
                </div>
                <!-- /clearfix -->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="ذخیره" name="sub" class="btn btn-primary">
                        &nbsp;
                        <button class="btn btn-default" type="button"
                                onClick="document.location.href='listaccount.php'">لغو
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
</div>
<!-- /container -->
</body></html>