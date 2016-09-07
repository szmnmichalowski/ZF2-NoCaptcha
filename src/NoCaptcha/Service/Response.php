<?php
namespace NoCaptcha\Service;

use Zend\Http\Response as HttpResponse;

/**
 * Class Response
 *
 * @link https://github.com/szmnmichalowski/ZF2-NoCaptcha
 * @package NoCaptcha\Service
 * @author  Szymon Michałowski <szmnmichalowski@gmail.com>
 */
class Response
{
    /**
     * @var bool
     */
    protected $status = false;

    /**
     * @var string
     */
    protected $error = '';

    /**
     * @param HttpResponse $response
     */
    public function __construct(HttpResponse $response = null)
    {
        if ($response) {
            $this->setFromResponseObj($response);
        }
    }


    /**
     * @param HttpResponse $response
     *
     * @return $this
     */
    public function setFromResponseObj(HttpResponse $response)
    {
        $content = json_decode($response->getBody(), true);


        if (array_key_exists('success', $content)) {
            $this->setStatus($content['success']);
        }

        if (array_key_exists('error', $content)) {
            $this->setError($content['error']);
        }

        return $this;
    }

    /**
     * @param $status
     *
     * @return mixed
     */
    public function setStatus($status)
    {
        return $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $error
     *
     * @return mixed
     */
    public function setError($error)
    {
        return $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }
}
