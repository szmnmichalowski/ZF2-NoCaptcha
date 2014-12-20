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
        $params = array(
            'success' => true,
            'error' => 'error'
        );

        $httpResponse = new Response();
        $httpResponse->setStatusCode(200);
        $httpResponse->getHeaders()->addHeaderLine('Content-Type', 'text/html');
        $httpResponse->setContent(json_encode($params));

        $this->response->setFromResponseObj($httpResponse);

        $this->assertSame(true, $this->response->getStatus());
        $this->assertSame($params['error'], $this->response->getError());
    }


    public function testConstructorWithHttpResponse()
    {
        $params = array(
            'success' => true,
            'error' => 'error'
        );

        $httpResponse = new Response();
        $httpResponse->setStatusCode(200);
        $httpResponse->getHeaders()->addHeaderLine('Content-Type', 'text/html');
        $httpResponse->setContent(json_encode($params));
        $response = new CaptchaResponse($httpResponse);

        $this->assertSame(true, $response->getStatus());
        $this->assertSame($params['error'], $response->getError());
    }


    public function testConstructorWithMissingStatus()
    {
        $params = array(
            'error' => 'error'
        );

        $httpResponse = new Response();
        $httpResponse->setStatusCode(200);
        $httpResponse->getHeaders()->addHeaderLine('Content-Type', 'text/html');
        $httpResponse->setContent(json_encode($params));
        $response = new CaptchaResponse($httpResponse);

        $this->assertSame(false, $response->getStatus());
    }
}