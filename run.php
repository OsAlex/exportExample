<?php

(new XlsExchange())
    ->setInputFile('./tmp/input.json')
    ->setOutputFile('./tmp/output.xls')
    ->export();

(new XlsExchange())
    ->setFtpAttribute([
        'ftp_host'     => '',
        'ftp_login'    => '',
        'ftp_password' => '',
        'ftp_dir'      => '',
    ])
    ->setInputFile('./tmp/input.json')
    ->setOutputFile('./tmp/output.xls')
    ->export();
