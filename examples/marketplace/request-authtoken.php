<?php require_once '../bootstrap.php';

use Dnetix\MercadoPago\MercadoPago;

/**
 * When using MarketPlace you need to obtain an access token from the users. In order to do that
 * a link has to be diplayed to the user.
 * The redirection url for the authorization token should be config in the application and in the configuration
 * passed to the instanciation
 *
 * https://applications.mercadopago.com.co
 */

// Instanciate a new MercadoPago object, normally this can be binded to a container and used across the project
$mercadoPago = MercadoPago::load($config);

// You can, if you need to, pass parameters to the url in case that you want to be included in the response url so the
// redirection can be tracked.
$authorizationPoint = $mercadoPago->authorizationPoint([
    'SOME_VAR' => 'SOME_VALUE'
]);

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

    <a href="<?= $authorizationPoint ?>" class="">Authorize this app to charge in your name</a>

</body>
</html>
