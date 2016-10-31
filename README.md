# Introduction
This is a Service Provider for Silex and added the access to WordPress API websites easy.

# Installation
You can simply install with composer:

`composer require nunopress/silex-wpapi-serviceprovider`

In your Silex application register the service provider:

```php
$app->register(new NunoPress\WpApiServiceProvider());
```

# Configuration
You can configure this items inside the container:

### wpapi.client
With default the service provider use GuzzleHttp client, but you can use what you want, you need to implement our `NunoPress\WpApiServiceProvider\WpApiClientInterface`.

### wpapi.client.option
If you use our client you can pass GuzzleHttp options here, most userful for the `base_uri` instead to use all times the full uri or for the authentication.

# Usage
This is our interface for get default WordPress API endpoint's and we added one method for call the Advanced Custom Fields API (_you need a WordPress plugin ACF Rest API_).

Example for get the first page from pages in order by menu_order and asc:

```php
$wpapi->getItems('pages', 'menu_order', 'asc', 1)
```

If you want get the Advanced Custom Fields options page you can do it:

```php
$wpapi->getAcf('options')
```

# API

```
/**
 * @param string $type
 * @param string $orderBy
 * @param string $order
 * @param int $page
 *
 * @return array
*/
public function getItems($type = 'posts', $orderBy = 'menu_order', $order = 'desc', $page = 1)
```

```
/**
 * @param string $type
 * @param null|int $id
 *
 * @return array
 */
public function getAcf($type, $id = null)
```