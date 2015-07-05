<?php require_once '../bootstrap.php';

use Dnetix\MercadoPago\MercadoPago;

/**
 * When creating a payment preference as a MarketPlace the process it's the same, but a marketplace_fee has to
 * be setted, you can do that in the configuration for the instanciation, or in every preference creation
 * Also the access token must be the seller's one so you can set it when creating the preference if you have already one
 * or request one with the authorization code
 */

// Instanciate a new MercadoPago object, normally this can be binded to a container and used across the project
$mercadoPago = MercadoPago::load($config);

// In case you need to obtain a new access token for the seller. If this is the case, just ommit the
// CLIENT_ACCESS_TOKEN_VALUE parameter on the createPreference method. And if you want to store it, follow the
// obtain-authtoken.php steps
$mercadoPago->setAuthorizationCode('SELLER_AUTHORIZATION_CODE')
    ->obtainAccessToken();

// Creating the payment preference
$preference = $mercadoPago->addItem([
        'id' => 'PRODUCT_ID',
        'title' => 'PRODUCT_TITLE',
        'description' => 'PRODUCT_DESCRIPTION',
        'quantity' => 1,
        'unit_price' => 10000,
        'picture_url' => 'OPTIONAL_URL_IMAGE_OF_PRODUCT',
    ])
    ->setFee(8.5)
    ->addExternalReference('YOUR_EXTERNAL_REFERENCE')
    ->createPreference('CLIENT_ACCESS_TOKEN_VALUE');

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
