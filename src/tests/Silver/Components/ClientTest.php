<?php
/**
 * ClientTest.php
 */

namespace Silver\Components;

use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase {
    
    const DEFAULT_URI = 'https://jsonplaceholder.typicode.com/';
    
    private static function defaultBody() {
        return [
                "userId" => 1,
                "title"  => "Test",
                "body"   => "Test",
        ];
    }
    
    public function testGetBasic() {
        $client   = new Client([Client::BASE_URI => self::DEFAULT_URI]);
        $response = $client->get('posts');
        self::assertEquals(200, $response->getStatusCode());
    }
    
    public function testPostBasic() {
        $client   = new Client([Client::BASE_URI => self::DEFAULT_URI]);
        $response = $client->post('posts', [
                RequestOptions::JSON => self::defaultBody(),
        ]);
        self::assertEquals(201, $response->getStatusCode());
    }
    
    public function testPutBasic() {
        $client   = new Client([Client::BASE_URI => self::DEFAULT_URI]);
        $response = $client->put('posts/1', [
                RequestOptions::JSON => self::defaultBody(),
        ]);
        self::assertEquals(200, $response->getStatusCode());
    }
    
    public function testDeleteBasic() {
        $client   = new Client([Client::BASE_URI => self::DEFAULT_URI]);
        $response = $client->delete('posts/1');
        self::assertEquals(200, $response->getStatusCode());
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Magic request methods require a URI and optional options array.
     */
    public function testValidatesArguments() {
        (new Client())->get();
    }
    
    public function testClientHasOption() {
        $client = new Client([
                Client::BASE_URI        => self::DEFAULT_URI,
                RequestOptions::HEADERS => ['bar' => 'fo'],
                RequestOptions::TIMEOUT => 2,
        ]);
        $this->assertArrayHasKey(Client::BASE_URI, $client->getAllConfig());
        $this->assertSame(self::DEFAULT_URI, $client->getConfig(Client::BASE_URI));
        $this->assertArrayHasKey(RequestOptions::HEADERS, $client->getAllConfig());
        $this->assertArrayHasKey(RequestOptions::TIMEOUT, $client->getAllConfig());
    }
    
    public function testMergeDefaultOptions() {
        $client = new Client([
                RequestOptions::HEADERS => ['User-agent' => 'baz'],
        ]);
        $this->assertArrayHasKey('User-agent', $client->getConfig(RequestOptions::HEADERS));
        $this->assertSame('baz', $client->getConfig(RequestOptions::HEADERS)['User-agent']);
        $this->assertArrayHasKey('Accept', $client->getConfig(RequestOptions::HEADERS));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Headers must be an array.
     */
    public function testValidatesHeaders() {
        $client = new Client();
        $client->get('/test', [RequestOptions::HEADERS => 'baz']);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Query must be a string or array.
     */
    public function testValidatesQuery() {
        $client = new Client();
        $client->get('/test', [RequestOptions::QUERY => FALSE]);
    }
    
    public function testBuildUriGetRequest() {
        $client   = new Client([Client::BASE_URI => self::DEFAULT_URI]);
        $response = $client->get('posts/:id/comments', [
                RequestOptions::QUERY => [
                        'id' => 1,
                ],
        ]);
        self::assertEquals(200, $response->getStatusCode());
        
        $response = $client->get('comments', [
                RequestOptions::QUERY => [
                        'postId' => 1,
                ],
        ]);
        self::assertEquals(200, $response->getStatusCode());
    }
    
}
