<?php
require("header.php");//checks session and login
$page = 'p1';
include "top.php";//includes html top and menu
?>

<script language="javascript">

    function deluser(id) {

        $.fancybox({

            modal: false,

            minWidth: 300,

            autoHeight: true,

            scrolling: 'no',

            openEffect: 'none',

            openSpeed: 100,

            closeEffect: 'none',

            closeSpeed: 100,

            preload: true,

            overlayShow: true,

            content: "<div>آیا از حذف اطمینان دارید؟</div><div class='actions'><input type='button' value='بلی'  onclick='del(" + id + ");' class='btn btn-primary'>&nbsp;<input type='button' value='خير' class='btn btn-default' onclick='$.fancybox.close();'></div>",

            helpers: {
                overlay: {

                    css: {'background': 'rgba(192, 196, 207, 0.8)'},

                    locked: false

                }

            }

        });

    }


    function del(ID) {

        $.post("deluser.php", {id: ID}, function (data) {

            if (data == 0) {

                $.fancybox.close();

                $('#error2').fadeIn(600);

            }

            else if (data == 1) {

                $.fancybox.close();

                $('#a' + ID).hide(2000);

                $('#error1').fadeIn(600);

            }

            else if (data == 2) {

                $.fancybox.close();

                $('#error3').fadeIn(600);

            }

            else {

                $.fancybox.close();

                $('#error2').fadeOut(600);

            }

        });

    }

</script>

<div class="page-header">

    <h1>لیست مشتری ها</h1>

</div>

<div class="row">

    <div class="actions" style="margin-left:20px;">

        <input type="button" onClick="document.location.href='adduser.php';" value="افزودن مشتری"
               class="btn btn-primary">

    </div>

    <div class="col-lg-12">


        <div class="alert alert-danger" id="error2" style="display:none;">

            خطایی در ارسال پارامتر ها به وجود آمده است! دوباره امتحان کنید .

        </div>

        <div class="alert alert-success" id="error1" style="display:none;">

            مشترک مورد نظر با موفقیت حذف شد .

        </div>

        <div class="alert alert-danger" id="error3" style="display:none;">

            ابتدا میبایست فاکتورهای انتصاب یافته به مشترک را حذف نمائید.

        </div>


        <table class="table table-striped">

            <thead>

            <tr>

                <th class="hidden-xs">شناسه</th>

                <th>نام</th>

                <th class="hidden-xs">گروه</th>

                <th>اعتبار</th>

                <th>مانده بدهی</th>

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
            $DB->Select('customer', '*', 'display = 1 ' . $pagin);
            $totalRows = $DB->TotalRows;
            $num = $DB->Select_Rows;
            $i = 0;
            while ($i < $num) {
                ?>

                <tr id="<?php echo 'a' . $DB->Select_Result[$i]['id']; ?>">

                    <th class="hidden-xs"><?php echo $DB->Select_Result[$i]['id']; ?></th>

                    <td><?php echo $DB->Select_Result[$i]['name']; ?></td>

                    <td class="hidden-xs"><?php echo findGrp($DB->Select_Result[$i]['grp']); ?> </td>

                    <td><?php echo $DB->Select_Result[$i]['credit']; ?> ریال</td>

                    <td><?php echo number_format(userBalance($DB->Select_Result[$i]['id'])); ?> ریال</td>

                    <td><a href="addinvoice.php?id=<?php echo $DB->Select_Result[$i]['id']; ?>"
                           title="افزودن فاکتور"><img alt="افزودن فاکتور" src="../images/add-invoice.png"></a>&nbsp;

                        <a href="listinvoiceord.php?id=<?php echo $DB->Select_Result[$i]['id']; ?>"
                           title="فاکتورها"><img alt="فاکتورها" src="../images/invoices.png"></a>&nbsp;

                        <a href="edituser.php?id=<?php echo $DB->Select_Result[$i]['id']; ?>" title="ویرایش مشتري"><img
                                alt="ویرایش" src="../images/edit-invoice.png"></a>&nbsp;

                        <a href="transaction.php?id=<?php echo $DB->Select_Result[$i]['id']; ?>" title="تراکنشها"><img
                                alt="تراکنشها" src="../images/transactions.png"></a>&nbsp;

                        <a href="javascript:deluser('<?php echo $DB->Select_Result[$i]['id']; ?>')"
                           title="حذف مشتري"><img alt="حذف" src="../images/delete.png"></a></td>

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
                $fdot = $ldot = 0;
                $pages = ceil($totalRows / $_SESSION['resultperpage']);
                if ($p > 1) {
                    $href = $p - 1;
                    echo '<li><a href="?p=' . $href . '"><span aria-hidden="true">&laquo;</span><span class="sr-only">قبلی</span></a></li>';
                }
                for ($i = 1; $i <= $pages; $i++) {
                    if ($pages < 6 || $i == $pages || $i == 1 || ($i > $p - 3 && $i < $p + 3)) {
                        $act = $i == $p ? 'class="active"' : ' ';
                        echo '<li ' . $act . '><a href="?p=' . $i . '">' . $i . '</a></li>';
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
                    echo '<li><a href="?p=' . $href . '"><span aria-hidden="true">&raquo;</span><span class="sr-only">بعدی</span></a></li>';
                }
                ?>

            </ul>

        </nav>

    </div>

</div>

</div>

<?php
include("../footer.php");
?>

</div>

<!-- /container -->


</body></html>