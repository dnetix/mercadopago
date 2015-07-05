# MercadoPago SDK module for Payments integration

* [Installation](#installation)
* [Basic checkout](#basic-checkout)
* [Examples](#examples)

<a name="installation"></a>
## Installation

### With Composer

From command line
```
composer require dnetix/mercadopago
```
As a dependency in your project's composer.json
```json
{
    "require": {
        "dnetix/mercadopago": "1.*"
    }
}
```

<a name="basic-checkout"></a>
## Basic checkout

* Create your application in [https://applications.mercadopago.com](https://applications.mercadopago.com)
* Set the values for the configuration array (Structure in examples/config.php)
* Instanciate a MercadoPago object to create a preference. It can be binded to an IoC and create the preference

```php
$mercadopago = Dnetix\MercadoPago\MercadoPago::load($config);

$preference = $mercadoPago->addItem([
        'id' => 'PRODUCT_ID',
        'title' => 'PRODUCT_TITLE',
        'description' => 'PRODUCT_DESCRIPTION',
        'quantity' => 1,
        'unit_price' => 10000,
        'picture_url' => 'OPTIONAL_URL_IMAGE_OF_PRODUCT',
    ])
    ->addExternalReference('OPTIONAL_EXTERNAL_REFERENCE')
    ->createPreference();
```

* In your html

```html
    <a href="<?= $preference->initPoint() ?>" name="MP-Checkout" class="">Payment Link</a>
```

* And that's all. This process is explained in detail on examples/payment-creation/payment-simple.php

<a name="examples"></a>
## Examples

The examples for the different usage methods such as marketplace charges, payment search, notification handling and others are explained in the examples folder.