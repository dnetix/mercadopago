<?php require_once '../bootstrap.php';

use Dnetix\MercadoPago\MercadoPago;

/**
 * All options that can be passed to the preference listed
 */

// Instanciate a new MercadoPago object, normally this can be binded to a container and used across the project
$mercadoPago = MercadoPago::load($config);

// These filters are an intended for reference, use it as you need it
$filters = [
    'range' => 'date_created',
    'begin_date' => 'NOW-1MONTH',
    'end_date' => 'NOW',
    'status' => 'approved',
    'operation_type' => 'regular_payment'
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
