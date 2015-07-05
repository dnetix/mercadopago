<?php require_once '../bootstrap.php';

use Dnetix\MercadoPago\MercadoPago;

/**
 * A simple button for the preference
 */

// Instanciate a new MercadoPago object, normally this can be binded to a container and used across the project
$mercadoPago = MercadoPago::load($config);

// As many products as needed
$preference = $mercadoPago->addItem([
        'id' => 'PRODUCT_ID',
        'title' => 'PRODUCT_TITLE',
        'description' => 'PRODUCT_DESCRIPTION',
        'quantity' => 1,
        'unit_price' => 10000,
        'picture_url' => 'OPTIONAL_URL_IMAGE_OF_PRODUCT',
    ])
    ->addExternalReference('YOUR_EXTERNAL_REFERENCE')
    ->createPreference();

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
