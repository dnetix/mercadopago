<?php namespace Dnetix\MercadoPago\Objects;

/**
 * Class Person
 *
 * @author Diego Calle
 * @package Dnetix\MercadoPago\Objects
 */
class Person {

    protected $id;
    protected $first_name;
    protected $last_name;
    protected $phone;
    protected $email;
    protected $nickname;
    protected $identification;

    public function __construct($data) {
        foreach($data as $key => $value){
            $this->$key = $value;
        }
    }

    /**
     * Returns MercadoPago identification number for the user
     * @return int
     */
    public function id() {
        return $this->id;
    }

    public function firstName() {
        return $this->first_name;
    }

    public function lastName() {
        return $this->last_name;
    }

    public function name() {
        return $this->first_name.' '.$this->last_name;
    }

    public function phoneNumber() {
        if(is_array($this->phone) && isset($this->phone['area_code']) && isset($this->phone['number']) && isset($this->phone['extension'])){
            return $this->phone['area_code'].' '.$this->phone['number'].' '.$this->phone['extension'];
        }
        return $this->phone;
    }

    public function email() {
        return $this->email;
    }

    public function nickname() {
        return $this->nickname;
    }

    public function identification() {
        if(is_array($this->identification)){
            return $this->identification['number'];
        }
        return $this->identification;
    }

    public function identificationType() {
        if(is_array($this->identification)){
            return $this->identification['type'];
        }
        return null;
    }

}