<?php namespace Vgalkin\Dpd;


class DpdSoapClient extends \SoapClient
{
    /**
     *
     */
    protected $soapOptions = [
        'connection_timeout' => 20,
    ];

    /**
     * @param string $wdsl
     */
    public function __construct($wdsl)
    {
        parent::__construct($wdsl, $this->soapOptions);
    }


    /**
     * @param string $method
     * @param array  $params
     * @param string $wrap
     * @return mixed|string
     */
    public function call(string $method, array $params, string $wrap): \stdClass
    {
        // перевести ключи запроса в camelCase
        $params = $this->convertDataForService($params);
        $params = $wrap ? [$wrap => $params] : $params;
        return $this->$method($params);
    }

    /**
     * Конвертирует переданные данные в формат внешнего API
     * Под конвертацией понимается:
     * - перевод названий параметров в camelCase
     *
     * @param  array $data
     * @return array
     */
    protected function convertDataForService($data)
    {
        $ret = [];
        foreach ($data as $key => $value) {
            $key = $this->underScoreToCamelCase($key);
            $ret[$key] = is_array($value) ? $this->convertDataForService($value) : $value;
        }

        return $ret;
    }


    /**
     * Переводит строку из under_score в camelCase
     *
     * @param  string  $string                   строка для преобразования
     * @param  boolean $capitalizeFirstCharacter первый символ строчный или прописной
     * @return string
     */
    public static function underScoreToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        // символы разного регистра
        if (/*strtolower($string) != $string &&*/
            strtoupper($string) != $string
        ) {
            return $string;
        }

        $string = strtolower($string);
        $string = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $string[0] = strtolower($string[0]);
        }

        return $string;
    }
}
