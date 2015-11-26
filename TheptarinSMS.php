<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TheptarinSMS
 * 1.อ่าน MySql
 * 2.บันทึกไฟล์ CSV
 * @author noree 
 */
class TheptarinSMS {

    /**
     * ข้อมูลนัดหมายจาก appointment_sms ใน ttr_mse
     */
    protected $appointment = array();

    /**
     * ชื่อไฟล์ csv
     */
    protected $file_name = './CSVFiles/TheptarinSMS';

    public function __construct() {
        $this->get_appointment();
        $this->save();
    }

    /**
     * ดึงข้อมูลจาก ttr_mse.appointment_sms เก็บไว้ที่ appointment
     * @access private
     */
    private function get_appointment() {
        $dsn = 'mysql:host=10.1.99.6;dbname=ttr_mse';
        $username = 'orr-projects';
        $password = 'orr-projects';
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );
        $db_conn = new PDO($dsn, $username, $password, $options);
        $sql = "SELECT `hn` AS `HN`, 'รพ.เทพธารินทร์' AS `Clinic`, date_format( `app_date`, '%Y-%m-%d' ) AS `Appt Date`, date_format( `app_from_time`, '%H:%i' ) AS `Appt Time`, date_format( `app_to_time`, '%H:%i' ) AS `Appt To Time`, `tel` AS `Mobile Number`, concat('คุณ ',`patient_name`) AS `Patient Name`, `language` AS `Language`, if( `doctor_id` > 1000,concat( 'แพทย์',substring_index( `doctor_name`, ' ', - 3 )), `doctor_name` ) AS `Doctor Prefix`, `doctor_remark` AS `Appt Service` FROM `ttr_mse`.`appointment_sms` AS `appointment_sms`";
        $stmt = $db_conn->prepare($sql);
        $stmt->execute();
        $this->appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);
        print_r($this->appointment);
        return;
    }

    /**
     * อ่านข้อมูลจาก appointment มาเขียนลงบนไฟล์ csv
     * @access private
     */
    private function save() {
        $myfile = fopen($this->file_name . '.csv', "w") or die("Unable to open file!");
        foreach ($this->appointment as $value) {
            $line = $value["HN"] . "," . $value["Clinic"] . "," . $value["Appt Date"] . "," . $value["Appt Time"] . "," . $value["Appt To Time"] . "," . $value["Mobile Number"] . "," . $value["Patient Name"] . "," . $value["Language"] . "," . $value["Doctor Prefix"] . "," . $value["Appt Service"] . "\r\n";
            fwrite($myfile, $line);
        }
        fclose($myfile);
    }
}

$my = new TheptarinSMS();
