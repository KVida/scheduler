<?php
//входные данные
$data = [
    ["name"=>"1", "start_At"=>"2020-02-16 22:03:00", "duration"=>"00:30","period"=>"4h"],
    ["name"=>"2", "start_At"=>"2020-02-16 18:30:00", "duration"=>"01:00","period"=>"12h"]
];

$count_data = count($data);
$param = 0;

//переобразование входных данных
$data_new = [];
for ($i=0; $i < $count_data; $i++) {
    $p = [];

    $p["name"] = $data[$i]["name"];

    $arr_date_time_now = new DateTime($data[$i]["start_At"]);

    $time_now = $arr_date_time_now->format('U');
    $p['start_At'] = $time_now;

    $duration =  getTime($data[$i]["duration"], 'm');
    $p['end_At'] = $time_now + $duration;

    $period =getTime($data[$i]["period"], 'h');

    $p['new_start_date'] = $time_now + $period;

    $data_new [] = $p;
}

//запуск
$count_data_new = count($data_new);
while (true){
    for ($i=0; $i < $count_data_new; $i++) {
        $name_flower = $data_new[$i]["name"];
        $time_start = $data_new[$i]["start_At"];
        $time_end = $data_new[$i]["end_At"];
        $new_start_date = $data_new[$i]["new_start_date"];

        $date_time_now = new DateTime();
        $time_now = $date_time_now->format('U');

        if (($time_now == $time_start || $time_now > $time_start) && $time_now < $time_end) {
            echo on($name_flower);
        } elseif ($time_now == $time_end) {
            //$data[$i]["start_At"] = getDateTimeFormat($new_start_date);

            $data_new[$i]["start_At"] = $new_start_date;
            $data_new[$i]["end_At"] = $new_start_date + $duration;;
            $data_new[$i]["new_start_date"] = $new_start_date + $period;
            echo off($name_flower);
        }
    }

    $param++;
    if ($param < 100) {
        break;
    }
}

//функция для перевода в секунды
function getTime ($time, $flag_h_or_m) {
    if ($flag_h_or_m == 'm') {
        $arr_time = explode(':', $time);
        $time_m = $arr_time[0] * 1;
        $time_s = $arr_time[1] * 1;

        $time_sek = $time_m * 60 + $time_s;
    } elseif ($flag_h_or_m == 'h') {
        $time_h = trim($time, 'h');

        $time_sek = $time_h * 3600;
    } else {
        $time_sek = 0;
    }

    return $time_sek;
}

//функция для получения даты и времени в формате
function getDateTimeFormat($dt_U) {
    $dateTimeFormat = date('Y-m-d H:i:s', $dt_U);

    return $dateTimeFormat;
}

function on($name_fl) {
   return "On $name_fl";
}

function off($name_fl) {
    return "Off $name_fl";
}