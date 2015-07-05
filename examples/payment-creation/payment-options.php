<?php require_once '../bootstrap.php';

use Dnetix\MercadoPago\MercadoPago;

/**
 * All options that can be passed to the preference listed
 */

// Instanciate a new MercadoPago object, normally this can be binded to a container and used across the project
$mercadoPago = MercadoPago::load($config);

// As many products as needed
$mercadoPago->addItem([
    'id' => 'PRODUCT_ID',
    'title' => 'PRODUCT_TITLE',
    'description' => 'PRODUCT_DESCRIPTION',
    'quantity' => 1,
    'unit_price' => 10000,
    'picture_url' => 'OPTIONAL_URL_IMAGE_OF_PRODUCT',
]);

// An external reference can be passed to the preference, this is very useful later to track payments of invoices
$mercadoPago->addExternalReference('YOUR_EXTERNAL_REFERENCE');

// More optional parameter that you can set for the preference, use the ones you need.
$mercadoPago->addExtraParameters([
    'payer' => [
        'name' => 'user-name',
        'surname' => 'user-surname',
        'email' => 'user@email.com',
        'date_created' => 'yyyy-mm-ddThh:mm:ss.mmm-04:00',
        'phone' => [
            'area_code' => '11',
            'number' => '4444-4444'
        ],
        'identification' => [
            'type' => 'DNI',
            'number' => '12345678'
        ],
        'address' => [
            'street_name' => 'Street',
            'street_number' => 123,
            'zip_code' => '1430'
        ]
    ],
    'payment_methods' => [
        'excluded_payment_methods' => [
            [
                'id' => 'amex',
            ]
        ],
        'excluded_payment_types' => [
            [
                'id' => 'ticket'
            ]
        ],
        'installments' => 24,
        'default_payment_method_id' => null,
        'default_installments' => null,
    ],
    'shipments' => [
        'receiver_address' => [
            'zip_code' => '1430',
            'street_number'=> 123,
            'street_name'=> 'Street',
            'floor'=> 4,
            'apartment'=> 'C'
        ]
    ],
    'expires' => false,
    'expiration_date_from' => null,
    'expiration_date_to' => null
]);

$preference = $mercadoPago->createPreference();

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

    <a href="<?= $preference->initPoint() ?>" name="MP-Checkout" class="">Pay Link</a>
    <!--  This script it's only needed if you want the payment on AJAX  -->
    <script type="text/javascript" src="http://mp-tools.mlstatic.com/buttons/render.js"></script>

</body>
</html>
