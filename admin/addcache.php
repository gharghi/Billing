<?php
require("header.php");//checks session and login
$page = 'p8';
//here we do our php actions
if (isset($_POST['sub'])) {
    $DB = new database;
    $account_id = $DB->Escape($_POST['account_id']);
    $amount = (int)$DB->Escape($_POST['amount']);
    $bank = $DB->Escape($_POST['bank']);
    $date = gdate($DB->Escape($_POST['date']));
    $descr = $DB->Escape($_POST['descr']);
    $type = $DB->Escape($_POST['type']);
    $add = $DB->Insert('cache', 'account_id,amount,bank,reg_date,descr,type', '( "' . $account_id . '","' . $amount . '","' . $bank . '","' . $date . '","' . $descr . '","' . $type . '" )');
    if ($add) {
        $status = '<div class="alert alert-success">
				تراكنش با موفقيت درج شد .
				(<a href="accounttrans.php?id='.$account_id.'" >مشاهده تراكنش ها</a>)
				</div>';
    } else {
        $status = '<div class="alert alert-danger">
				خطایی در ارسال پارامتر ها به وجود آمده است! دوباره امتحان کنید .
				</div>';
    }
}
include "top.php";//includes html top and menu
$DB = new database;
$DB->Select('account', '*', 'id = ' . $DB->Escape($_GET['id']));
?>

<div class="page-header">
    <h1>درج تراكنش براي
        <a href="editaccount.php?id=<?php echo  $DB->Escape($_GET['id']); ?>" class="titleA">
            <?php echo $DB->Select_Result[0]['name']; ?></a>
        </h1>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php if (isset($status)) echo $status; ?>
        <form method="POST" role="form" class="form-horizontal">
            <fieldset>
                <input type="hidden" name="account_id" value="<?php echo $DB->Select_Result[0]['id']; ?>">
                <!-- /clearfix -->
                <div class="form-group">
                    <div class="col-xs-2">
                        <label for="type" class="control-label">نوع تراكنش</label>
                    </div>
                    <div class="col-xs-10 inputsize">
                        <select name="type" id="type" class="form-control">
                            <option value="1">برداشت</option>
                            <option value="2">واريز</option>
                            <option value="3">حقوق</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-2">
                        <label for="amount" class="control-label">مبلغ </label>
                    </div>
                    <div class="col-xs-10 inputsize">
                        <input type="text" size="30" name="amount" id="amount" class="form-control"
                               onKeyUp="doText('amount','textAmount');">

                    </div>
                    <div class="col-xs-6 inputsize">
                        <label id="textAmount"> </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-2">
                        <label for="date" class="control-label">تاريخ</label>
                    </div>
                    <div class="col-xs-10 inputsize">
                        <input type="text" size="30" name="date" id="date" class="form-control" placeholder="1393-05-24"
                               value="<?php echo curjdate(); ?>">
                    </div>
                </div>
                <script>
                    var objCal1 = new AMIB.persianCalendar('date', {
                            extraInputID: 'date',
                            extraInputFormat: 'yyyy-mm-dd',
                            btnClassName: 'hidden'
                        }
                    );
                </script>
                <!-- /clearfix -->

                <div class="form-group">
                    <div class="col-xs-2">
                        <label for="bank" class="control-label">نام بانك</label>
                    </div>
                    <div class="col-xs-10 inputsize">
                        <input type="text" size="30" name="bank" id="bank" class="form-control">
                    </div>
                </div>

                <!-- /clearfix -->

                <div class="form-group">
                    <div class="col-xs-2">
                        <label for="descr" class="control-label">توضیحات</label>
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