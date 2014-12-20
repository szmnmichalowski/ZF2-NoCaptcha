<?php
namespace NoCaptcha\Captcha;

use Zend\Captcha\AbstractAdapter;
use NoCaptcha\Service\ReCaptcha as ReCaptchaService;


/**
 * Class ReCaptcha
 *
 * @link https://github.com/szmnmichalowski/ZF2-NoCaptcha
 * @package NoCaptcha\Captcha
 * @author  Szymon Michałowski <szmnmichalowski@gmail.com>
 */
class ReCaptcha extends AbstractAdapter
{

    /**
     * @var ReCaptchaService
     */
    protected $service;

    /**
     * light | dark
     *
     * @var string
     */
    protected $theme = 'light';

    /**
     * image | audio
     *
     * @var string
     */
    protected $type = 'image';

    /**
     * @var string
     */
    protected $callback = 'recaptchaCallback';

    /**
     * See the different options on https://developers.google.com/recaptcha/docs/display
     *
     * @var array
     */
    protected $options = array();

    /**
     * Error codes
     */
    const MISSING_VALUE = 'missingValue';
    const ERR_CAPTCHA   = 'errCaptcha';
    /**

    /**
     * Error messages
     * @var array
     */
    protected $messageTemplates = array(
        self::MISSING_VALUE => 'Missing captcha fields',
        self::ERR_CAPTCHA   => 'Failed to validate captcha',
    );


    /**
     * @param null $options
     */
    public function __construct($options = null)
    {
        $this->setService(new ReCaptchaService());

        parent::__construct($options);

        if (!empty($options)) {
            if (array_key_exists('site_key', $options)) {
                $this->setSiteKey($options['site_key']);
            }
            if (array_key_exists('secret_key', $options)) {
                $this->setSecretKey($options['secret_key']);
            }
            $this->setOptions($options);
        }
    }

    /**
     * @param $siteKey
     *
     * @return $this
     */
    public function setSiteKey($siteKey)
    {
        $this->getService()->setSiteKey($siteKey);
        return $this;
    }

    /**
     * @return string
     */
    public function getSiteKey()
    {
        return $this->getService()->getSiteKey();
    }

    /**
     * @param $secretKey
     *
     * @return $this
     */
    public function setSecretKey($secretKey)
    {
        $this->getService()->setSecretKey($secretKey);
        return $this;
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->getService()->getSecretKey();
    }

    /**
     * @return ReCaptchaService
     */
    public function getService()
    {
        return $this->service;
    }


    /**
     * @param ReCaptchaService $service
     *
     * @return ReCaptchaService
     */
    public function setService(ReCaptchaService $service)
    {
        return $this->service = $service;
    }

    /**
     * @return string
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param string $callback
     *
     * @return $this
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param string $theme
     *
     * @return $this
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


    /**
     * @return string
     */
    public function generate()
    {
        return "";
    }


    /**
     * Check if captcha is valid
     *
     * @param mixed $value
     *
     * @return bool
     * @throws \Exception
     */
    public function isValid($value)
    {
        if (!$value) {
            $this->error(self::MISSING_VALUE);
            return false;
        }

        $response = $this->getService()->verify($value);

        if ($response->getStatus() === true) {
            return true;
        }

        $this->error(self::ERR_CAPTCHA);
        return false;
    }

    /**
     * Get helper name
     *
     * @return string
     */
    public function getHelperName()
    {
        return 'recaptcha.helper';
    }

}