helloPay SDK For PHP
========
[![Build Status](https://travis-ci.org/hellopay/hellopay-php-sdk.svg)](https://travis-ci.org/hellopay/hellopay-php-sdk)
[![Latest Stable Version](http://img.shields.io/badge/Latest%20Stable-master-blue.svg)](https://packagist.org/packages/hellopay/php-sdk)

helloPay is a secure payment platform, created by a team that has brought together their extensive knowledge and experience in global payment systems to make sending and receiving money easier, both for personal and commercial use. It is legally established as HELLOPAY SINGAPORE PTE. LTD, 12 Prince Edward Road #06-08, Bestway Building, Singapore 079212.

When you register with us, you just need your mobile number and email address to get started. Our network will then enable you to safely make online purchases without revealing your payment details. All your data and transactions are kept 100% secure by our experienced anti-fraud team, and our security measures adhere to the highest safety and security standards.

helloPay was also created for businesses, offering flexible payment options to your customers to accelerate growth. A range of local Singaporean payment channels, including credit/debit card and Direct Debit for Internet Bank users via eNETS, is available both on mobile and online devices to enable you to provide a seamless checkout experience. We strive to meet your daily business needs with transparent and affordable price plans, a simple one-step integration process and daily payouts, so you can focus on what matters.

## Installation

The helloPay PHP SDK can be installed with [Composer](https://getcomposer.org/). Add the helloPay PHP SDK package to your composer.json file.
```json
{ 
  "require": { "hellopay/php-sdk": "dev-master" } 
}
```

## Usage
> **Note:** This version of the helloPay SDK for PHP requires PHP 5.4 or greater.

Cancel a transaction example

```php
$helloPay = new \HelloPay\HelloPay([
    'shopConfig' => '{shop-config}',
    'apiUrl' => '{api-url}',
    //'sslEnabled' => true|false //optional
]);

try {
	$purchaseId = 'AAEAAADoKU642BAQq_wQDOlqdB5nbGFlWoZMKyz5tOFWC64mbMdXZMLm';
	$response = $helloPay->cancelTransaction($purchaseId);
} catch(HelloPay\Exceptions\HelloPayResponseException $e) {
	  // When helloPay returns an error
      echo 'There\'s an error: ' . $e->getMessage();
      exit;
} catch(HelloPay\Exceptions\HelloPaySDKException $e) {
	  // When validation fails or other local issues
      echo 'helloPay SDK returned an error: ' . $e->getMessage();
      exit;
}

if ($response) {
    echo "Transaction has been cancelled successfully!";
} else {
    echo $helloPay->getLastMessage();
}

```

## Tests
1. [Composer](https://getcomposer.org/) is a prerequisite for running the tests. Install composer globally, then run `composer install` to install required files.

2. The tests can be executed by running this command from the root directory:

```bash
$ ./vendor/bin/phpunit
```

## License

Please see the [license file](https://github.com/hellopay/hellopay-php-sdk/blob/master/LICENSE) for more information.
