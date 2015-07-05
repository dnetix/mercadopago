<?php namespace Dnetix\MercadoPago;

use Exception;

/**
 * Class MercadoPagoSandBox
 * Testing version for the MercadoPago SDK
 *
 * @author Diego Calle
 * @package Dnetix\MercadoPago
 */
class MercadoPagoSandBox extends MercadoPago {

    public function __construct($config = []){
        $this->sandBox = true;
        parent::__construct($config);
    }

    /**
     * Return an array with the information for the new test user
     * @param string $siteId
     * @return array
     */
    public function getTestUser($siteId = 'MCO') {
        // Obtain access token
        $result = CurlClient::post(self::API_URL.'/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->appId(),
            'client_secret' => $this->secretKey()
        ]);

        $accessToken = $result['response']['access_token'];

        // Obtain data
        $result = CurlClient::post(CurlClient::buildQuery(self::API_URL.'/users/test_user', [
            'access_token' => $accessToken
        ]), [
            'site_id' => $siteId
        ]);

        return $result['response'];
    }

    public function createPreApprovalPayment($accessToken = null){
        if(sizeof($this->items()) == 0){
            throw new Exception("No se han asignado items para la preferencia");
        }
        if(is_null($accessToken)){
            if(!$this->accessToken()){
                throw new Exception("No se ha asignado un Access Token");
            }
            $accessToken = $this->accessToken();
        }
        $return = CurlClient::post(CurlClient::buildQuery(self::API_URL.'/preapproval', [
            'access_token' => $accessToken
        ]), [
            'items' => $this->items(),
            'marketplace_fee' => $this->fee()
        ]);
        return $return['response'];
    }

    public static function load($config = []) {
        return new self($config);
    }

}
