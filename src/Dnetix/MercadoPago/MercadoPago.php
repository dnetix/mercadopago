<?php namespace Dnetix\MercadoPago;

use Dnetix\MercadoPago\Objects\MerchantOrder;
use Dnetix\MercadoPago\Objects\Payment;
use Dnetix\MercadoPago\Objects\Preference;
use Exception;

/**
 * Class MercadoPago
 *
 * @author Diego Calle
 * @package Dnetix\MercadoPago
 */
class MercadoPago {

    protected $appId;
    protected $secretKey;
    protected $authorization_url;

    protected $notification_url;
    protected $authorizationCode;

    protected $currency = 'COP';
    protected $fee;

    protected $accessData;
    protected $accessToken;

    protected $preferenceData = [];
    protected $back_urls;
    protected $items = [];

    protected $sandBox = false;

    const AUTHORIZATION_URL = 'https://auth.mercadopago.com.co/authorization';
    const API_URL = 'https://api.mercadopago.com';

    public function __construct($config = []) {
        $requiredProperties = [
            'appId',
            'secretKey',
            'authorization_url'
        ];
        foreach($config as $key => $value){
            $this->$key = $value;
        }
        foreach($requiredProperties as $requiredProperty){
            if(is_null($this->$requiredProperty)){
                throw new Exception("The value for the required property {$requiredProperty} has not been provided on the construction");
            }
        }
    }

    public static function load($config = []) {
        return new self($config);
    }

    /**
     * Returns the URL to point to the user in order to obtain the authorization for the application from MercadoPago
     * @param null $additionalData
     * @return string
     */
    public function authorizationPoint($additionalData = null) {
        $redirectUserUri = CurlClient::buildQuery($this->authorizationUrl(), $additionalData);
        $data = [
            'client_id' => $this->appId(),
            'response_type' => 'code',
            'platform_id' => 'mp',
            'redirect_uri' => $redirectUserUri
        ];
        return CurlClient::buildQuery(self::AUTHORIZATION_URL, $data);
    }

    /**
     * Sets the authorization code in order to obtain an access token
     * @param $data
     * @return $this
     */
    public function setAuthorizationCode($data){
        if(is_array($data)){
            $this->authorizationCode = $data['code'];
        }else{
            $this->authorizationCode = $data;
        }
        return $this;
    }

    /**
     * Obtains an access token from an authorization code
     * @param array $additionalData
     * @return mixed
     * @throws Exception
     */
    public function obtainAccessToken($additionalData = []) {
        if(is_null($this->authorizationCode())){
            throw new Exception("An authorization code has not been provided");
        }
        if(isset($additionalData['code'])){
            unset($additionalData['code']);
        }
        $response = CurlClient::post(self::API_URL.'/oauth/token', [
            'client_id' => $this->appId(),
            'client_secret' => $this->secretKey(),
            'grant_type' => 'authorization_code',
            'code' => $this->authorizationCode(),
            'redirect_uri' => CurlClient::buildQuery($this->authorizationUrl(), $additionalData)
        ]);
        $this->accessData = $response['response'];
        $this->accessToken = $response['response']['access_token'];

        return $this->accessToken();
    }

    /**
     * In case an access token has not been stablished already obtains one from MercadoPago and returns it.
     * @return string
     */
    public function accessToken() {
        if(is_null($this->accessToken)){
            $response = CurlClient::post(self::API_URL.'/oauth/token', [
                'client_id' => $this->appId(),
                'client_secret' => $this->secretKey(),
                'grant_type' => 'client_credentials'
            ]);
            $this->accessData = $response['response'];
            $this->accessToken = $response['response']['access_token'];
        }
        return $this->accessToken;
    }

    /**
     * Sets the MarketPlace fee to charge in the preference
     * @param $fee
     * @return $this
     */
    public function setFee($fee) {
        $this->fee = $fee;
        return $this;
    }

    /**
     * Adds an item to the preference
     * Required ['title', 'description', 'quantity', 'unit_price']
     * Optional ['currency_id', 'picture_url']
     * @param $data
     * @return $this
     * @throws Exception
     */
    public function addItem($data) {
        $requiredProperties = [
            'title',
            'description',
            'quantity',
            'unit_price'
        ];
        foreach($requiredProperties as $requiredProperty){
            if(!isset($data[$requiredProperty]) || is_null($data[$requiredProperty])){
                throw new Exception("The value for the required property {$requiredProperty} has not been provided for the item");
            }
        }
        if(!isset($data['currency_id'])){
            $data['currency_id'] = $this->currency();
        }
        $this->items[] = $data;
        return $this;
    }

    /**
     * Adds an external reference to the preference
     * @param $reference
     * @return $this
     */
    public function addExternalReference($reference) {
        $this->preferenceData['external_reference'] = $reference;
        return $this;
    }

    /**
     * Allows to add extra information to the preference
     * @param $data
     * @return $this
     */
    public function addExtraParameters($data) {
        foreach($data as $key => $value){
            $this->preferenceData[$key] = $value;
        }
        return $this;
    }

    public function createPreference($accessToken = null) {
        if(sizeof($this->items()) == 0){
            throw new Exception("There is no items for the preference");
        }
        if(is_null($accessToken)){
            $accessToken = $this->accessToken();
        }
        $this->preferenceData['items'] = $this->items();
        if(!is_null($this->fee())){
            $this->preferenceData['marketplace_fee'] = $this->fee();
        }
        if(!is_null($this->backUrls())){
            $this->preferenceData['back_urls'] = $this->backUrls();
        }
        if(!is_null($this->notificationUrl())){
            $this->preferenceData['notification_url'] = $this->notificationUrl();
        }
        $response = CurlClient::post(CurlClient::buildQuery(self::API_URL.'/checkout/preferences', [
            'access_token' => $accessToken
        ]), $this->preferenceData);

        return new Preference($response['response']);
    }

    /**
     * Return the preference that mathches the given id or null if it not exists.
     * @param $id
     * @return Preference|null
     */
    public function getPreference($id){
        $uri = '/checkout/preferences/'.$id;
        try {
            $response = CurlClient::get(CurlClient::buildQuery(self::API_URL . $uri, [
                'access_token' => $this->accessToken()
            ]));
            return new Preference($response['response']);
        }catch (Exception $e){
            return null;
        }
    }

    /**
     * Returns the payments that matches the given id or null if not exists
     * @param $id
     * @return Payment
     */
    public function getPayment($id){
        $uri = '/collections/notifications/'.$id;
        if($this->sandBox()){
            $uri = '/sandbox'.$uri;
        }
        try {
            $response = CurlClient::get(CurlClient::buildQuery(self::API_URL . $uri, [
                'access_token' => $this->accessToken()
            ]));
            return new Payment($response['response']['collection']);
        }catch (Exception $e){
            return null;
        }
    }

    /**
     * Returns the array of Payment matching the given filters
     * @param array $filters
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function searchPayments($filters = [], $offset = 0, $limit = 0){
        $uri = '/collections/search';
        if($this->sandBox()){
            $uri = '/sandbox'.$uri;
        }
        $filters['access_token'] = $this->accessToken();

        $filters["offset"] = $offset;
        $filters["limit"] = $limit;

        $return = CurlClient::get(CurlClient::buildQuery(self::API_URL.$uri, $filters));
        $results = [];
        foreach($return['response']['results'] as $result){
            $results[] = new Payment($result['collection']);
        }
        return $results;
    }

    /**
     * @param $id
     * @return MerchantOrder
     */
    public function getMerchantOrder($id) {
        $uri = '/merchant_orders/'.$id;
        $return = CurlClient::get(CurlClient::buildQuery(self::API_URL.$uri, [
            'access_token' => $this->accessToken()
        ]));
        return new MerchantOrder($return['response']);
    }

    /**
     * @param $data
     * @return MerchantOrder
     */
    public function getMerchantOrderIPN($data) {
        $merchantOrder = null;
        if($data['topic'] == 'payment'){
            $payment = $this->getPayment($data['id']);
            $merchantOrder = $this->getMerchantOrder($payment->merchantOrderId());
        }else if($data['topic'] == 'merchant_order'){
            $merchantOrder = $this->getMerchantOrder($data['id']);
        }
        return $merchantOrder;
    }

    /** Getters */

    public function items() {
        return $this->items;
    }

    public function sandBox(){
        return $this->sandBox;
    }

    public function appId() {
        return $this->appId;
    }

    public function secretKey() {
        return $this->secretKey;
    }

    public function authorizationUrl() {
        return $this->authorization_url;
    }

    public function notificationUrl() {
        return $this->notification_url;
    }

    public function authorizationCode() {
        return $this->authorizationCode;
    }

    public function currency() {
        return $this->currency;
    }

    public function fee() {
        return $this->fee;
    }

    public function accessData() {
        return $this->accessData;
    }

    public function backUrls() {
        return $this->back_urls;
    }

}
