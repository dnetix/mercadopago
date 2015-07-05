<?php namespace Dnetix\MercadoPago\Objects;

/**
 * Class Preference
 *
 * @author Diego Calle
 * @package Dnetix\MercadoPago\Objects
 */
class Preference {

    protected $id;
    protected $operation_type;
    protected $client_id;
    protected $marketplace;
    protected $marketplace_fee;
    protected $items;
    protected $payer;
    protected $back_urls;
    protected $auto_return;
    protected $payment_methods;
    protected $shipments;
    protected $notification_url;
    protected $external_reference;
    protected $init_point;
    protected $additional_info;
    protected $collector_id;

    private $preferenceArray;

    public function __construct($preferenceArray){
        $this->preferenceArray = $preferenceArray;
        foreach($preferenceArray as $key => $value){
            $this->$key = $value;
        }
    }

    /**
     * Returns the identifier for the preference
     * @return string
     */
    public function id() {
        return $this->id;
    }

    public function operationType(){
        return $this->operation_type;
    }

    public function clientId() {
        return $this->client_id;
    }

    public function marketplace() {
        return $this->marketplace;
    }

    public function marketplaceFee() {
        return $this->marketplace_fee;
    }

    /**
     * Return the URI for the preference
     * @return string
     */
    public function initPoint() {
        return $this->init_point;
    }

    public function externalReference() {
        return $this->external_reference;
    }

    public function collectorId() {
        return $this->collector_id;
    }

    public function items() {
        return $this->items;
    }

    public function additionalInfo() {
        return $this->additional_info;
    }

    public function payer() {
        return new Person($this->payer);
    }

    /**
     * Returns all the information as it was inserted
     * @return array
     */
    public function toArray() {
        return $this->preferenceArray;
    }

}