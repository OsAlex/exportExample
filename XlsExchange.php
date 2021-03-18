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
    protected array $data;

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
            (new XlsExchangeLocal($this))->export();
        }
    }

    /**
     * @param $value
     * @return ExchangeInterface
     */
    public function setInputFile($value): ExchangeInterface
    {
        $this->path_to_input_json_file = $value;

        $this->data = $this->parse($this->path_to_input_json_file);

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
        $content = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', file_get_contents($path_to_input_json_file));
        $content = json_decode($content, TRUE);

        if ($content === null || empty($content['items']) || json_last_error() !== JSON_ERROR_NONE) {
            throw new DomainException('Bad json format');
        }

        $result = [];
        foreach ($content['items'] as $item) {
            if (!empty($item['item']['barcode']) && $this->isEAN13($item['item']['barcode'])) {
                $result[] = [
                    'Id'       => $item['item']['id'],
                    'ШК'       => $item['item']['barcode'],
                    'Название' => $item['item']['name'],
                    'Кол-во'   => $item['quantity'],
                    'Сумма'    => $item['price'],
                ];
            }
        }

        return $result;
    }

    /**
     * @param $code
     * @return bool
     */
    private function isEAN13($code)
    {
        $sumEvenIndexes = 0;
        $sumOddIndexes  = 0;

        $codeAsArray = array_map('intval', str_split($code));

        if (count($codeAsArray) != 13) {
            return false;
        };

        for ($i = 0; $i < count($codeAsArray)-1; $i++) {
            if ($i % 2 === 0) {
                $sumOddIndexes  += $codeAsArray[$i];
            } else {
                $sumEvenIndexes += $codeAsArray[$i];
            }
        }

        $rest = ($sumOddIndexes + (3 * $sumEvenIndexes)) % 10;

        if ($rest !== 0) {
            $rest = 10 - $rest;
        }

        return $rest === $codeAsArray[12];
    }
}