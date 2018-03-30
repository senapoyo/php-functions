<?php
/**
 * is_holiday
 *
 * @version    1.0
 * @author     senapoyo
 *
 * 指定された日が日本の祝日であるか判定する
 *
 * @params  integer  $year   年
 * @params  integer  $month  月
 * @params  integer  $day    日
 * @return  boolean          真偽
 */

/*
// e.g.
var_dump(is_holiday(1990, 11, 12)); // true
*/

function is_holiday($year, $month, $day) {
    return _is_holiday($year, $month, $day);
}

function _is_holiday($year, $month, $day, $check = false) {

    $year = (int) $year;
    $month = (int) $month;
    $day = (int) $day;

    $weekday = date('w', mktime(0, 0, 0, $month, $day, $year)); // 0 = 日曜 ~ 6 = 土曜

    // 祝日
    $holiday = array();
    if ($year >= 1948) $holiday[1][] = 1; // 元日
    if ($year >= 1967) $holiday[2][] = 11; // 建国記念の日
    if ($year >= 1948) $holiday[4][] = 29; // 天皇誕生日→みどりの日(1989)→昭和の日(2007)
    if ($year >= 1948) $holiday[5][] = 3; // 憲法記念日
    if ($year >= 2007) $holiday[5][] = 4; // みどりの日
    if ($year >= 1948) $holiday[5][] = 5; // こどもの日
    if ($year >= 2016) $holiday[8][] = 11; // 山の日
    if ($year >= 1927) $holiday[11][] = 3; // 明治節→文化の日(1948)
    if ($year >= 1878) $holiday[11][] = 23; // 新嘗祭→勤労感謝の日(1948)
    if ($year >= 1989 && $year <= 2019) $holiday[12][] = 23; // 天皇誕生日(暫定)

    // 過去の祝日
    if ($year >= 1878 && $year <= 1948) $holiday[1][] = 3; // 元始祭
    if ($year >= 1878 && $year <= 1948) $holiday[1][] = 5; // 新年宴会
    if ($year >= 1948 && $year <= 1999) $holiday[1][] = 15; // 成人の日
    if ($year >= 1878 && $year <= 1912) $holiday[1][] = 30; // 孝明天皇祭
    if ($year >= 1878 && $year <= 1948) $holiday[2][] = 11; // 紀元節
    if ($year >= 1878 && $year <= 1948) $holiday[4][] = 3; // 神武天皇祭
    if ($year >= 1996 && $year <= 2002) $holiday[7][] = 20; // 海の日
    if ($year >= 1913 && $year <= 1947) $holiday[7][] = 30; // 明治天皇祭
    if ($year >= 1913 && $year <= 1947) $holiday[8][] = 31; // 天長節
    if ($year >= 1966 && $year <= 2002) $holiday[9][] = 15; // 敬老の日
    if ($year >= 1878 && $year <= 1879) $holiday[9][] = 17; // 神嘗祭
    if ($year >= 1966 && $year <= 1999) $holiday[10][] = 10; // 体育の日
    if ($year >= 1880 && $year <= 1947) $holiday[10][] = 17; // 神嘗祭
    if ($year >= 1913 && $year <= 1947) $holiday[10][] = 31; // 天長節祝日
    if ($year >= 1878 && $year <= 1912) $holiday[11][] = 3; // 天長節

    // 皇室慶弔行事に伴う休日
    if ($year == 1959) $holiday[4][] = 10; // 皇太子・明仁親王の結婚の儀
    if ($year == 1989) $holiday[2][] = 24; // 昭和天皇の大喪の礼
    if ($year == 1990) $holiday[11][] = 12; // 即位の礼正殿の儀
    if ($year == 1993) $holiday[6][] = 9; // 皇太子・皇太子徳仁親王の結婚の儀

    if (isset($holiday[$month]) && in_array($day, $holiday[$month])) return true;

    // ハッピーマンデー制度
    if ($weekday == 1
     && ($year >= 2000 && $month == 1 && $day >= 8 && $day <= 14 // 成人の日(1月第2月曜日)
      || $year >= 2003 && $month == 7 && $day>= 15 && $day <= 21 // 海の日(7月第3月曜日)
      || $year >= 2003 && $month == 9 && $day >= 15 && $day <= 21 // 敬老の日(9月第3月曜日)
      || $year >= 2000 && $month == 10 && $day >= 8 && $day <= 14) // 体育の日(10月第2月曜日)
    ) return true;

    // 春季皇霊祭、春分の日(1878~2099年)
    if ($month == 3
     && (($year >= 1878 && $year <= 1893 && ($year % 4 == 0 || $year % 4 == 1) && $day == 20)
      || ($year >= 1878 && $year <= 1893 && ($year % 4 == 2 || $year % 4 == 3) && $day == 21)
      || ($year >= 1894 && $year <= 1899 && $year % 4 == 3 && $day == 21)
      || ($year >= 1894 && $year <= 1899 && $year % 4 != 3 && $day == 20)
      || ($year >= 1900 && $year <= 1923 && $year % 4 == 3 && $day == 22)
      || ($year >= 1900 && $year <= 1923 && $year % 4 != 3 && $day == 21)
      || ($year >= 1924 && $year <= 1959 && $day == 22)
      || ($year >= 1960 && $year <= 1991 && $year % 4 == 0 && $day = 20)
      || ($year >= 1960 && $year <= 1991 && $year % 4 != 0 && $day = 21)
      || ($year >= 1992 && $year <= 2023 && ($year % 4 == 0 || $year % 4 == 1) && $day == 20)
      || ($year >= 1992 && $year <= 2023 && ($year % 4 == 2 || $year % 4 == 3) && $day == 21)
      || ($year >= 2024 && $year <= 2055 && $year % 4 == 3 && $day == 21)
      || ($year >= 2024 && $year <= 2055 && $year % 4 != 3 && $day == 20)
      || ($year >= 2056 && $year <= 2091 && $day == 20)
      || ($year >= 2092 && $year <= 2099 && $year % 4 == 0 && $day == 19)
      || ($year >= 2092 && $year <= 2099 && $year % 4 != 0 && $day == 20))
     ) return true;

    // 秋季皇霊祭、秋分の日(1878~2099年)
    if ($month == 9
     && (($year >= 1878 && $year <= 1887 && $day == 23)
      || ($year >= 1888 && $year <= 1899 && $year % 4 == 0 && $day == 22)
      || ($year >= 1888 && $year <= 1899 && $year % 4 != 0 && $day == 23)
      || ($year >= 1900 && $year <= 1919 && $year % 4 == 0 && $day == 23)
      || ($year >= 1900 && $year <= 1919 && $year % 4 != 0 && $day == 24)
      || ($year >= 1920 && $year <= 1947 && ($year % 4 == 0 && $year % 4 == 1) && $day == 23)
      || ($year >= 1920 && $year <= 1947 && ($year % 4 == 2 && $year % 4 == 3) && $day == 24)
      || ($year >= 1948 && $year <= 1979 && $year % 4 == 3 && $day == 24)
      || ($year >= 1948 && $year <= 1979 && $year % 4 != 3 && $day == 23)
      || ($year >= 1980 && $year <= 2011 && $day == 23)
      || ($year >= 2012 && $year <= 2043 && $year % 4 == 0 && $day == 22)
      || ($year >= 2012 && $year <= 2043 && $year % 4 != 0 && $day == 23)
      || ($year >= 2044 && $year <= 2075 && ($year % 4 == 0 && $year % 4 == 1) && $day == 22)
      || ($year >= 2044 && $year <= 2075 && ($year % 4 == 2 && $year % 4 == 3) && $day == 23)
      || ($year >= 2076 && $year <= 2099 && $year % 4 == 3 && $day == 23)
      || ($year >= 2076 && $year <= 2099 && $year % 4 != 3 && $day == 22))
    ) return true;

    // 振替休日(1973年4月以降)
    if (($year >= 1974 || $year == 1973 && $month >= 4) && $weekday == 1 && !$check) {
        $yesterdayU = strtotime($year . '-' . $month . '-' . $day . ' -1 days');
        if (_is_holiday(date('Y', $yesterdayU), date('n', $yesterdayU), date('j', $yesterdayU), true)) return true;
    }

    // 前日と翌日が祝日だった場合は国民の休日(1988年以降)
    if ($year >= 1988 && !$check) {
        $yesterdayU = strtotime($year . '-' . $month . '-' . $day . ' -1 days');
        $tomorrowU = strtotime($year . '-' . $month . '-' . $day . ' +1 days');
        if (_is_holiday(date('Y', $yesterdayU), date('n', $yesterdayU), date('j', $yesterdayU), true)
         && _is_holiday(date('Y', $tomorrowU), date('n', $tomorrowU), date('j', $tomorrowU), true)
        ) return true;
    }

    return false;
}