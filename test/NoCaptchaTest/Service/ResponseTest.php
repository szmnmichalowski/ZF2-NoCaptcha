<?php
namespace NoCaptchaTest\Service;

use NoCaptcha\Service\Response as CaptchaResponse;
use Zend\Http\Response;


class ResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CaptchaResponse
     */
    protected $response;

    public function setUp()
    {
        $this->response = new CaptchaResponse();
    }

    public function testSettersAndGetters()
    {
        // Status
        $status = true;
        $this->response->setStatus($status);
        $this->assertSame($status, $this->response->getStatus());

        // Error
        $error = 'missing-value';
        $this->response->setError($error);
        $this->assertSame($error, $this->response->getError());
    }

    public function testFromResponseObj()
    {
        $httpResponse = new Response();
        $httpResponse->setStatusCode(200);
        $httpResponse->getHeaders()->addHeaderLine('Content-Type', 'text/html');

        $this->response->setFromResponseObj($httpResponse);

        $this->assertSame(false, $this->response->getStatus());
    }


    public function testConstructorWithHttpResponse()
    {
        $httpResponse = new Response();
        $httpResponse->setStatusCode(200);
        $httpResponse->getHeaders()->addHeaderLine('Content-Type', 'text/html');

        $response = new CaptchaResponse($httpResponse);

        $this->assertSame(false, $response->getStatus());
    }
}
