/**
 * Authors: Reza Behroozi & Shahin Gharghi
 * Date: 05/04/15
 * Time: 08:07 PM
 * Website: http://PersianAdmins.ir
 * This liberary is free to use under GNU/GPL license
 */
function doText(input, output) {
    var textHezargan = {
        0: '',
        1: 'هزار',
        2: 'ميليون',
        3: 'ميليارد',
        4: 'هزار ميليارد',
        5: 'ميليون ميليارد',
        6: 'ميليارد ميليارد',
        7: 'هزار ميليارد ميليارد'
    }
    var val = document.getElementById(input).value;
    var len = val.length;
    var octs = Math.ceil(len / 3);
    var j = 1000;
    var nums = [];
    for (i = 0; i < octs; i++) {
        nums[i] = val % j;
        val = Math.floor(val / j);
    }
    var ret = '';
    for (i = octs - 1; i >= 0; i--) {
        ret += oct(nums[i]) + ' ';
        if (nums[i] != 0) {
            ret += textHezargan[i];
        }
        if (i != 0 && nums[i - 1] != 0) {
            ret += ' و ';
        }
    }
    document.getElementById(output).innerHTML = ret + ' ريال';
}
//calculating each octed
function oct(a) {
    var textYekan = {0: '', 1: 'يك', 2: 'دو', 3: 'سه', 4: 'چهار', 5: 'پنج', 6: 'شش', 7: 'هفت', 8: 'هشت', 9: 'نه'}
    var textDahgan = {
        0: '',
        20: 'بيست',
        30: 'سي',
        40: 'چهل',
        50: 'پنجاه',
        60: 'شصت',
        70: 'هفتاد',
        80: 'هشتاد',
        90: 'نود'
    }
    var textSadgan = {
        0: '',
        100: 'يكصد',
        200: 'دويست',
        300: 'سيصد',
        400: 'چهارصد',
        500: 'پانصد',
        600: 'ششصد',
        700: 'هفتصد',
        800: 'هشتصد',
        900: 'نهصد'
    }
    var textSecDahgan = {
        10: 'ده',
        11: 'يازده',
        12: 'دوازده',
        13: 'سيزده',
        14: 'چهارده',
        15: 'پانزده',
        16: 'شانزده',
        17: 'هفده',
        18: 'هجده',
        19: 'نوزده'
    }
    var sadgan = Math.floor(a / 100) * 100;
    var b = a % 100;
    var dahgan = Math.floor(b / 10) * 10;
    var yekan = b % 10;
    if (yekan == 0) {
        vavDahgan = ' ';
    }
    else {
        vavDahgan = ' و ';
    }
    if (b == 0) {
        vavSadgan = ' ';
    }
    else {
        vavSadgan = ' و ';
    }
    if (yekan != 0) {
        var ret = textYekan[yekan];
    }
    else {
        var ret = '';
    }
    if (dahgan != 0) {
        if (dahgan == 10) {
            ret = textSecDahgan[b]
        }
        else {
            ret = textDahgan[dahgan] + vavDahgan + ret;
        }
    }
    if (sadgan != 0) {
        ret = textSadgan[sadgan] + vavSadgan + ret;
    }
    return ret;
}