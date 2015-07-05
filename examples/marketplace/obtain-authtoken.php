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

// The authorization comes in the variable code of the response. To set it you can pass the full get response
// or just $_GET['code'];
$mercadoPago->setAuthorizationCode($_GET);

// It's highly recommended to store this authorization code somewhere in order to obtain access tokens when
// they expire

// Once the authorization code is setted you can request an access token with it. The $_GET variable is passed
// because if you pass additional parameters when you requested the auth code, they should match with the ones
// given here. So if you're obtaining the access token later on, not in the redirection, just pass the same parameters
// that when requesting it.
$mercadoPago->obtainAccessToken($_GET);

// This should be stored too but this data has an expiration time. Keep that in mind when you use it later.

/**
 * An access token has the following structure, if you want all this data just use
 * $mercadopago->accessData()
 *
 * [
 *      "access_token" => "SOME_LONG_STRING"
 *      "refresh_token" => "ANOTHER_STRING"
 *      "live_mode" => true
 *      "user_id" => 123445667
 *      "token_type" => "bearer"
 *      "expires_in" => 21600
 *      "scope" => "offline_access read write"
 * ]
 *
 * For the access_token value just use
 * $mercadopago->accessToken();
 */

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

    <div>Access Token: <?= $mercadoPago->accessToken() ?></div>

</body>
</html>
