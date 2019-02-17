<?php
/**
 * Curl.php
 */

namespace Silver\Components;

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
    
    /**
     * Curl constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = []) {
        $this->options = $options;
        $this->headers = [];
    }
    
    public function create() {
        $defaults = [
                CURLOPT_HEADER         => FALSE,
                CURLOPT_FRESH_CONNECT  => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT        => 4,
                CURLOPT_PROTOCOLS      => CURLPROTO_HTTP | CURLPROTO_HTTPS,
                CURLOPT_HEADERFUNCTION => $this->headerFunction(),
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, ($this->options + $defaults));
        
        if ($result = curl_exec($ch)) {
            $this->body       = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
            $this->statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $this->curlInfo   = curl_getinfo($ch);
        } else {
            $this->error = curl_error($ch);
        }
        
        curl_close($ch);
    }
    
    /**
     * @return array
     */
    public function getOptions(): array {
        return $this->options;
    }
    
    public function updateOptions(array $options): void {
        $auxOptions = $options;
        
        foreach ($auxOptions as $key => $value) {
            if (isset($this->options[$key])) {
                unset($options[$key]);
                $this->options[$key] = $value;
            }
        }
        
        $this->options += $options;
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
        return function($ch, string $header) {
            $auxHeader = trim($header);
            
            if ($auxHeader !== '') {
                $this->headers[] = $auxHeader;
            }
            
            return strlen($header);
        };
    }
}
