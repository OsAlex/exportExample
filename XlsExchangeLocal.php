<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 17.03.2021
 * Time: 23:11
 */

class XlsExchangeLocal extends XlsExchange implements ExchangeInterface
{
    protected string $path_to_input_json_file;
    protected string $path_to_output_xlsx_file;

    private array $json;

    /**
     * @return void
     */
    public function export()
    {
        $file = fopen($this->path_to_output_xlsx_file, 'w');

        foreach ($this->json as $fields) {
            fputcsv($file, $fields);
        }

        fclose($file);
    }

    /**
     * @param $value
     * @return ExchangeInterface
     */
    public function setInputFile($value): ExchangeInterface
    {
        $this->path_to_input_json_file = $value;

        $this->json = parent::parse($this->path_to_input_json_file);

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