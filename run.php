<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

require_once("./XlsExchange.php");

(new \XlsExchange())
    ->setInputFile('./tmp/input.json')
    ->setOutputFile('./tmp/output.xls')
    ->export();

(new \XlsExchange())
    ->setFtpAttribute([
        'ftp_host'     => 'speedtest.tele2.net',
        'ftp_login'    => '',
        'ftp_password' => '',
        'ftp_dir'      => '',
    ])
    ->setInputFile('./tmp/input.json')
    ->setOutputFile('output.xls')
    ->export();
