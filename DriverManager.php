<?php

namespace SmsGatewayBundle;

use GuzzleHttp\Client;
use Illuminate\Support\Manager;
use SmsGatewayBundle\Drivers\LogSMS;
use SmsGatewayBundle\Drivers\EmailSMS;
use SmsGatewayBundle\Drivers\MozeoSMS;
use SmsGatewayBundle\Drivers\NexmoSMS;
use SmsGatewayBundle\Drivers\PlivoSMS;
use SmsGatewayBundle\Drivers\TwilioSMS;
use SmsGatewayBundle\Drivers\ZenviaSMS;
use SmsGatewayBundle\Drivers\CallFireSMS;
use SmsGatewayBundle\Drivers\EZTextingSMS;
use SmsGatewayBundle\Drivers\FlowrouteSMS;
use SmsGatewayBundle\Drivers\LabsMobileSMS;
use SmsGatewayBundle\Drivers\JawalySMS;

class DriverManager extends Manager
{
    /**
     * Get the default sms driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['sms.driver'];
    }

    /**
     * Set the default sms driver name.
     *
     * @param string $name
     */
    public function setDefaultDriver($name)
    {
        $this->app['config']['sms.driver'] = $name;
    }

    /**
     * Create an instance of the Log driver.
     *
     * @return LogSMS
     */
    protected function createLogDriver()
    {
        $provider = new LogSMS($this->app['log']);

        return $provider;
    }

    /**
     * Create an instance of the CallFire driver.
     *
     * @return CallFireSMS
     */
    protected function createCallfireDriver()
    {
        $config = $this->app['config']->get('sms.callfire', []);

        $provider = new CallFireSMS(
            new Client(),
            $config['app_login'],
            $config['app_password']
        );

        return $provider;
    }

    /**
     * Create an instance of the Jawally driver.
     *
     * @return JawalySMS
     */
    protected function createJawalyDriver()
    {
        $config = $this->app['config']->get('sms.jawaly', []);

        $provider = new JawalySMS(
            $config['username'],
            $config['api_secret'],
            $config['from']
        );

        return $provider;
    }

    /**
     * Creates an instance of the EMail driver.
     *
     * @return EmailSMS
     */
    protected function createEmailDriver()
    {
        $provider = new EmailSMS($this->app['mailer']);

        return $provider;
    }

    /**
     * Create an instance of the EZTexting driver.
     *
     * @return EZTextingSMS
     */
    protected function createEztextingDriver()
    {
        $config = $this->app['config']->get('sms.eztexting', []);

        $provider = new EZTextingSMS(new Client());

        $data = [
            'User' => $config['username'],
            'Password' => $config['password'],
        ];

        $provider->buildBody($data);

        return $provider;
    }

    /**
     * Create an instance of the LabsMobile driver.
     *
     * @return LabsMobileSMS
     */
    protected function createLabsMobileDriver()
    {
        $config = $this->app['config']->get('sms.labsmobile', []);

        $provider = new LabsMobileSMS(new Client());

        $auth = [
            'username' => $config['username'],
            'password' => $config['password'],
        ];

        $provider->buildBody($auth);

        return $provider;
    }

    /**
     * Create an instance of the Mozeo driver.
     *
     * @return MozeoSMS
     */
    protected function createMozeoDriver()
    {
        $config = $this->app['config']->get('sms.mozeo', []);

        $provider = new MozeoSMS(new Client());

        $auth = [
            'companykey' => $config['company_key'],
            'username' => $config['username'],
            'password' => $config['password'],
        ];
        $provider->buildBody($auth);

        return $provider;
    }

    /**
     * Create an instance of the nexmo driver.
     *
     * @return NexmoSMS
     */
    protected function createNexmoDriver()
    {
        $config = $this->app['config']->get('sms.nexmo', []);

        $provider = new NexmoSMS(
            new Client(),
            $config['api_key'],
            $config['api_secret']
        );

        return $provider;
    }

    /**
     * Create an instance of the Twillo driver.
     *
     * @return TwilioSMS
     */
    protected function createTwilioDriver()
    {
        $config = $this->app['config']->get('sms.twilio', []);

        return new TwilioSMS(
            new \Services_Twilio($config['account_sid'], $config['auth_token']),
            $config['auth_token'],
            $this->app['request']->url(),
            $config['verify']
        );
    }

     /**
      * Create an instance of the Zenvia driver.
      *
      * @return ZenviaSMS
      */
     protected function createZenviaDriver()
     {
         $config = $this->app['config']->get('sms.zenvia', []);

         $provider = new ZenviaSMS(
             new Client(),
             $config['account_key'],
             $config['passcode'],
             $config['callbackOption']
         );

         return $provider;
     }

    /**
     * Create an instance of the Plivo driver.
     *
     * @return PlivoSMS
     */
    protected function createPlivoDriver()
    {
        $config = $this->app['config']->get('sms.plivo', []);

        $provider = new PlivoSMS(
            $config['auth_id'],
            $config['auth_token']
        );

        return $provider;
    }

    /**
     * Create an instance of the flowroute driver.
     *
     * @return FlowrouteSMS
     */
    protected function createFlowrouteDriver()
    {
        $config = $this->app['config']->get('sms.flowroute', []);

        $provider = new FlowrouteSMS(
            new Client(),
            $config['access_key'],
            $config['secret_key']
        );

        return $provider;
    }
}
