<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 17.03.2021
 * Time: 23:11
 */

class XlsExchangeByFtp extends XlsExchange implements ExchangeInterface
{
    protected $path_to_input_json_file;
    protected $path_to_output_json_file;
    protected $ftp_host;
    protected $ftp_login;
    protected $ftp_password;
    protected $ftp_dir;

    public function __construct($ftp_host, $ftp_login, $ftp_password, $ftp_dir)
    {
        $this->ftp_host = $ftp_host;
        $this->ftp_login = $ftp_login;
        $this->ftp_password = $ftp_password;
        $this->ftp_dir = $ftp_dir;
    }

    public function export()
    {
        // TODO: Implement export() method.
    }

    public function setInputFile()
    {
        // TODO: Implement setInputFile() method.
    }

    public function setOutputFile()
    {
        return $this;
    }
}