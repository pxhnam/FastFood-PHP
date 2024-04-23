<?php
class Validator
{
    public static function isValidDate($day, $month, $year)
    {
        if (!is_numeric($day) || !is_numeric($month) || !is_numeric($year)) {
            return false;
        }

        if ($month < 1 || $month > 12) {
            return false;
        }

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        if ($day < 1 || $day > $daysInMonth) {
            return false;
        }

        if ((($year % 4 == 0) && ($year % 100 != 0)) || ($year % 400 == 0)) {
            if ($month === 2 && $day > 29) {
                return false;
            }
        } else {
            if ($month === 2 && $day > 28) {
                return false;
            }
        }
        return true;
    }
}
