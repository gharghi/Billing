<?php
include_once '/home/persianadm/domains/bill.karaneshan.ir/public_html/cron/class.logindb.inc.php';
//calculate the new gen_date
function gdate($date, $period)
{
    include_once "/home/persianadm/domains/bill.karaneshan.ir/public_html/inc/jdf.php";
    $date2 = tr_num(jdate("Y-m-d", strtotime($date)));
    $d = explode('-', $date2);
    $new = $d[2] + $period;
    if ($d[1] < 7) {
        $new++;
    }
    return jalali_to_gregorian($d[0], $d[1], $new, '-');
}

//display current date in jalali
function curjdate()
{
    return date("Y-m-d");
}

//=================end of defining functions
$DB = new logindb;
$DB->Select('invoice', '*', 'type = 2');
if ($DB->Select_Rows > 0) {
    $num = $DB->Select_Rows;
    for ($i = 0; $i < $num; $i++) {
        $checkDate = gdate($DB->Select_Result[$i]['gen_date'], -7);
        if (strtotime($checkDate) > strtotime(curjdate())) {
            continue;
        }
        $newGenDate = gdate($DB->Select_Result[$i]['gen_date'], $DB->Select_Result[$i]['period']);
        if (strtotime($DB->Select_Result[$i]['exp_date']) <= strtotime($newGenDate)) {
            continue;
        }
        $insert = $DB->Insert('invoice', 'customer_id,notification_id,type,period,status,discount,vat,descr,track_num,exp_date,gen_date,invoice_name,date', '( "' . $DB->Select_Result[$i]['customer_id'] . '","' . $DB->Select_Result[$i]['notification_id'] . '","1","0","0",
		"' . $DB->Select_Result[$i]['discount'] . '","' . $DB->Select_Result[$i]['vat'] . '","' . $DB->Select_Result[$i]['descr'] . '",
		"' . $DB->Select_Result[$i]['track_num'] . '","' . $DB->Select_Result[$i]['exp_date'] . '","' . $DB->Select_Result[$i]['gen_date'] . '","' . $DB->Select_Result[$i]['invoice_name'] . '",CURDATE() )');
        if ($insert) {
            $newID = $DB->Insert_ID;
            $set['gen_date'] = $newGenDate;
            $update = $DB->Update('invoice', $set, 'id = ' . $DB->Select_Result[$i]['id']);
            if ($update) {
                $DB1 = new logindb;
                $DB1->Select('item', '*', 'invoice_id = ' . $DB->Select_Result[$i]['id']);
                $num2 = $DB1->Select_Rows;
                for ($j = 0; $j < $num2; $j++) {
                    $insert = $DB1->Insert('item', 'invoice_id,name,price,quantity,unit', '( "' . $newID . '","' . $DB1->Select_Result[$j]['name'] . '",
					"' . $DB1->Select_Result[$j]['price'] . '","' . $DB1->Select_Result[$j]['quantity'] . '","' . $DB1->Select_Result[$j]['unit'] . '" )');
                }
            }
        }
    }
}
?>