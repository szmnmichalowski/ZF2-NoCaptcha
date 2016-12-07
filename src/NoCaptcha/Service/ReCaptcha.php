<?php
namespace NoCaptcha\Service;

use Zend\Http\Client as HttpClient;
use NoCaptcha\Service\Response as CaptchaResponse;
use NoCaptcha\Exception\Exception;
use Zend\Stdlib\ArrayUtils;
use Traversable;

/**
 * Class ReCaptcha
 *
 * @link https://github.com/szmnmichalowski/ZF2-NoCaptcha
 * @package NoCaptcha\Service
 * @author  Szymon Michałowski <szmnmichalowski@gmail.com>
 */
class ReCaptcha
{
    /**
     * @const api url
     */
    const VERIFY_SERVER = "https://www.google.com/recaptcha/api/siteverify?";

    /**
     * @var string
     */
    protected $siteKey = '';

    /**
     * @var string
     */
    protected $secretKey = '';

    /**
     * @var null
     */
    protected $ip = null;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var HttpClient
     */
    protected $httpClient;


    /**
     * @param HttpClient $client
     * @param null       $options
     *
     * @throws \Exception
     */
    public function __construct(HttpClient $client = null, $options = null)
    {
        if ($client !== null) {
            $this->setHttpClient($client);
        } else {
            $this->setHttpClient(new HttpClient());
        }

        if ($options !== null) {
            $this->setOptions($options);
        }

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $this->setIp($_SERVER['REMOTE_ADDR']);
        }

        if ($this->options) {
            $this->httpClient->setOptions($this->options);
        }
    }

    /**
     * Verify the user input
     *
     * @param $response
     *
     * @return Response
     * @throws \Exception
     */
    public function verify($response)
    {
        if (!$this->secretKey) {
            throw new Exception('Missing secret key');
        }

        $params = array(
            'secret' => $this->secretKey,
            'response' => $response,
        );

        if ($this->ip !== null) {
            $params['remoteip'] = $this->ip;
        }

        $this->httpClient->setUri(self::VERIFY_SERVER);
        $this->httpClient->setParameterPost($params);

        if ($this->options) {
            $this->httpClient->setOptions($this->options);
        }

        $this->httpClient->setMethod('POST');
        $response = $this->httpClient->send();

        return new CaptchaResponse($response);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set configuration parameters for HTTP client
     *
     * @param array|Traversable $options
     *
     * @throws \Exception
     * @return $this
     */
    public function setOptions($options = array())
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (!is_array($options)) {
            throw new \Exception('Config parameter is not a valid');
        }

        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }

        return $this;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * @param $option
     *
     * @return mixed
     */
    public function getOption($option)
    {
        return $this->options[$option];
    }

    /**
     * @param HttpClient $httpClient
     *
     * @return $this
     */
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @return null
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param $ip
     *
     * @return $this
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @param $secretKey
     *
     * @return $this
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getSiteKey()
    {
        return $this->siteKey;
    }

    /**
     * @param $siteKey
     *
     * @return $this
     */
    public function setSiteKey($siteKey)
    {
        $this->siteKey = $siteKey;
        return $this;
    }

}
