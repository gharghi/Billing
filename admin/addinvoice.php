<?php
require("header.php");//checks session and login
$page = 'p1';
//here we do our php actions
if (isset($_POST['sub'])) {
    $DB = new database;
    $customer_id = $DB->Escape($_POST['customer_id']);
    $type = $DB->Escape($_POST['type']);
    $invoice_name = $DB->Escape($_POST['invoice_name']);
    $period = $DB->Escape($_POST['period']);
    $gen_date = gdate($DB->Escape($_POST['gen_date']));
    $exp_date = gdate($DB->Escape($_POST['exp_date']));
    $status = $DB->Escape($_POST['status']);
    $notification_id = $DB->Escape($_POST['notification_id']);
    $discount = $DB->Escape($_POST['discount']);
    $vat = $DB->Escape($_POST['vat']);
    $descr = $DB->Escape($_POST['descr']);
    $itemCount = $DB->Escape($_POST['itemCount']);
    $track_num = substr(md5(rand(0, 1000)), 0, 10);
    $add = $DB->Insert('invoice', 'customer_id,notification_id,type,period,status,discount,vat,descr,track_num,exp_date,gen_date,invoice_name,date', '( "' . $customer_id . '","' . $notification_id . '","' . $type . '","' . $period . '","' . $status . '","' . $discount . '","' . $vat . '","' . $descr . '","' . $track_num . '","' . $exp_date . '","' . $gen_date . '","' . $invoice_name . '",CURDATE() )');
    $invoice_id = $DB->Last_ID();
    if ($add) {
        $itemSet = ' ';
        for ($i = 1; $i <= $itemCount; $i++) {
            $itemQuantity = $DB->Escape($_POST['itemQuantity' . $i]);
            $itemName = $DB->Escape($_POST['itemName' . $i]);
            $itemPrice = $DB->Escape($_POST['itemPrice' . $i]);
            $itemUnit = $DB->Escape($_POST['itemUnit' . $i]);
            if (!$itemQuantity) {
                $itemQuantity = 1;
            }
            if (!$itemName || !$itemPrice) {
                continue;
            }
            $itemSet = $itemSet . ' ( "' . $itemName . '","' . $itemQuantity . '","' . $itemPrice . '","' . $itemUnit . '" , ' . $invoice_id . ' ) ,';
        }
        $itemSet = rtrim($itemSet, ",");
        $addItem = $DB->Insert('item', 'name,quantity,price,unit,invoice_id', $itemSet);
        if ($addItem) {
            $status = '<div class="alert alert-success">

				فاکتور  مورد نظر با موفقیت افزوده شد .

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
$DB->Select('customer', '*', 'id = ' . $DB->Escape($_GET['id']));
?>
<script language="javascript" src="../js/FormatMoney.js" ></script >
<script language="javascript" >

    function addItem() {
        var itemName = $('#itemName').val();
        var itemQuantity = $('#itemQuantity').val();
        var itemPrice = $('#itemPrice').val();
        var itemUnit = $('#itemUnit').val();
        itemPrice = parseInt(itemPrice);
        itemPrice2 = FormatMoney(itemPrice, '', ' ریال ', ',', '', 0, 0);
        var count = $('#itemCount').val();
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
    }
    $(document).ready(function () {
        $('#type').change(function () {
            var x = $('#type').val();
            if (x == 1) {
                $('#period').addClass('hidden');
            }
            else {
                $('#period').removeClass('hidden');
            }
        });
    });
</script >

<div class="page-header" >
    <h1 >افزودن فاکتور به <a href="edituser.php?id=<?php echo  $DB->Escape($_GET['id']); ?>" class="titleA">
        <?php echo $DB->Select_Result[0]['name']; ?></a></h1 >
</div >
<div class="row" >
    <div class="col-lg-12" >
        <?php if (isset($status)) echo $status; ?>
        <form method="POST" role="form" class="form-horizontal" >
            <fieldset >
                <div class="form-group" >
                    <div class="col-xs-2" >
                        <label for="type" class="control-label" >نوع</label >
                    </div >
                    <div class="col-xs-10 inputsize" >
                        <select name="type" id="type" class="form-control" onchange="toggleType();" >
                            <option value="1" >عادی</option >
                            <option value="2" > دوره ای</option >
                        </select >
                    </div >
                </div >

                <!-- /clearfix -->

                <div class="form-group" >
                    <div class="col-xs-2" >
                        <label for="invoice_name" class="control-label" >عنوان فاکتور</label >
                    </div >
                    <div class="col-xs-10 inputsize" >
                        <input type="text" size="30" name="invoice_name" id="invoice_name" class="form-control" >
                    </div >
                </div >
                <div class="form-group" >
                    <div class="col-xs-2" >
                        <label for="gen_date" class="control-label" >زمان سررسید</label >
                    </div >
                    <div class="col-xs-10 inputsize" >
                        <input type="text" size="30" name="gen_date" id="gen_date" class="form-control sdsds"
                               placeholder="1393-05-24" value="<?php echo curjdate(); ?>" >
                    </div >
                </div >
                <script >
                    var objCal5 = new AMIB.persianCalendar('gen_date', {
                            extraInputID: 'gen_date',
                            extraInputFormat: 'yyyy-mm-dd',
                            btnClassName: 'hidden'
                        }
                    );
                </script >
                <!-- /clearfix -->

                <div class="form-group hidden" id="period" >
                    <div class="col-xs-2" >
                        <label for="period" class="control-label" >مدت تناوب</label >
                    </div >
                    <div class="col-xs-10 inputsize" >
                        <input type="text" size="30" name="period" id="period" class="form-control"
                               placeholder="مدت به روز" >
                    </div >
                </div >

                <!-- /clearfix -->

                <div class="form-group" >
                    <div class="col-xs-2" >
                        <label for="exp_date" class="control-label" >زمان انقضا</label >
                    </div >
                    <div class="col-xs-10 inputsize" >
                        <input type="text" size="30" name="exp_date" id="exp_date" class="form-control"
                               placeholder="1393-05-24" value="<?php
                        $date = new DateTime(curjdate());
                        $date->add(new DateInterval('P180D'));
                        echo $date->format('Y-m-d');
                        ?>" >
                    </div >
                </div >
                <script >
                    var objCal2 = new AMIB.persianCalendar('exp_date', {
                            extraInputID: 'exp_date',
                            extraInputFormat: 'yyyy-mm-dd',
                            btnClassName: 'hidden'
                        }
                    );
                </script >
                <!-- /clearfix -->

                <div class="form-group" >
                    <div class="col-xs-2" >
                        <label for="status" class="control-label" >وضعیت پرداخت</label >
                    </div >
                    <div class="col-xs-10 inputsize" >
                        <select name="status" id="status" class="form-control" >
                            <option value="0" >پرداخت نشده</option >
                            <option value="1" >پرداخت شده</option >
                            <option value="2" > لغو شده</option >
                        </select >
                    </div >
                </div >

                <!-- /clearfix -->

                <div class="form-group" >
                    <div class="col-xs-2" >
                        <label for="notification_id" class="control-label" >قانون اطلاع رسانی</label >
                    </div >
                    <div class="col-xs-10 inputsize" >
                        <select name="notification_id" id="notification_id" class="form-control" >
                            <option value="0" >انتخاب کنید</option >
                            <?php
                            $DB2 = new database;
                            $DB2->Select('notification', 'id,name');
                            $num = $DB2->Select_Rows;
                            $i = 0;
                            while ($i < $num) {
                                echo " <option value='" . $DB2->Select_Result[$i]['id'] . "'>" . $DB2->Select_Result[$i]['name'] . "</option>";
                                $i++;
                            }
                            ?>
                        </select >
                    </div >
                </div >

                <!-- /clearfix -->

                <div class="form-group" >
                    <div class="col-xs-2" >
                        <label for="discount" class="control-label" >درصد تخفیف</label >
                    </div >
                    <div class="col-xs-10 inputsize" >
                        <input type="text" size="30" name="discount" id="discount" class="form-control"
                               placeholder="درصد تخفیف به عدد" >
                    </div >
                </div >

                <!-- /clearfix -->

                <div class="form-group" >
                    <div class="col-xs-2" >
                        <label for="vat" class="control-label" >ارزش افزوده</label >
                    </div >
                    <div class="col-xs-10 inputsize" >
                        <input type="text" size="30" name="vat" id="vat" class="form-control"
                               placeholder="ارزش افزوده به عدد" >
                    </div >
                </div >

                <!-- /clearfix -->

                <div class="form-group" >
                    <div class="col-xs-2" >
                        <label for="descr" class="control-label" >توضیحات</label >
                    </div >
                    <div class="col-xs-10 inputsize" >
                        <textarea size="40" name="descr" id="descr" class="form-control" > </textarea >
                    </div >
                </div >

                <!-- /clearfix -->

                <input type="hidden" name="itemCount" id="itemCount" value="0" >
                <input type="hidden" name="customer_id" value="<?php echo $DB->Select_Result[0]['id']; ?>" >

                <div class="form-group" >
                    <div >
                        <div class="col-xs-2" >
                            <label for="vat" class="control-label" >آیتم ها</label >
                        </div >
                        <span class="itemName" >عنوان </span > <span class="itemQuantity" >تعداد</span > <span
                            class="itemUnit" >واحد</span > <span class="itemPrice" >قیمت</span > <span
                            class="itemButton" ></span >

                        <div class="col-xs-10 " >
                            <input type="text" name="itemName" id="itemName" class="itemName" >
                            <input type="text" name="itemQuantity" id="itemQuantity" class="itemQuantity" value="1" >
                            <input type="text" name="itemUnit" id="itemUnit" class="itemUnit" placeholder="عدد" >
                            <input type="text" name="itemPrice" id="itemPrice" class="itemPrice" placeholder="ریال" >
                            <span id="itemButton" class="itemButton" ><a href="javascript:addItem();"
                                                                         class="btn btn-info btn-xs" >افزودن</a > </span >
                        </div >
                    </div >
                </div >

                <!-- /clearfix -->

                <!------------------------------------>

                <div class="form-group" >
                    <div class="col-xs-2" >
                        <label style="height:20px;" id="itemLabel" class="control-label" > &nbsp;</label >
                    </div >
                    <div class="col-xs-10  " id="itemTable" ></div >
                </div >

                <!-- /clearfix -->

                <!------------------------------>

                <div class="form-group" >
                    <div class="col-sm-offset-2 col-sm-10" >
                        <input type="submit" value="ذخیره" name="sub" class="btn btn-primary" >
                        &nbsp;
                        <button class="btn btn-default" type="button" onClick="document.location.href='listuser.php'" >
                            لغو
                        </button >
                    </div >
                </div >
            </fieldset >
        </form >
    </div >
</div >
</div>
<?php
include("../footer.php");
?>
</div>

<!-- /container -->

</body></html>