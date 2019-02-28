<?php
require("header.php");//checks session and login
$page = 'p2';
//here we do our php actions
if (isset($_POST['sub'])) {
    $DB = new database;
    $id = $DB->Escape($_POST['id']);
    $set['type'] = $DB->Escape($_POST['type']);
    $set['invoice_name'] = $DB->Escape($_POST['invoice_name']);
    $set['period'] = $DB->Escape($_POST['period']);
    $set['gen_date'] = gdate($DB->Escape($_POST['gen_date']));
    $set['exp_date'] = gdate($DB->Escape($_POST['exp_date']));
    $set['status'] = $DB->Escape($_POST['status']);
    $set['notification_id'] = $DB->Escape($_POST['notification_id']);
    $set['discount'] = $DB->Escape($_POST['discount']);
    $set['vat'] = $DB->Escape($_POST['vat']);
    $set['descr'] = $DB->Escape($_POST['descr']);
    $itemCount = $DB->Escape($_POST['itemCount']);
    $update = $DB->Update('invoice', $set, 'id = ' . $id);
    if ($update) {
        $delete = $DB->Delete('item', 'invoice_id', $id);
        if ($delete) {
            $itemSet = ' ';
            for ($i = 0; $i <= $itemCount + 3; $i++) {
                if (!isset($_POST['itemName' . $i]) || !isset($_POST['itemPrice' . $i]) || !is_numeric($_POST['itemPrice' . $i]) || !is_numeric($_POST['itemQuantity' . $i])) {
                    continue;
                }
                $itemQuantity = $DB->Escape($_POST['itemQuantity' . $i]);
                $itemName = $DB->Escape($_POST['itemName' . $i]);
                $itemPrice = $DB->Escape($_POST['itemPrice' . $i]);
                $itemUnit = $DB->Escape($_POST['itemUnit' . $i]);
                if (!$itemQuantity) {
                    $itemQuantity = 1;
                }
                $itemSet = $itemSet . ' ( "' . $itemName . '","' . $itemQuantity . '","' . $itemUnit . '","' . $itemPrice . '" , ' . $id . ' ) ,';
            }
        }
        $itemSet = rtrim($itemSet, ",");
        $addItem = $DB->Insert('item', 'name,quantity,Unit,price,invoice_id', $itemSet);
        if ($addItem) {
            $status = '<div class="alert alert-success">

				فاکتور  مورد نظر با موفقیت ویرایش شد .

				</div>';
        } else {
            $status = '<div class="alert alert-danger">

			خطایی در ارسال پارامتر ها به وجود آمده است! دوباره امتحان کنید .

				</div>';
        }
    }
}
include "top.php";//includes html top and menu
$DB = new database;
$DB2 = new database;
$id = $DB->Escape($_GET['id']);
$DB->Select('item', '*', 'invoice_id = ' . $id . ' ORDER BY id ASC');
$itemNum = $DB->Select_Rows;
$DB2->Select('invoice', '*', 'id = ' . $id);
?>

<script language="javascript" src="../js/FormatMoney.js"></script>

<script language="javascript">

    function addItem() {

        var itemName = $('#itemName').val();

        var itemQuantity = $('#itemQuantity').val();

        var itemPrice = $('#itemPrice').val();

        var itemUnit = $('#itemUnit').val();

        var count = $('#itemCount').val();

        itemPrice = parseInt(itemPrice);

        itemPrice2 = FormatMoney(itemPrice, '', ' ریال ', ',', '', 0, 0);

        count++;

        $('#itemCount').val(count);

        $('#itemTable').append('<div class="itemRow" ><div class="itemName">' + itemName + '<input type="hidden" name="itemName' + count + '" id="' + itemName + count + '" value="' + itemName + '" > </div><div class="itemQuantity">' + itemQuantity + '<input type="hidden" name="itemQuantity' + count + '" id="' + itemQuantity + count + '" value="' + itemQuantity + '"></div><div class="itemUnit">' + itemUnit + '<input type="hidden" name="itemUnit' + count + '" id="' + itemUnit + count + '" value="' + itemUnit + '"></div><div class="itemPrice">' + itemPrice2 + '<input type="hidden" name="itemPrice' + count + '" id="' + itemPrice + count + '" value="' + itemPrice + '"></div><img src="../images/del.png" onClick="delItem(this);"></div>');

        var itemHeight = $('#itemLabel').height();

        itemHeight += 20;

        $('#itemLabel').height(itemHeight);

        $('#itemName').val('');

        $('#itemQuantity').val('');

        $('#itemPrice').val('');

    }

    function delItem(id) {

        $(id).parent('div').remove();

        var count = $('#itemCount').val();

        count--;

        $('#itemCount').val(count);

    }

</script>

<div class="page-header">

    <h1>ویرایش فاکتور شماره <?php echo $id; ?></h1>

</div>

<div class="row">

    <div class="col-lg-12">


        <?php if (isset($status)) echo $status; ?>

        <form method="POST" role="form" class="form-horizontal">

            <fieldset>


                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="type" class="control-label">نوع</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <select name="type" id="type" class="form-control">

                            <option
                                value="<?php echo $DB2->Select_Result[0]['type']; ?>"> <?php echo findType($DB2->Select_Result[0]['type']); ?></option>

                            <option value="1">عادی</option>

                            <option value="2"> دوره ای</option>

                        </select>

                    </div>

                </div>

                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="invoice_name" class="control-label">عنوان فاکتور</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="invoice_name" id="invoice_name" class="form-control"
                               value="<?php echo $DB2->Select_Result[0]['invoice_name']; ?>">

                    </div>

                </div>

                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="gen_date" class="control-label">زمان سررسید</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="gen_date" id="gen_date" class="form-control"
                               placeholder="1393-05-24"
                               value="<?php echo sfdate($DB2->Select_Result[0]['gen_date']); ?>">

                    </div>

                </div>
                <script>
                    var objCal1 = new AMIB.persianCalendar('gen_date', {
                            extraInputID: 'gen_date',
                            extraInputFormat: 'yyyy-mm-dd',
                            btnClassName: 'hidden'
                        }
                    );
                </script>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="period" class="control-label">مدت تناوب</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="period" id="period" class="form-control"
                               placeholder="مدت به روز" value="<?php echo $DB2->Select_Result[0]['period']; ?>">

                    </div>

                </div>

                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="exp_date" class="control-label">زمان انقضا</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="exp_date" id="exp_date" class="form-control"
                               placeholder="1393-05-24"
                               value="<?php echo sfdate($DB2->Select_Result[0]['exp_date']); ?>">

                    </div>

                </div>
                <script>
                    var objCal2 = new AMIB.persianCalendar('exp_date', {
                            extraInputID: 'exp_date',
                            extraInputFormat: 'yyyy-mm-dd',
                            btnClassName: 'hidden'
                        }
                    );
                </script>
                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="status" class="control-label">وضعیت پرداخت</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <select name="status" id="status" class="form-control">

                            <option
                                value="<?php echo $DB2->Select_Result[0]['status']; ?>"> <?php echo findStatus($DB2->Select_Result[0]['status']); ?></option>

                            <option value="0">پرداخت نشده</option>

                            <option value="1">پرداخت شده</option>

                            <option value="2"> لغو شده</option>

                        </select>

                    </div>

                </div>

                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="notification_id" class="control-label">قانون اطلاع رسانی</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <select name="notification_id" id="notification_id" class="form-control">

                            <option
                                value="<?php echo $DB2->Select_Result[0]['notification_id']; ?>"> <?php echo notifName($DB2->Select_Result[0]['notification_id']); ?></option>

                            <?php
                            $DB3 = new database;
                            $DB3->Select('notification', 'id,name');
                            $num = $DB3->Select_Rows;
                            $i = 0;
                            while ($i < $num) {
                                echo " <option value='" . $DB3->Select_Result[$i]['id'] . "'>" . $DB3->Select_Result[$i]['name'] . "</option>";
                                $i++;
                            }
                            ?>

                        </select>

                    </div>

                </div>

                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="discount" class="control-label">درصد تخفیف</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="discount" id="discount" class="form-control"
                               placeholder="درصد تخفیف به عدد"
                               value="<?php echo $DB2->Select_Result[0]['discount']; ?>">

                    </div>

                </div>

                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="vat" class="control-label">ارزش افزوده</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="vat" id="vat" class="form-control"
                               placeholder="ارزش افزوده به عدد" value="<?php echo $DB2->Select_Result[0]['vat']; ?>">

                    </div>

                </div>

                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="descr" class="control-label">توضیحات</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <textarea size="40" name="descr" id="descr"
                                  class="form-control"> <?php echo nlToEnter($DB2->Select_Result[0]['descr']); ?></textarea>

                    </div>

                </div>

                <!-- /clearfix -->

                <input type="hidden" name="itemCount" id="itemCount" value="<?php echo $itemNum; ?>">

                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="vat" class="control-label">آیتم ها</label>

                    </div>

                    <span class="itemName">عنوان </span> <span class="itemQuantity">تعداد</span> <span class="itemUnit">واحد</span>
                    <span class="itemPrice">قیمت</span> <span class="itemButton"></span>

                    <div class="col-xs-10">


                        <input type="text" name="itemName" id="itemName" class="itemName ">

                        <input type="text" name="itemQuantity" id="itemQuantity" class="itemQuantity  " value="1">

                        <input type="text" name="itemUnit" id="itemUnit" class="itemUnit " placeholder="عدد">

                        <input type="text" name="itemPrice" id="itemPrice" class="itemPrice " placeholder="ریال">

                        <span id="itemButton" class="itemButton"><a href="javascript:addItem();"
                                                                    class="btn btn-info btn-xs">افزودن</a> </span>


                    </div>

                </div>

                <!-- /clearfix -->

                <!------------------------------------>

                <div class="form-group">

                    <div class="col-xs-2">

                        <label style="height:<?php echo $itemNum * 20; ?>px;" id="itemLabel" class="control-label">
                            &nbsp;</label>

                    </div>

                    <div class="col-xs-10" id="itemTable">

                        <?php
                        for ($i = 0; $i < $itemNum; $i++) {
                            $j = $i + 1;
                            echo '

	<div class="itemRow" >

	<div class="itemName">' . $DB->Select_Result[$i]['name'] . '

	<input type="hidden" name="itemName' . $j . '" id="w' . $DB->Select_Result[$i]['name'] . $j . '" value="' . $DB->Select_Result[$i]['name'] . '" >

	</div>

	<div class="itemQuantity">' . $DB->Select_Result[$i]['quantity'] . '

	<input type="hidden" name="itemQuantity' . $j . '" id="w' . $DB->Select_Result[$i]['quantity'] . $j . '" value="' . $DB->Select_Result[$i]['quantity'] . '">

	</div>

	<div class="itemUnit">' . $DB->Select_Result[$i]['unit'] . '

	<input type="hidden" name="itemUnit' . $j . '" id="w' . $DB->Select_Result[$i]['unit'] . $j . '" value="' . $DB->Select_Result[$i]['unit'] . '">

	</div>

	<div class="itemPrice">' . number_format($DB->Select_Result[$i]['price']) . '

	<input type="hidden" name="itemPrice' . $j . '" id="w' . $DB->Select_Result[$i]['price'] . $j . '" value="' . $DB->Select_Result[$i]['price'] . '">

	</div>

	<img src="../images/del.png" onClick="delItem(this);">

	</div>

	';
                        }
                        ?>

                    </div>

                </div>

                <!-- /clearfix -->

                <!------------------------------>

                <div class="form-group">

                    <input type="submit" value="ویرایش" name="sub" class="btn btn-primary">

                    &nbsp;

                    <button class="btn btn-default" type="button" onClick="document.location.href='listinvoiceord.php'">
                        بازگشت
                    </button>

                </div>

            </fieldset>

        </form>

    </div>

</div>

</div></div>

<?php
include("../footer.php");
?>


</body></html>