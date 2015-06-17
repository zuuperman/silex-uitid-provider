<?php

namespace CultuurNet\UiTIDProvider\Security;

use Silex\Application;
use Silex\ServiceProviderInterface;

class UiTIDSecurityServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app['uitid_firewall_user_provider'] = $app->share(function (Application $app) {
            return new UiTIDUserProvider($app['uitid_user_service']);
        });

        $app['security.authentication_listener.factory.uitid'] = $app->protect(function ($name, $options) use ($app) {
            $app['security.authentication_provider.' . $name . '.uitid'] = $app->share(function () use ($app) {
                return new UiTIDAuthenticator($app['uitid_user_service']);
            });

            $app['security.authentication_listener.' . $name . '.uitid'] = $app->share(function () use ($app) {
                return new UiTIDListener(
                    $app['security.authentication_manager'],
                    $app['security.token_storage'],
                    $app['uitid_user_session_service']
                );
            });

            return array(
                'security.authentication_provider.' . $name . '.uitid',
                'security.authentication_listener.' . $name . '.uitid',
                null,
                'pre_auth',
            );
        });
    }

    /**
     * @inheritdoc
     */
    public function boot(Application $app)
    {
    }
}