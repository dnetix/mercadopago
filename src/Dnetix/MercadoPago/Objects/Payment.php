<?php namespace Dnetix\MercadoPago\Objects;

/**
 * Class Payment
 *
 * @author Diego Calle
 * @package Dnetix\MercadoPago\Objects
 */
class Payment {

    protected $id;
    protected $sandbox;
    protected $payer;
    protected $collector;
    protected $order_id;
    protected $reason;
    protected $external_reference;
    protected $transaction_amount;
    protected $net_received_amount;
    protected $total_payed_amount;
    protected $currency_id;
    protected $shipping_cost;
    protected $status;
    protected $status_detail;
    protected $payment_type;
    protected $marketplace;
    protected $marketplace_fee;
    protected $mercadopago_fee;
    protected $merchant_order_id;
    protected $operation_type;
    protected $payment_method_id;
    protected $site_id;
    protected $date_created;
    protected $date_approved;
    protected $money_release_date;
    protected $last_modified;

    private $paymentArray;

    public function __construct($paymentArray) {
        $this->paymentArray = $paymentArray;
        foreach($paymentArray as $key => $value){
            $this->$key = $value;
        }
    }

    public function id() {
        return $this->id;
    }

    public function payer() {
        return new Person($this->payer);
    }

    public function collector() {
        return new Person($this->collector);
    }

    public function currency() {
        return $this->currency_id;
    }

    public function orderId() {
        return $this->order_id;
    }

    public function reason() {
        return $this->reason;
    }

    /**
     * Returns the external reference, the one given on the preference
     * @return string
     */
    public function externalReference() {
        return $this->external_reference;
    }

    public function transactionAmount() {
        return $this->transaction_amount;
    }

    public function netReceivedAmount() {
        return $this->net_received_amount;
    }

    public function totalPayedAmount() {
        return $this->total_payed_amount;
    }

    public function shippingCost() {
        return $this->shipping_cost;
    }

    public function status() {
        return $this->status;
    }

    public function marketplace() {
        return $this->marketplace;
    }

    public function marketplaceFee() {
        return $this->marketplace_fee;
    }

    public function mercadopagoFee() {
        return $this->mercadopago_fee;
    }

    public function merchantOrderId() {
        return $this->merchant_order_id;
    }

    public function siteId() {
        return $this->site_id;
    }

    public function sandbox() {
        return $this->sandbox;
    }

    public function isApproved(){
        return $this->status() == 'approved';
    }

    public function dateCreated() {
        return $this->date_created;
    }

    public function dateApproved() {
        return $this->date_approved;
    }

    public function moneyReleaseDate() {
        return $this->money_release_date;
    }

    public function lastModified() {
        return $this->last_modified;
    }

    public function toArray() {
        return $this->paymentArray;
    }
}