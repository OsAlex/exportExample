<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 17.03.2021
 * Time: 23:11
 */

class XlsExchangeByFtp extends XlsExchange implements ExchangeInterface
{
    protected string $path_to_input_json_file;
    protected string $path_to_output_xlsx_file;
    protected string $ftp_host;
    protected string $ftp_login;
    protected string $ftp_password;
    protected string $ftp_dir;

    protected array $data;

    public function __construct($ftp_host, $ftp_login, $ftp_password, $ftp_dir)
    {
        $this->ftp_host = $ftp_host;
        $this->ftp_login = $ftp_login;
        $this->ftp_password = $ftp_password;
        $this->ftp_dir = $ftp_dir;
    }

    /**
     * @return void
     */
    public function export()
    {
        $file = fopen($this->path_to_output_xlsx_file, 'w');
        foreach ($this->data as $row) {
            fputcsv($file, $row);
        }

        try {
            $ftp = ftp_connect($this->ftp_host, '22', '60');
            ftp_login($ftp, $this->ftp_login, $this->ftp_password);
            ftp_fput($ftp, $this->ftp_dir . '/' . $this->path_to_output_xlsx_file, $file, FTP_BINARY);
        } catch (Exception $exception) {
            throw new DomainException('Error FTP connect');
        }

        rewind($file);
    }

    /**
     * @param $value
     * @return ExchangeInterface
     */
    public function setInputFile($value): ExchangeInterface
    {
        $this->path_to_input_json_file = $value;

        $this->data = parent::parse($this->path_to_input_json_file);

        return $this;
    }

    /**
     * @param $value
     * @return ExchangeInterface
     */
    public function setOutputFile($value): ExchangeInterface
    {
        $this->path_to_output_xlsx_file = $value;

        return $this;
    }
}