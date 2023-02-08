<?php
include "config.php";

// $records = mysqli_query($connect,"SELECT * FROM `patient_data` WHERE DATE(datetime) >= (DATE(NOW()) - INTERVAL 10 DAY)");
$dailyrecords = mysqli_query($connect,"SELECT * FROM `patient_data` WHERE TIMESTAMPDIFF(HOUR, datetime, NOW()) < 24");
$records = mysqli_query($connect,"SELECT * FROM `patient_data`");
$chartrecords = mysqli_query($connect,"SELECT * FROM `patient_data`");
$lastrecord = mysqli_query($connect,"SELECT * FROM `patient_data` ORDER BY id DESC LIMIT 1");
$firstrecord = mysqli_query($connect,"SELECT * FROM `patient_data` WHERE TIMESTAMPDIFF(HOUR, datetime, NOW()) < 24 ORDER BY id ASC LIMIT 1");

