<?php

namespace Usend\Delivery\Dpd;


class DpdSoapFacade
{
    /**
     *
     */
    protected $wdsl = 'http://ws.dpd.ru/services/%s?wsdl';

    /**
     * Разные методы раскиданы по разным url
     */
    protected $urls = [
        'order2', // url для заказов
        'label-print' // url для этикеток и наклеек
    ];

    /**
     * DpdSoapClient[] Хранит коллекцию клиентов
     */
    protected $collection = [];


    /**
     * @param $testMode
     */
    public function __construct($testMode)
    {
        // Создаём разные soap-клиенты для вызова разных soap-методов по разным url
        foreach ($this->urls as $urlPart) {
            $this->collection[$urlPart] = new DpdSoapClient(sprintf($this->resolveWsdl($this->wdsl, $testMode), $urlPart));
        }
    }


    /**
     * @param string $url - url клиента из коллекции в котором пытаемся вызвать метод
     * @param string $method
     * @param array  $params
     * @param string $wrap
     * @return \stdClass
     */
    public function call(string $url, string $method, array $params, string $wrap): \stdClass
    {
        /** @var $client DpdSoapClient */
        $method = strtolower($method);
        $url = strtolower($url);
        $client = $this->collection[$url];
        return $client->call($method, $params, $wrap);
    }


    /**
     * Конвертирует переданный uri в соответствии с тестовым режимом
     *
     * @param string $uri
     * @param bool   $testMode
     * @return string
     */
    public function resolveWsdl($uri, bool $testMode)
    {
        if ($testMode) {
            return str_replace('ws.dpd.ru', 'wstest.dpd.ru', $uri);
        }

        return $uri;
    }

}
