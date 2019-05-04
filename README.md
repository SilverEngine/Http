<p align="center">
    <img src="https://thumb.ibb.co/fDOcRG/goodone.jpg">
</p>

<p align="center">
    <a href="#">
        <img src="https://img.shields.io/badge/Licence-MIT-green.svg" alt="LICENSE" title="LICENSE">
    </a>
    <a href="#">
        <img src="https://img.shields.io/badge/PHP-%3E%3D%207.1-blue.svg" alt="PHP 7" title="PHP 7">
    </a>
    <a href="#">
        <img src="https://img.shields.io/badge/Alpha-V1.0.0-yellow.svg" alt="version" title="version">
    </a>
</p>

## HTTP

Http Components for @SilverEngine

## Basic usage

### Client

```php
use Silver\Components\Http\Client;

$baseUri     = 'https://jsonplaceholder.typicode.com/';
$defaultBody = [
        "userId" => 1,
        "title"  => "Test",
        "body"   => "Test",
];

$client   = new Client([Client::BASE_URI => $baseUri]);

$response = $client->get('posts');
$response = $client->post('posts', [
        RequestOptions::JSON => $defaultBody,
]);
$response = $client->put('posts/1', [
        RequestOptions::JSON => $defaultBody,
]);
$response = $client->delete('posts/1');
```

```php
$response = $client->get('posts/:id/comments', [
        RequestOptions::QUERY => [
                'id' => 1,
        ],
]);
```

### Curl

**Method GET**
```php
use Silver\Components\Http\Curl;

$headers = [
        'Accept: application/json',
        'User-Agent: SilverEngineHttp/1.0.0 PHP/7.1',
];

$curl = new Curl([
        CURLOPT_URL           => 'https://jsonplaceholder.typicode.com/posts',
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER    => $headers,
]);

$curl->create();
```

**Method POST**
```php
use Silver\Components\Http\Curl;

$headers = [
        'Accept: application/json',
        'User-Agent: SilverEngineHttp/1.0.0 PHP/7.1',
        'Content-Type: application/json',
];

$curl = new Curl([
        CURLOPT_URL           => 'https://jsonplaceholder.typicode.com/posts',
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER    => $headers,
        CURLOPT_POSTFIELDS    => json_encode([
                "userId" => 1,
                "title"  => "Test",
                "body"   => "Test",
        ]),
]);

        $curl->create();
```

**Method PUT**
```php
use Silver\Components\Http\Curl;

$headers = [
        'Accept: application/json',
        'User-Agent: SilverEngineHttp/1.0.0 PHP/7.1',
        'Content-Type: application/json',
];

$curl = new Curl([
        CURLOPT_URL           => 'https://jsonplaceholder.typicode.com/posts/1',
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_HTTPHEADER    => $headers,
        CURLOPT_POSTFIELDS    => json_encode([
                "userId" => 1,
                "title"  => "Test",
                "body"   => "Test",
        ]),
]);

$curl->create();
```

**Method DELETE**
```php
use Silver\Components\Http\Curl;

$headers = [
        'Accept: application/json',
        'User-Agent: SilverEngineHttp/1.0.0 PHP/7.1',
];

$curl = new Curl([
        CURLOPT_URL           => 'https://jsonplaceholder.typicode.com/posts/1',
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_HTTPHEADER    => $headers,
]);

$curl->create();
```

**Update options**
```php
$headers = [
        'Accept: application/json',
        'User-Agent: SilverEngineHttp/1.0.0 PHP/7.1',
];
$options = [
        CURLOPT_URL           => 'https://jsonplaceholder.typicode.com/posts/1',
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_HTTPHEADER    => $headers,
];
$curl    = new Curl($options);
$curl->updateOptions([
                CURLOPT_HTTPHEADER => [
                        'test',
                ],
]);
```
```
//Result before updating $curl->getOptions()[CURLOPT_HTTPHEADER]
Array
(
    [0] => 'Accept: application/json',
    [1] => 'User-Agent: SilverEngineHttp/1.0.0 PHP/7.1',
)

//Result after updating $curl->getOptions()[CURLOPT_HTTPHEADER]
Array
(
    [0] => "test"
)
```

## License

The Http Components for Silver Engine is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
