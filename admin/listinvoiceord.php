<?php
require("header.php");//checks session and login
$page = 'p2';
include "top.php";//includes html top and menu
?>
<script language="javascript">

    function delinvoice(id) {

        $.fancybox({

            modal: false,

            minWidth: 260,

            autoHeight: true,

            scrolling: 'no',

            openEffect: 'none',

            openSpeed: 100,

            closeEffect: 'none',

            closeSpeed: 100,

            preload: true,

            overlayShow: true,

            content: "<div>آیا از حذف اطمینان دارید؟</div><div class='actions'><input type='button' value='بلی'  onclick='del(" + id + ");' class='btn btn-primary'>&nbsp;<input type='button' value='خير' class='btn btn-default'  onclick='$.fancybox.close();'></div>",

            helpers: {


                overlay: {

                    css: {'background': 'rgba(192, 196, 207, 0.8)'},

                    locked: false

                }

            }

        });

    }


    function del(ID) {

        $.post("delinvoice.php", {id: ID}, function (data) {

            if (data == 0) {

                $.fancybox.close();

                $('#error2').fadeIn(600);

            }

            else if (data == 1) {

                $.fancybox.close();

                $('#a' + ID).hide(2000);

                $('#error1').fadeIn(600);

            }

            else {

                $.fancybox.close();

                $('#error2').fadeOut(600);

            }

        });

    }

</script>
<?php $DB= new database; ?>
<div class="page-header">
    <h1 >صورتحسابهاي عادي <a href="edituser.php?id=<?php echo  $DB->Escape($_GET['id']); ?>" class="titleA">
            <?php echo resolveCus($DB->Escape($_GET['id'])); ?></a></h1 >
</div>

        <div class="alert alert-success" id="error1" style="display:none;"> صورتحساب مورد نظر با موفقیت حذف شد .</div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="hidden-xs">شناسه</th>
                <th>مشتری</th>
                <th>عنوان</th>
                <th class="hidden-xs">وضعیت</th>
                <th>جمع فاکتور</th>
                <th class="hidden-xs">سر رسید</th>
                <th class="hidden-xs">یادآوری</th>
                <th>عملیات مدیریت</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $DB = new database;
            if (!isset($_GET['p']) || empty($_GET['p'])) {
                $lim = 1;
            } else {
                $lim = $DB->Escape($_GET['p']);
            }
            if ($lim == 'All') {
                $pagin = ' ';
            } else {
                $start = $lim * $_SESSION['resultperpage'];
                $start -= $_SESSION['resultperpage'];
                $end = $_SESSION['resultperpage'];
                $pagin = 'LIMIT ' . $start . ',' . $end;
            }
            $customer = '';
            if (isset($_GET['id'])) {
                $customer = ' and customer_id = ' . $DB->Escape($_GET['id']);
            }
            $DB->Select('invoice INNER JOIN customer on invoice.customer_id=customer.id ', '*,customer.name as cusName,invoice.id as inID', 'type = 1' . $customer . ' order by invoice.status ASC, invoice.gen_date DESC ' . $pagin);
            $totalRows = $DB->TotalRows;
            $num = $DB->Select_Rows;
            $i = 0;
            while ($i < $num) {
                ?>
                <tr id="<?php echo 'a' . $DB->Select_Result[$i]['inID']; ?>">
                    <th class="hidden-xs"><?php echo $DB->Select_Result[$i]['inID']; ?></th>
                    <td><?php echo $DB->Select_Result[$i]['cusName']; ?></td>
                    <td><?php echo $DB->Select_Result[$i]['invoice_name']; ?></td>
                    <td class="hidden-xs"><?php echo findStatus($DB->Select_Result[$i]['status']); ?></td>
                    <td><?php echo number_format(invoiceBalance($DB->Select_Result[$i]['inID'])); ?> ریال</td>
                    <td class="hidden-xs <?php if ((date("Y-m-d H:i:s") > $DB->Select_Result[$i]['gen_date']) && $DB->Select_Result[$i]['status'] == 0) echo 'red'; ?>"><?php echo sfdate($DB->Select_Result[$i]['gen_date']); ?></td>
                    <td class="hidden-xs"><?php echo $DB->Select_Result[$i]['reminded_count']; ?></td>
                    <td><a href="viewinvoiceord.php?id=<?php echo $DB->Select_Result[$i]['inID']; ?>"
                           title="مشاهده"><img alt="مشاهده" src="../images/view-invoice.png"></a>&nbsp; <a
                            href="editinvoiceord.php?id=<?php echo $DB->Select_Result[$i]['inID']; ?>"
                            title="ویرایش"><img alt="ویرایش" src="../images/edit.png"></a>&nbsp; <a
                            href="javascript:delinvoice('<?php echo $DB->Select_Result[$i]['inID']; ?>')"
                            title="حذف"><img alt="حذف" src="../images/delete.png"></a></td>
                </tr>
                <?php
                $i++;
            } ?>
            </tbody>
        </table>
        <nav>
            <ul class="pagination">
                <?php
                $p = isset($_GET['p']) ? $_GET['p'] : 1;
                $id = isset($_GET['id']) ? 'id=' . $_GET['id'] : '';
                $fdot = $ldot = 0;
                $pages = ceil($totalRows / $_SESSION['resultperpage']);
                if ($p > 1) {
                    $href = $p - 1;
                    echo '<li><a href="?' . $id . '&p=' . $href . '"><span aria-hidden="true">&laquo;</span><span class="sr-only">قبلی</span></a></li>';
                }
                for ($i = 1; $i <= $pages; $i++) {
                    if ($pages < 6 || $i == $pages || $i == 1 || ($i > $p - 3 && $i < $p + 3)) {
                        $act = $i == $p ? 'class="active"' : ' ';
                        echo '<li ' . $act . '><a href="?' . $id . '&p=' . $i . '">' . $i . '</a></li>';
                    } else {
                        if ($fdot == 0) {
                            echo '<li class="disabled"><a >...</a></li>';
                            $fdot = 1;
                        }
                        if ($fdot == 1 && $i > $p && $ldot == 0 && $p >= 5) {
                            echo '<li  class="disabled"><a >...</a></li>';
                            $ldot = 1;
                        }
                    }
                }
                if ($p < $pages) {
                    $href = $p + 1;
                    echo '<li><a href="?' . $id . '&p=' . $href . '"><span aria-hidden="true">&raquo;</span><span class="sr-only">بعدی</span></a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</div>
</div>
</div>
<?php
include("../footer.php");
?>
</body></html>