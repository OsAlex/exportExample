<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 17.03.2021
 * Time: 23:11
 */

class XlsExchange implements ExchangeInterface
{
    protected string $path_to_input_json_file;
    protected string $path_to_output_xlsx_file;
    protected string $ftp_host;
    protected string $ftp_login;
    protected string $ftp_password;
    protected string $ftp_dir;

    private bool  $ftp_status = FALSE;
    private array $json;

    /**
     * @param array $params
     * @return ExchangeInterface
     */
    public function setFtpAttribute(array $params): ExchangeInterface
    {
        if (isset($params['ftp_host']) && isset($params['ftp_login']) && isset($params['ftp_password']) && isset($params['ftp_dir'])) {
            $this->ftp_host     = $params['ftp_host'];
            $this->ftp_login    = $params['ftp_login'];
            $this->ftp_password = $params['ftp_password'];
            $this->ftp_dir      = $params['ftp_dir'];

            $this->ftp_status = true;

            return (new XlsExchangeByFtp($this->ftp_host, $this->ftp_login, $this->ftp_password, $this->ftp_dir));
        }

        return (new XlsExchangeLocal());
    }

    /**
     * @return void
     */
    public function export()
    {
        if ($this->ftp_status) {
            (new XlsExchangeByFtp($this->ftp_host, $this->ftp_login, $this->ftp_password, $this->ftp_dir))->export();
        } else {
            (new XlsExchangeLocal())->export();
        }
    }

    /**
     * @param $value
     * @return ExchangeInterface
     */
    public function setInputFile($value): ExchangeInterface
    {
        $this->path_to_input_json_file = $value;

        $this->json = $this->parse($this->path_to_input_json_file);

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

    /**
     * @param $path_to_input_json_file
     * @return array
     */
    public function parse($path_to_input_json_file): array
    {
        try {
            $result = json_decode($path_to_input_json_file);
        } catch (Exception $e) {
            throw new DomainException('Bad json format: ' . $e->getMessage());
        }

        return $result;
    }
}