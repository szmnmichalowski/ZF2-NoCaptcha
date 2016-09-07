<?php
namespace NoCaptchaTest\Captcha;

use NoCaptcha\Captcha\ReCaptcha;
use Zend\Config;

class NoCaptchaTest extends \PHPUnit_Framework_TestCase
{

    protected $siteKey = 'TEST_SITE_KEY';
    protected $secretKey = 'TEST_SECRET_KEY';

    protected $theme = 'light';

    protected $type = 'image';

    protected $size = 'normal';

    protected $callback = 'recaptchaCallback';

    /**
     * @var ReCaptcha
     */
    protected $captcha = null;


    public function setUp()
    {
        $this->captcha = new ReCaptcha();
        $this->captcha->getService()->setOption('sslverifypeer', false);
    }


    public function testSettersAndGetters()
    {
        // Site key
        $this->captcha->setSiteKey($this->siteKey);
        $this->assertSame($this->siteKey, $this->captcha->getSiteKey());

        // Secret key
        $this->captcha->setSecretKey($this->secretKey);
        $this->assertSame($this->secretKey, $this->captcha->getSecretKey());

        // Theme
        $this->captcha->setTheme($this->theme);
        $this->assertSame($this->theme, $this->captcha->getTheme());

        // Type
        $this->captcha->setType($this->type);
        $this->assertSame($this->type, $this->captcha->getType());

        // Size
        $this->captcha->setSize($this->size);
        $this->assertSame($this->size, $this->captcha->getSize());

        // Callback
        $this->captcha->setCallback($this->callback);
        $this->assertSame($this->callback, $this->captcha->getCallback());
    }


    public function testServiceInstance()
    {
        $this->assertInstanceOf('NoCaptcha\Service\ReCaptcha', $this->captcha->getService());
    }


    public function testMultipleOptions()
    {
        $options = array(
            'theme' => 'dark',
            'type' => 'image',
            'size' => 'compact',
            'callback' => 'recaptchaCallback'
        );

        $this->captcha->setOptions($options);
        $_options = $this->captcha->getOptions();

        $this->assertSame($options['theme'], $_options['theme']);
        $this->assertSame($options['type'], $_options['type']);
        $this->assertSame($options['size'], $_options['size']);
        $this->assertSame($options['callback'], $_options['callback']);
    }


    public function testMultipleOptionsFromConfig()
    {
        $options = array(
            'theme' => 'dark',
            'type' => 'image',
            'size' => 'compact',
            'callback' => 'recaptchaCallback'
        );

        $config = new Config\Config($options);
        $this->captcha->setOptions($config);

        $_options = $this->captcha->getOptions();

        $this->assertSame($options['theme'], $_options['theme']);
        $this->assertSame($options['type'], $_options['type']);
        $this->assertSame($options['size'], $_options['size']);
        $this->assertSame($options['callback'], $_options['callback']);

        $_captcha = new ReCaptcha($config);

        $this->assertSame($_captcha->getOptions(), $_options);
    }


    public function testSingleOption()
    {
        $options = array(
            'theme' => 'dark',
        );
        $this->captcha->setOption('theme', $options['theme']);

        $this->assertSame($options['theme'], $this->captcha->getOption('theme'));
        $this->assertSame($options['theme'], $this->captcha->getTheme());
    }

    public function testRecaptchaServiceOptions()
    {
        $options = array(
            'service_options' => array(
                'adapter' => 'Zend\Http\Client\Adapter\Curl',
            ),
        );

        $_captcha = new ReCaptcha($options);

        $adapter = $_captcha->getService()->getHttpClient()->getAdapter();
        $this->assertInstanceOf('Zend\Http\Client\Adapter\Curl', $adapter);
    }


    public function testSetInvalidOptions()
    {
        $this->setExpectedException('\Exception');
        $var = 'string';
        $this->captcha->setOptions($var);
    }


    public function testIsValidWithNull()
    {
        $var = null;
        $result = $this->captcha->isValid($var);

        $this->assertFalse($result);
    }


    public function testIsValidWithIncorrectString()
    {
        $var = 'string';
        $this->captcha->setSecretKey($this->secretKey);

        $this->assertTrue($this->captcha->isValid($var));
    }


    public function testConstructor()
    {
        $options = array(
            'site_key' => $this->siteKey,
            'secret_key' => $this->secretKey,
            'theme' => 'foo',
        );

        $_captcha = new ReCaptcha($options);

        $this->assertSame($options['site_key'], $_captcha->getSiteKey());
        $this->assertSame($options['secret_key'], $_captcha->getSecretKey());
        $this->assertSame($options['theme'], $_captcha->getTheme());
    }

}
