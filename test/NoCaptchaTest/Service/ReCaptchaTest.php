<?php
namespace NoCaptchaTest\Service;

use Zend\Http\Client as HttpClient;
use NoCaptcha\Service\ReCaptcha;

class ReCaptchaTest extends \PHPUnit_Framework_TestCase
{
    protected $siteKey = 'TEST_SITE_KEY';
    protected $secretKey = 'TEST_SECRET_KEY';

    /**
     * @var ReCaptcha
     */
    protected $service;


    public function setUp()
    {
        $this->service = new ReCaptcha();
        $this->service->setOption('sslverifypeer', false);
        $this->service->setOption('sslverifyhost', false);
    }


    public function testSettersAndGetters()
    {
        // Site key
        $this->service->setSiteKey($this->siteKey);
        $this->assertSame($this->siteKey, $this->service->getSiteKey());

        // Secret key
        $this->service->setSecretKey($this->secretKey);
        $this->assertSame($this->secretKey, $this->service->getSecretKey());

        // Ip
        $ip = '127.0.0.1';
        $this->service->setIp($ip);
        $this->assertSame($ip, $this->service->getIp());

        // Http client
        $this->service->setHttpClient(new HttpClient());
        $this->assertInstanceOf('Zend\Http\Client', $this->service->getHttpClient());
    }


    public function testVerifyWithMissingSecretKey()
    {
        $this->setExpectedException('NoCaptcha\Exception\Exception');
        $this->service->verify(null);
    }

    public function testVerifyReturnObject()
    {
        $var = 'string';
        $this->service->setSecretKey($this->secretKey);

        $this->assertInstanceOf('NoCaptcha\Service\Response', $this->service->verify($var));
    }

    public function testDoNotVerifyWithIncorrectParam()
    {
        $var = 'string';
        $this->service->setSecretKey($this->secretKey);
        $response = $this->service->verify($var);

        $this->assertFalse($response->getStatus());
    }


    public function testSingleOption()
    {
        $options = array(
            'sslverifypeer' => 'test',
        );
        $this->service->setOption('sslverifypeer', $options['sslverifypeer']);

        $this->assertSame($options['sslverifypeer'], $this->service->getOption('sslverifypeer'));
    }

    public function testMultpleOptions()
    {
        $options = array(
            'sslverifypeer' => 'test',
            'sslverifyhost' => false,
            'foo' => 'bar'
        );
        $this->service->setOptions($options);

        $this->assertSame($options, $this->service->getOptions());
        $this->assertSame($options['sslverifypeer'], $this->service->getOption('sslverifypeer'));
        $this->assertSame($options['foo'], $this->service->getOption('foo'));
    }

    public function testConstructor()
    {
        $options = array(
            'sslverifypeer' => 'test',
            'foo' => 'bar'
        );
        $client = new HttpClient();

        $service_1 = new ReCaptcha();
        $service_2 = new ReCaptcha($client);
        $service_3 = new ReCaptcha(null, $options);

        $this->assertInstanceOf('Zend\Http\Client', $service_1->getHttpClient());
        $this->assertEmpty($service_1->getOptions());

        $this->assertInstanceOf('Zend\Http\Client', $service_2->getHttpClient());
        $this->assertEmpty($service_2->getOptions());

        $this->assertInstanceOf('Zend\Http\Client', $service_3->getHttpClient());
        $this->assertSame($options, $service_3->getOptions());

    }

}
