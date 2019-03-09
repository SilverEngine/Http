<?php
/**
 * CurlTest.php
 */

namespace Silver\Components\Http;

use PHPUnit\Framework\TestCase;

class CurlTest extends TestCase {
    
    public function testBasicGet() {
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
        $this->assertEquals(200, $curl->getStatusCode());
    }
    
    public function testBasicPost() {
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
        $this->assertEquals(201, $curl->getStatusCode());
    }
    
    public function testBasicPut() {
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
        $this->assertEquals(200, $curl->getStatusCode());
    }
    
    public function testBasicDelete() {
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
        $this->assertEquals(200, $curl->getStatusCode());
    }
    
    public function testUpdateOptions() {
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
        $this->assertCount(9, $curl->getOptions());
        $this->assertCount(2, $curl->getOptions()[CURLOPT_HTTPHEADER]);
        $this->assertSame('DELETE', $curl->getOptions()[CURLOPT_CUSTOMREQUEST]);
        
        $curl->updateOptions($options);
        $this->assertCount(9, $curl->getOptions());
        $this->assertCount(2, $curl->getOptions()[CURLOPT_HTTPHEADER]);
        $this->assertSame('DELETE', $curl->getOptions()[CURLOPT_CUSTOMREQUEST]);
        
        $this->assertNotContains('test', $curl->getOptions()[CURLOPT_HTTPHEADER]);
        $curl->updateOptions([
                CURLOPT_HTTPHEADER => [
                        'test',
                ],
        ]);
        $this->assertCount(1, $curl->getOptions()[CURLOPT_HTTPHEADER]);
        $this->assertContains('test', $curl->getOptions()[CURLOPT_HTTPHEADER]);
    }
    
}
