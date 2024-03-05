<?php
namespace App\Core\Box;

class Tools {

    public static function formatDate($datetime) {
        $datetime_elements = explode(" ", $datetime);
        $date = $datetime_elements[0];

        $date_elements = explode("-", $date);
        $year = $date_elements[0];
        $month = $date_elements[1];
        $day = $date_elements[2];

        $month_lbl = "";
        switch ($month) {
            case "1": $month_lbl = "Enero";
                break;
            case "2": $month_lbl = "Febrero";
                break;
            case "3": $month_lbl = "Marzo";
                break;
            case "4": $month_lbl = "Abril";
                break;
            case "5": $month_lbl = "Mayo";
                break;
            case "6": $month_lbl = "Junio";
                break;
            case "7": $month_lbl = "Julio";
                break;
            case "8": $month_lbl = "Agosto";
                break;
            case "9": $month_lbl = "Septiembre";
                break;
            case "10": $month_lbl = "Octubre";
                break;
            case "11": $month_lbl = "Noviembre";
                break;
            case "12": $month_lbl = "Diciembre";
                break;
        }

        return $day . " " . $month_lbl . ", " . $year;
    }

    public static function formatTime($time) {
        $time_elements = explode(":", $time);
        $output = "";
        if ($time_elements[0] != "00") {
            $output .= $time_elements[0] . ":";
        }

        $output .= $time_elements[1] . ":" . $time_elements[2];
        return $output;
    }

//ID management
    public static function encryptId($id) {
        if ($id == 0) {
            return false;
        }
        $output = "rse";
        $expanded_id = $id * 17;
        $chars = str_split($expanded_id . "");

        if (count($chars) > 1) {
            $aux = $chars[0];
            $chars[0] = $chars[count($chars) - 1];
            $chars[count($chars) - 1] = $aux;
        }

        for ($i = 0; $i < count($chars); $i++) {
            $output .= self::getAlteredIndex($chars[$i]) . self::getLetterByIndex($chars[count($chars) - ($i + 1)]);
        }

        return $output;
    }

    public static function decryptId($alter_id) {
        $output = "";
        $chars = str_split($alter_id);
        $combined = array_splice($chars, 3);

        $filtered = array();
        for ($i = 0; $i < count($combined); $i = $i + 2) {
            $filtered[] = $combined[$i];
        }

        if (count($filtered) > 1) {
            $aux = $filtered[0];
            $filtered[0] = $filtered[count($filtered) - 1];
            $filtered[count($filtered) - 1] = $aux;
        }

        for ($i = 0; $i < count($filtered); $i++) {
            $output .= self::getOriginalIndex($filtered[$i]);
        }

        return intval($output) / 17;
    }

    private static function getLetterByIndex($index) {
        $letter = false;
        switch ($index) {
            case 1: $letter = "c";
                break;
            case 2: $letter = "m";
                break;
            case 3: $letter = "r";
                break;
            case 4: $letter = "j";
                break;
            case 5: $letter = "v";
                break;
            case 6: $letter = "t";
                break;
            case 7: $letter = "w";
                break;
            case 8: $letter = "b";
                break;
            case 9: $letter = "p";
                break;
            case 0: $letter = "a";
                break;
        }

        return $letter;
    }

    private static function getAlteredIndex($index) {
        $alt_index = false;
        switch ($index) {
            case 1: $alt_index = 7;
                break;
            case 2: $alt_index = 3;
                break;
            case 3: $alt_index = 9;
                break;
            case 4: $alt_index = 0;
                break;
            case 5: $alt_index = 5;
                break;
            case 6: $alt_index = 1;
                break;
            case 7: $alt_index = 4;
                break;
            case 8: $alt_index = 6;
                break;
            case 9: $alt_index = 2;
                break;
            case 0: $alt_index = 8;
                break;
        }

        return $alt_index;
    }

    private static function getOriginalIndex($alt_index) {
        $index = false;
        switch ($alt_index) {
            case 7: $index = 1;
                break;
            case 3: $index = 2;
                break;
            case 9: $index = 3;
                break;
            case 0: $index = 4;
                break;
            case 5: $index = 5;
                break;
            case 1: $index = 6;
                break;
            case 4: $index = 7;
                break;
            case 6: $index = 8;
                break;
            case 2: $index = 9;
                break;
            case 8: $index = 0;
                break;
        }

        return $index;
    }

}
