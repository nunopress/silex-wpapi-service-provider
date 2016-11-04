<?php

namespace NunoPress\Silex\WpApi\Provider;

use NunoPress\Silex\WpApi\WpApi;
use NunoPress\Silex\WpApi\WpApiClient;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class WpApiServiceProvider
 *
 * @package NunoPress\Silex\WpApi\Provider
 */
class WpApiServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     */
    public function register(Container $app)
    {
        $app['wpapi'] = function (Container $c) {
            return new WpApi($c['wpapi.client']);
        };

        $app['wpapi.client'] = function (Container $c) {
            return new WpApiClient($c['wpapi.client.options']);
        };

        $app['wpapi.client.options'] = [];
    }

}