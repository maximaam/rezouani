<?php
/**
 * Created by PhpStorm.
 * User: mimosa
 * Date: 29.05.18
 * Time: 16:58
 */

namespace App\Service;


use Unirest;


/**
 * Class Paypal
 * @package App\Service
 */
class Paypal
{
    private static $config = [
        'version'   => 98,
        'currency_code'   => 'EUR',
        'sandbox'   => [
            'username'  => 'mimo.berlino-facilitator_api1.gmail.com',
            'password'  => 'WQTYYURKZJ2VZZ8P',
            'signature'  => 'AAIpGRmmcgCTmV.LsCEFZFtHFZvLACCzl1.GLXJTratF5TNZIMHLPsk1',
            'endpoint_url'  => 'https://api-3t.sandbox.paypal.com/nvp',
            'payment_url'  => 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=',
        ]
    ];

    const ENV_SANDBOX = 'sandbox';
    const ENV_PROD = 'prod';


    public function getExpressCheckoutUrl(array $products, array $options, $env = self::ENV_PROD)
    {
        $params = [
            'METHOD'    => 'SetExpressCheckout',
            'VERSION'   => self::$config['version'],
            'USER'      => self::$config[$env]['username'],
            'PWD'       => self::$config[$env]['password'],
            'SIGNATURE' => self::$config[$env]['signature'],
            'RETURNURL' => $options['return_url'],
            'CANCELURL' => $options['cancel_url'],
            'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
            'PAYMENTREQUEST_0_CURRENCYCODE' => self::$config['currency_code'],
            'PAYMENTREQUEST_0_AMT' => $products['totals']['total']
        ];

        $params = http_build_query($params);
        $url = self::$config[$env]['endpoint_url'] . '?' . $params;


        Unirest\Request::verifyHost(false);
        $response = Unirest\Request::get($url);

        $response = $response->body;
        $response_params = [];
        parse_str($response, $response_params);


        if ($response_params['ACK'] == 'Success') {
            return self::$config[$env]['payment_url'] . $response_params['TOKEN'];
        }

        return false;
    }

    public function doExpressCheckoutPayment(array $products, array $options, $env = self::ENV_PROD)
    {
        $params = [
            'METHOD'        => 'DoExpressCheckoutPayment',
            'PAYMENTACTION' => 'Sale',

            'VERSION'       => self::$config['version'],
            'USER'          => self::$config[$env]['username'],
            'PWD'           => self::$config[$env]['password'],
            'SIGNATURE'     => self::$config[$env]['signature'],

            'PAYMENTREQUEST_0_CURRENCYCODE' => self::$config['currency_code'],
            'PAYMENTREQUEST_0_AMT' => $products['totals']['total'],

            'TOKEN'     => $options['token'],
            'PAYERID'   => $options['payer_id'],
        ];

        $params = http_build_query($params);
        $url = self::$config[$env]['endpoint_url'] . '?' . $params;

        $response = Unirest\Request::get($url);

        $response = $response->body;
        $response_params = [];
        parse_str($response, $response_params);

        dump($response); die;
    }

}