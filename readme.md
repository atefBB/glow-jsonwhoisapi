glow-jsonwhoisapi
=====================================

A simple API wrapper for https://jsonwhoisapi.com

# Installing


The recommended way to install Glow\JSONWhoisAPI is to use [Composer.](http://www.getcomposer.com)

```
composer require glow/jsonwhoisapi
```


# Example
```
$whois = new Glow\JSONWhois\Api;
$whois->setCustomerId('{customerId}')
              ->setApiKey('{apiKey}');
              
$data = $whois->query('shiftmail.io');
```