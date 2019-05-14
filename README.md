Пакет для взаимодействия с SOAP-сервером DPD
=======================

Общие сведения
---------------
Пакет создан для систематизации и упрощения обращений к различным url сервиса dpd.


Подключение с помощью composer
---------------
{
    "require": {
    "vgalkin-dev/dpd-soap-client": "dev-master",
  },
  "repositories": {
    "vgalkin-dev/dpd-soap-client": {
      "type": "vcs",
      "url": "https://github.com/vgalkin-dev/vgalkin-dev/dpd-soap-client.git"
    }
  }
}

$testMode = true;

$params = [
    'auth' => [
        'clientNumber' => '{dpd client number}',
        'clientKey'    => '{dpd personal key}',
    ],
    'header' => [
        // see dpd-manual
    ],
    'order' => [
        // see dpd-manual
    ]
];


$api = new \Usend\Delivery\Dpd\DpdSoapFacade($testMode);
// пример создания заказа
$api->call('order2', 'createOrder', $params, 'orders');


Рсширение
---------------
Для расширения функционала необходимо добавить соответсвующие url-адреса в массив Vgalkin\Dpd\DpdSoapFacade::$urls

Список доступных методов и соответсивующих им url-адресов необходимо брать из документации dpd



