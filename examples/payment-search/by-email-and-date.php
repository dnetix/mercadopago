<?php require_once '../bootstrap.php';

use Dnetix\MercadoPago\MercadoPago;

/**
 * Searching payments for external reference.
 */

// Instanciate a new MercadoPago object, normally this can be binded to a container and used across the project
$mercadoPago = MercadoPago::load($config);

// These filters are an intended for reference, use it as you need it
// In this case it you're looking for different emails, concatenate it with &
// The date range it's optional too
$filters = [
    'payer_email' => 'email-to-lookfor@example.com',
    'begin_date' => '2011-01-01T00:00:00Z',
    'end_date' => '2011-02-01T00:00:00Z'
];

$payments = $mercadoPago->searchPayments($filters)

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

    <table>
        <?php
        foreach($payments as $payment){
            // $payment it's an instance of Payment Class, use the available methods in it.
        ?>
            <tr>
                <td><?= $payment->id() ?></td>
                <td><?= $payment->siteId() ?></td>
                <td><?= $payment->dateCreated() ?></td>
                <td><?= $payment->externalReference() ?></td>
                <td><?= $payment->status() ?></td>
            </tr>
        <?php
        }
        ?>
    </table>

</body>
</html>
