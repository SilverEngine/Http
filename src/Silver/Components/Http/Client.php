<?php
/**
 * Client.php
 */

namespace Silver\Components\Http;

/**
 * @method Response get(string $uri, array $options = [])
 * @method Response put(string $uri, array $options = [])
 * @method Response post(string $uri, array $options = [])
 * @method Response delete(string $uri, array $options = [])
 * Class Client
 * @author Nicolás Marulanda P.
 */
class Client {
    
    const BASE_URI = 'base_uri';
    
    /** @var array */
    private $config;
    
    /** @var Curl */
    private $curl;
    
    /**
     * Client constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = []) {
        $this->curl = new Curl();
        $this->configureDefault($config);
    }
    
    /**
     * Create and send an HTTP request.
     *
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @return Response
     */
    public function request(string $method, string $uri, array $options = []): Response {
        $method      = strtoupper($method);
        $options     = $this->defaultOptions($options);
        $curlOptions = [
                CURLOPT_URL           => $this->buildUri($uri, $method, $options),
                CURLOPT_CUSTOMREQUEST => $method,
        ];
        
        $this->applyOptions($method, $options, $curlOptions);
        $this->curl->updateOptions($curlOptions);
        $this->curl->create();
        
        return $this->createResponse($this->curl);
    }
    
    public function __call($name, $arguments) {
        if (count($arguments) < 1) {
            throw new \InvalidArgumentException('Magic request methods require a URI and optional options array.');
        }
        
        $uri     = $arguments[0];
        $options = isset($arguments[1]) ? $arguments[1] : [];
        
        return $this->request($name, $uri, $options);
    }
    
    /**
     * @param string $option
     *
     * @return array
     */
    public function getConfig(string $option): ?array {
        if (!isset($this->config[$option])) {
            
            return NULL;
        }
        
        $config = $this->config[$option];
        
        if (!is_array($config)) {
            $config = [$config];
        }
        
        return $config;
    }
    
    public function getAllConfig(): array {
        return $this->config;
    }
    
    private function configureDefault(array $config) {
        $default      = [
                RequestOptions::HEADERS => [
                        'Accept'          => 'application/json',
                        'User-Agent'      => 'SilverEngineHttp/1.0.0 PHP/7.1',
                        //'Host'            => '',
                        'Accept-Encoding' => '',
                        'Expect'          => '',
                ],
        ];
        $this->config = Utils::arrayMergeRecursiveDistinct($config, $default);
        
        if (isset($config[RequestOptions::CURL])) {
            $this->curl->updateOptions($config[RequestOptions::CURL]);
        }
    }
    
    private function createResponse(Curl $curl): Response {
        $headers = $curl->getHeaders();
        //Example: HTTP/1.1 200 OK
        $startLine = explode(' ', array_shift($headers), 3);
        $headers   = $this->parseHeader($headers);
        
        return new Response($curl->getBody(), $headers, $startLine[1]);
    }
    
    private function buildUri(string $uri, string $method, array $options): string {
        $baseUrl = isset($options[self::BASE_URI]) ? Utils::trimUri($options[self::BASE_URI]) . '/' : '';
        
        if (strcasecmp($method, 'get') == 0 && isset($options[RequestOptions::QUERY])) {
            $query = $options[RequestOptions::QUERY];
            
            if (is_array($query)) {
                $auxQuery = $query;
                
                foreach ($auxQuery as $key => $value) {
                    $uri = str_replace(":$key", $value, $uri);
                    unset($query[$key]);
                }
            }
            
            if (count($query) > 0) {
                $uri .= '?' . http_build_query($query);
            }
        }
        
        return $baseUrl . Utils::trimUri($uri);
    }
    
    private function defaultOptions(array $options): array {
        if (array_key_exists(RequestOptions::HEADERS, $options) && !$this->headersIsValid($options)) {
            throw new \InvalidArgumentException('Headers must be an array.');
        }
        
        if (array_key_exists(RequestOptions::QUERY, $options) && !$this->queryIsValid($options)) {
            throw new \InvalidArgumentException('Query must be a string or array.');
        }
        
        return Utils::arrayMergeRecursiveDistinct($options, $this->config);
    }
    
    private function headersIsValid(array $options): bool {
        return is_array($options[RequestOptions::HEADERS]);
    }
    
    private function queryIsValid(array $options): bool {
        return is_array($options[RequestOptions::QUERY]) || is_string($options[RequestOptions::QUERY]);
    }
    
    private function parseHeader(array $headers): array {
        $result = [];
        
        foreach ($headers as $header) {
            $explode                     = explode(':', $header, 2);
            $result[trim($explode[0])][] = isset($explode[1]) ? trim($explode[1]) : NULL;
        }
        
        return $result;
    }
    
    private function applyOptions(string $method, array &$options, array &$curlOptions) {
        $curlOptPostFields = '';
        $contentType       = '';
        
        if (isset($options[RequestOptions::JSON])) {
            $curlOptPostFields = json_encode($options[RequestOptions::JSON]);
            $contentType       = 'application/json';
        } elseif (isset($options[RequestOptions::FORM_PARAMS])) {
            $curlOptPostFields = http_build_query($options[RequestOptions::FORM_PARAMS]);
            $contentType       = 'application/x-www-form-urlencoded';
        }
        
        if (strcasecmp($method, 'get') != 0) {
            $curlOptions[CURLOPT_POSTFIELDS]                  = $curlOptPostFields;
            $options[RequestOptions::HEADERS]['Content-Type'] = $contentType;
        }
        
        if (isset($options[RequestOptions::HEADERS]['Accept'])) {
            $options[RequestOptions::HEADERS]['Accept'] = 'application/json';
        }
        
        if (isset($options[RequestOptions::HEADERS])) {
            $headers = $options[RequestOptions::HEADERS];
            
            foreach ($headers as $key => $value) {
                $value = (string)$value;
                
                if ($value === '') {
                    $curlOptions[CURLOPT_HTTPHEADER][] = "$key;";
                } else {
                    $curlOptions[CURLOPT_HTTPHEADER][] = "$key: $value";
                }
            }
        }
    }
    
}
