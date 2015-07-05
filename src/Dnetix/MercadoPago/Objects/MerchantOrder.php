<?php namespace Dnetix\MercadoPago\Objects;

/**
 * Class MerchantOrder
 *
 * @author Diego Calle
 * @package Dnetix\MercadoPago\Objects
 */
class MerchantOrder {

    protected $id;
    protected $preference_id;
    protected $date_created;
    protected $last_updated;
    protected $application_id;
    protected $status;
    protected $site_id;
    protected $payer;
    protected $collector;
    protected $sponsor_id;
    protected $payments = [];
    protected $items;
    protected $marketplace;
    protected $shipments;
    protected $external_reference;
    protected $additional_info;
    protected $notfication_url;
    protected $total_amount;

    public function __construct($data) {
        foreach($data as $key => $value) {
            $this->$key = $value;
        }
        $payments = [];
        foreach($data['payments'] as $paymentData){
            $payments[] = new Payment($paymentData);
        }
        $this->payments = $payments;
    }

    /**
     * Returns the identifier for this MerchantOrder
     * @return string
     */
    public function id() {
        return $this->id;
    }

    /**
     * Returns the identifier for the preference associated with this MerchantOrder
     * @return string
     */
    public function preferenceId() {
        return $this->preference_id;
    }

    public function dateCreated() {
        return $this->date_created;
    }

    public function applicationId() {
        return $this->application_id;
    }

    public function status() {
        return $this->status;
    }

    public function lastUpdated() {
        return $this->last_updated;
    }

    /**
     * Returns the external reference code given
     * @return string
     */
    public function externalReference() {
        return $this->external_reference;
    }

    /**
     * Returns the value stablished (charged) for the merchant order, DO NOT represent how much it's been approved
     * @return int
     */
    public function totalAmount() {
        return $this->total_amount;
    }

    /**
     * Return the total amount approved for this merchant order
     * @return int
     */
    public function totalApproved(){
        $total = 0;
        foreach($this->payments as $payment){
            if($payment->isApproved()){
                $total += $payment->transactionAmount();
            }
        }
        return $total;
    }

    /**
     * Returns true if the total approved amount it's equal or higher to the charged amount in this MerchantOrder
     * false otherwise
     * @return bool
     */
    public function isPayed() {
        return ($this->totalApproved() >= $this->totalAmount());
    }

    /**
     * Returns an array of Payments associated with this MerchantOrder
     * @return array
     */
    public function payments() {
        return $this->payments;
    }

    public function payer() {
        return new Person($this->payer);
    }

}