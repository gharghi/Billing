<?php
require("header.php");//checks session and login
$page = 'p8';
include "top.php";//includes html top and menu
//here we do our php actions
if (isset($_POST['sub'])) {
    $DB1 = new database;
    $set['name'] = $DB1->Escape($_POST['name']);
    $set['grp'] = $DB1->Escape($_POST['grp']);
    $set['descr'] = $DB1->Escape($_POST['descr']);
    $add = $DB1->Update('account', $set, 'id = ' . $DB1->Escape($_POST['id']));
    if ($add) {
        $status = '<div class="alert alert-success">
			حساب  مورد نظر با موفقیت ویرایش شد .
			</div>';
    } else {
        $status = '<div class="alert alert-danger">
			خطایی در ارسال پارامتر ها به وجود آمده است! دوباره امتحان کنید .
			</div>';
    }
}
$DB = new database;
$DB->Select('account', '*', 'id = ' . $DB->Escape($_GET['id']));
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
                        <label for="grp" class="control-label">گروه</label>
                    </div>
                    <div class="col-xs-10 inputsize">
                        <select name="grp" id="grp" class="form-control">
                            <option
                                value="<?php echo $DB->Select_Result[0]['grp']; ?>"><?php echo findGrp2($DB->Select_Result[0]['grp']); ?> </option>
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
                        <textarea rows="4" size="40" name="descr" id="descr"
                                  class="form-control"><?php echo nlToEnter($DB->Select_Result[0]['descr']); ?></textarea>
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
</div>
<?php
include("../footer.php");
?>
</body></html>