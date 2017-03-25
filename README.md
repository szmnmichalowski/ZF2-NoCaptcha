## NoCaptcha

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://travis-ci.org/szmnmichalowski/ZF2-NoCaptcha.svg?branch=master)](https://travis-ci.org/szmnmichalowski/ZF2-NoCaptcha)

NoCaptcha is a [Zend Framework 2](http://framework.zend.com/) module which is integrated with new version of Google reCAPTCHA.<br/>
More info about ["No CAPTCHA reCAPTCHA"](http://googleonlinesecurity.blogspot.com/2014/12/are-you-robot-introducing-no-captcha.html)

### Installation

You can install this module by cloning this project into your **./vendor/** directory, or using composer, which is more recommended:<br/>
**1.**
Add this project into your composer.json
```
"require": {
    "szmnmichalowski/zf2-nocaptcha": "dev-master"
}
```
**2.**
Update your dependencies
```
$ php composer.phar update
```

**3.**
Add module to your **application.config.php**
```
return array(
    'modules' => array(
        'Application',
        'NoCaptcha' // <- Add this line
    )
);
```

### Usage

**1.**
Add to **layout.phtml** in head section:

```
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
```

**2.**
Register your site at [https://www.google.com/recaptcha/admin#createsite](https://www.google.com/recaptcha/admin#createsite).<br/>
After you register your site you will get:
- Site key
- Secret key

Without this two keys, module won't work correctly 

**3.**
Pass these keys to **\NoCaptcha\Captcha\ReCaptcha** class

**Example #1: Basic**

```
        $options = array(
            'site_key' => 'YOUR_SITE_KEY',
            'secret_key' => 'YOUR_SECRET_KEY',
        );
        
        $captcha = new \NoCaptcha\Captcha\ReCaptcha($options);
```
In your form class:
```
        ...
        $this->add(array(
            'type'  => 'Zend\Form\Element\Captcha',
            'name' => 'captcha',
            'attributes' => array(
                'id' => 'recaptcha-response',
            ),
            'options' => array(
                'label' => 'Are you a bot?',
                'captcha' => $captcha // <-- Object of NoCaptcha\Captcha\ReCaptcha
            )
        ));
        ...
```
Last step is to render captcha input in your view:
```
        <div class="form-group">
            <?php echo $this->formlabel($form->get('captcha')); ?>
            <div>
                <?php echo $this->formCaptcha($form->get('captcha')); ?>
                <div class="error-message">
                    <?php echo $this->formElementErrors($form->get('captcha')); ?>
                </div>
            </div>
        </div>
```

**Example #2: Advanced** 

```
        $options = array(
            'site_key' => 'YOUR_SITE_KEY',
            'secret_key' => 'YOUR_SECRET_KEY',
            'theme' => 'dark',
            'type' => 'image',
            'size' => 'normal',
            'messages' => array(
                'errCaptcha' => 'Custom message when google API return false'
            ),
            'service_options' => array(
                'adapter' => 'Zend\Http\Client\Adapter\Curl', // override default HttpClient adapter options
            )
        );

        $captcha = new \NoCaptcha\Captcha\Recaptcha($options);
```
Or you can use setters
```
        $captcha->setSiteKey($siteKey);
        $captcha->setSecretKey($secretKey);
        $captcha->setTheme('dark');
        $captcha->setType('image');
        $captcha->setSize('normal');
```

NoCaptcha uses **Zend\Http\Client** to verify if captcha is valid. If for some reasons you want to pass additional settings to Client class, you can do this by:

```
$service = $captcha->getService();
$service->setOptions(array(
  'sslverifypeer' => false
));
```

### reCAPTCHA options

Theme:<br/>
- light (default)
- dark

Type: <br/>
- image (default)
- audio

Size: <br/>
- normal (default)
- compact

More information about options you can find at [https://developers.google.com/recaptcha/docs/display](https://developers.google.com/recaptcha/docs/display)
