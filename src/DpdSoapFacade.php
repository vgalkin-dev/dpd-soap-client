<?php namespace Vgalkin\Dpd;


/**
 *
 */
class DpdSoapFacade
{

    /**
     * Режми работы - тестовый/боевой сервер
     */
    protected $testMode;

    /**
     *
     */
    protected $wdsl = 'http://ws.dpd.ru/services/%s?wsdl';

    /**
     * Разные методы раскиданы по разным url
     */
    protected $urls = [
        'order2', // url для заказов
        'label-print', // url для этикеток и наклеек
        'tracing', // статусы посылок
        'geography' // пвз
    ];

    /**
     * DpdSoapClient[] Хранит коллекцию soap-клиентов для каждого wsdl-usl из $this->urls
     */
    protected $collection = [];


    /**
     * @param $testMode
     */
    public function __construct($testMode)
    {
        $this->testMode = $testMode;
    }


    /**
     * @param string $url - url клиента из коллекции в котором пытаемся вызвать метод
     * @param string $method
     * @param array  $params
     * @param string $wrap
     * @return mixed
     * @throws \Exception - soap-сервер сгенерит свои исключения
     */
    public function call(string $url, string $method, array $params, string $wrap) :\StdClass
    {
        $method = strtolower($method);
        $url    = strtolower($url);

        // создать soap-обработчик для указанного url
        if(! in_array($url, $this->collection)) {
            $this->collection[$url] = new DpdSoapClient(sprintf($this->resolveWsdl($this->wdsl, $this->testMode), $url));
        }

        /** @var $client DpdSoapClient */
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
