<?php
namespace App\Providers;

use App;
use App\Entities\AccessTokenRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider;
use Laravel\Passport\Bridge\ClientRepository;
use Laravel\Passport\Bridge\ScopeRepository;
use League\OAuth2\Server\AuthorizationServer;

class JwtClaimsServiceProvider extends PassportServiceProvider
{

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->setupConfig();
        parent::boot();
    }

    /**
     * Make the authorization service instance.
     *
     * @return AuthorizationServer
     */
    public function makeAuthorizationServer()
    {
        return new AuthorizationServer(
            $this->app->make(ClientRepository::class),
            $this->app->make(AccessTokenRepository::class),
            $this->app->make(ScopeRepository::class),
            'file://'.Passport::keyPath('oauth-private.key'),
            'file://'.Passport::keyPath('oauth-public.key')
        );
    }

    protected function setupConfig()
    {
       // $source = realpath('/home/jaime/Documentos/projects/vagrant-immigration/vitalrecords/oauthserver/lumen-oauth2/config/jwt-claims.php');
        //$this->publishes([$source => $source]);
        //$this->mergeConfigFrom($source, 'jwt-claims');
    }
}