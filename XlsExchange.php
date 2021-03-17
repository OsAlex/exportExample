<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 17.03.2021
 * Time: 23:11
 */

class XlsExchange implements ExchangeInterface
{
    protected $path_to_input_json_file;
    protected $path_to_output_xlsx_file;
    protected $ftp_host;
    protected $ftp_login;
    protected $ftp_password;
    protected $ftp_dir;

    private $ftp_status = false;

    public function export()
    {
        if ($this->ftp_status) {
            return (new XlsExchangeByFtp($this->ftp_host, $this->ftp_login, $this->ftp_password, $this->ftp_dir))
                ->export();
        }

        return $this->export();
    }

    public function setInputFile($value)
    {
        $this->path_to_input_json_file = $value;
    }

    /**
     * @param $value
     */
    public function setOutputFile($value)
    {
        $this->path_to_output_xlsx_file = $value;
    }
}