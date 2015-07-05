<?php require_once '../bootstrap.php';

use Dnetix\MercadoPago\MercadoPago;

/**
 * This process is internal and there is no user intervention in it.
 * The return is a MerchantOrder object. Check the available methods in it. In this example a simple
 * use case it's presented
 */

// Instanciate a new MercadoPago object, normally this can be binded to a container and used across the project
$mercadoPago = MercadoPago::load($config);

try {
    $merchantOrder = $mercadoPago->getMerchantOrderIPN($_POST);
    if(is_null($merchantOrder)){
        // It's really not a response from MercadoPago do more security stuff
    }

    // At this point, a response has been obtain. So you can check for your internal invoice data with the
    // externalReference if you use it.
    $externalReference = $merchantOrder->externalReference();

    if($merchantOrder->isPayed()){
        // The amount has been payed. if matches the amount of your internal invoice you can release your items

        // Check if the amount it's accurate, the following method return how much it's been charged in MercadoPago
        // DO NOT represent how much has been approved
        $merchantOrder->totalAmount();

        $merchantOrder->totalApproved();
    }else{
        // It's not payed yet. Could be partially payed, or not at all. If you want to check. use
        $payments = $merchantOrder->payments();
        // This is an array of payments associated.
        // See the Objects\Payment class for the available methods to use with each one
    }

}catch (Exception $e){
    // It's not a response from MercadoPago do your security stuff
}
