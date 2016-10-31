<?php

namespace NunoPress\WpApiServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class WpApiServiceProvider
 *
 * @package NunoPress\WpApiServiceProvider
 */
class WpApiServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['wpapi'] = function (Container $c) {
            return new WpApi($c['wpapi.client']);
        };

        $pimple['wpapi.client'] = function (Container $c) {
            return new WpApiClient($c['wpapi.client.options']);
        };

        $pimple['wpapi.client.options'] = [];
    }

}