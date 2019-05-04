<?php
/**
 * Curl.php
 */

namespace Silver\Components\Http;

/**
 * Class Curl
 * @author Nicolás Marulanda P.
 */
class Curl {
    
    /** @var array */
    private $headers;
    
    /** @var string */
    private $body;
    
    /** @var array */
    private $options;
    
    /** @var string */
    private $error;
    
    /** @var int */
    private $statusCode;
    
    /** @var array */
    private $curlInfo;
    
    /** @var int */
    private $errno;
    
    /**
     * Curl constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = []) {
        $this->optionsDefault($options);
        $this->headers = [];
    }
    
    public function create() {
        $ch = curl_init();
        curl_setopt_array($ch, $this->options);
        
        if ($result = curl_exec($ch)) {
            $this->body       = $result;
            $this->statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $this->curlInfo   = curl_getinfo($ch);
        }
        
        $this->error = curl_error($ch);
        $this->errno = curl_errno($ch);
        
        curl_close($ch);
    }
    
    /**
     * @return array
     */
    public function getOptions(): array {
        return $this->options;
    }
    
    public function updateOptions(array $options): void {
        $this->options = $options + $this->options;
    }
    
    /**
     * @return array
     */
    public function getHeaders(): array {
        return $this->headers;
    }
    
    /**
     * @return string
     */
    public function getBody(): string {
        return $this->body;
    }
    
    /**
     * @return string
     */
    public function getError(): string {
        return $this->error;
    }
    
    /**
     * @return int
     */
    public function getErrno(): int {
        return $this->errno;
    }
    
    /**
     * @return int
     */
    public function getStatusCode(): int {
        return $this->statusCode;
    }
    
    /**
     * @return array
     */
    public function getCurlInfo(): array {
        return $this->curlInfo;
    }
    
    private function headerFunction(): \Closure {
        return function($ch, string $header): int {
            $auxHeader = trim($header);
            
            if ($auxHeader !== '') {
                $this->headers[] = $auxHeader;
            }
            
            return strlen($header);
        };
    }
    
    private function optionsDefault(array $options = []) {
        $this->options = [
                CURLOPT_HEADER         => FALSE,
                CURLOPT_FRESH_CONNECT  => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT        => 4,
                CURLOPT_PROTOCOLS      => CURLPROTO_HTTP | CURLPROTO_HTTPS,
                CURLOPT_HEADERFUNCTION => $this->headerFunction(),
        ];
        $this->updateOptions($options);
    }
}
