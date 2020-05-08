<?php

namespace App\Providers;

use App\Console\Commands\PassportKeysCommand;
use DateInterval;
use Illuminate\Auth\RequestGuard;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Laravel\Passport\Guards\TokenGuard;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use League\OAuth2\Server\ResourceServer;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use Laravel\Passport\Bridge\PersonalAccessGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use Laravel\Passport\Bridge;
use League\OAuth2\Server;

class PassportServiceProvider  extends \Laravel\Passport\PassportServiceProvider
{
    const RSA_KEY_PATTERN =
        '/^(-----BEGIN (RSA )?(PUBLIC|PRIVATE) KEY-----\n)(.|\n)+(-----END (RSA )?(PUBLIC|PRIVATE) KEY-----)$/';
    private $private_key = "-----BEGIN RSA PRIVATE KEY-----
MIIJKQIBAAKCAgEAtP4+C/g5invOYLJo16raXeEqTmarx0eg1ersDRkPMvICDfo7
8vZ62yJXppCuF7f6URt5C1PTG4NdU9vmUMLGFfnFl98UrXSTwjpGBb6oBUyacE4f
jSzdhBhFLfEccQSBwYG4Tro43+RGjBF5SoDoNo9YVcA5PTfchQ4pna1EGmLXqxI5
y8GbzNOD/at1OqMsi0dhs58eLW2zfMP256fQAe9bBQg2qbBT3xGs9icEE1ASJQjf
JfEQjIDgXu1TL9HnnlmWDulTOYvEZ/j04+ZKTvItAXF0qGhZlB+TpKMgsxjlptmf
jbbPFx5D+KS/oyivH/nxNBGWHMKeI+ihfzFoerUO90l8KmtYJ6hL3dMCDj67Nbq5
E1RhjGyC682a1qzApofGIH00d5yM5aHj5pmHgseaP5/g6jftXFiIGGIupZe6SxBw
kYAWGrbMvjGBl4LXpCt3orsjA8Xl3DRFsj38ltiltnLMqw5Mlvoqrh/WqJd+SEx/
G1r1y7rXdl5Qm1QQgDJN4gsCw1rfUPjH5jNMPsxChnhgpe/CNvlZUMjIxninMhZ1
xFeyU06lqkdG1D5K8xkkliID3hwCRku0RtQkghRjY5YDmSHEvj1zyZ5RWpRB6Kef
nmMhYCkFllKJCiDLlEKpGoH8AWEzQCiPY4VNLRURU8f7A9x/RXljAzSdewUCAwEA
AQKCAgBvuteAuh6DX8RaKyjPojglZh+gwH7giVY/WEOaHlMkpwMfr88cQI49AqlL
ONtP5xIOyb/ifz++J00QvJokVujDniodg+gW3/VrHGAnobfFdbGNDJ0roHuq1PS3
1LUzC44yb6rgZtM/W4M1aoAOvx8at0jXvs9W/EoUlVaZcOGuzD/I6nplT0KnohdE
HHlE1oBkBo/Iu7x8XNFFfw/Hl++vkhwFMjnUbPaJqTa1ygYZCf/5fs2KdZM6uUBJ
OYZ16cVrGnCpCd6HksGPUb+ThJFwxM2Fq41RfQF3kDqrksxX41G5u3RknmqfId7h
+ooW5cGeIsSE2Spq3LWVA0HW7CBLPQggpyGDld2HPBkMMdFiNQnXuAoRZGt1vr0I
Qd1bz4VDbpevTJSoA0v5xxfgX1gm9/B8R9tZvySvOZn1EGdYuW55eeEsrOs0sjhG
HQWsIZrL8vIxHrywmf43ih/lAkWgYZW0SVSCIE+mzAu+xybSR/B9wKNra9SrP3yx
8JVbDXzx5z4J59PsNWGOmv+WwVTjbuMCkxM3LZwva8qzJaOsWjVpNPseITNlvFaS
hKCxT9emEYZ00+pyTxPJlSnlePHFzIV1gTeqxzTCaU2/z4rYrHUQ5U3H/fIkec7S
a9ZCqlygxdmOBoaeOjVhyqUU6fvCQ3z4elvQkGQ78NZS4LUZ7QKCAQEA6ngoJpLt
rr7xJqgdJZJH/gCnXdELlODjLr+pb/c8jGCjAEf297uspv/Ljr0aS28P1LxiE7Wu
yMVAOOVltBCCfwYcZl2NRO+2xeAGTMqZV/lhQFtUn3yKQkGBybmfsC9afJW9Sdgt
X8mWZVzlGLcXXO4AUvm3gTkXlqQqGEn4576/v+QvelkYNu+aI1+xZHqabuPxnKkZ
wgynvQ8hRoB68IHqnidXWm6qmTnUNhv4MI24jCw024X7GE01naPL6rGFYSidou5A
y0b0nlVvJhEEih3vZYxLcPNFmKAPaQK9PoN4rBda8/H9OyNkD2zCc7ZJmtPrrKkY
nN3GxCc53aIumwKCAQEAxZz7Cu2S0p/gF17RF6f6GXIubEi3AoBTHrORCe6p0eea
qt0sYtpuj/QfbXgWUADOhc5HZS4M7cPYU6RNDg5JDsy6YLtn6snx7qC+6++jMtdx
ZaOt/GFF0aHTN12tSMTL/AuVKbwR+pwxMu4GqqpYA+GJhCj1+yJZ+Hnucc4xkckj
8uwxX4d6zRVyRpbpop9Ex6JXitGPJbiqjWdlCGfVFy9WbRkR3+m+Is5sOoSEaVu4
JfBvvPaxpHSp9mzMHcpXvCZneP08jb9woNhS7nyWTuYmVZeYtFHQjjYugO+b8dVU
IsbM7/tLDtxYjSRT6iHEe5rsjmGS/QhzcbV7lBHG3wKCAQEAtBXVwMrZA35keexl
dpYD9XNLGSOWYaLY2u8alISwXKFckLq1VVEwKpQNJHTZ3VZQMnT+X2UL+eiGiyym
EMcdc7Kd3YG9H1V+bDzEmfaCEK/ZojSDqntdNcZaiTWpEQlN1qKr4CvJreiHOxM+
Yt09dILYfOKPrxDKrUkpi75FDrnTTO9WCAKmNouRN9onxaTlOIaa7Fh1EDXoH62f
QSctRcyDBzXwQeT94vInq+ePmLtBI2hQsMsXMHMc2xcTaA8xAo7PhHS6D2dQOswj
snkfo3WaicRXrUksCy3kqCMSdxFDfenyUatrdCCamb5PG9DGd8SNHvBBlzo/kCkQ
qMoIQQKCAQEAl6qK9nEuHZ98wxMPkYNnrT34KT2J0E6i/M4+hAv7pFGIYJkNpvUU
pEFuGXtwTIjB+Oo/24UDgTytfEx+o0oNgmFYrkNHV2kU2NvgjH5ImA3IR4TfVPNn
PaRg0n6AL3hzQoe1POnONrTIAdmznirNEHV6MMerOsq7mLwkaI4jd0uT3q6umi4F
7v8FzO2odRqeFEYhwDZkEkRwCcUQQupGHtxDDcqO8HaaamZZpHPumFo/tEaby8De
yGSNycboxfh1a79h7PcXEYebrqc4xzcxuW5kf46xMbwVQACPon/EBVCAmYxQ3+LT
UZ2GDRWTnf+6qE9YPVLgGHny+JOcPCGEqwKCAQBxT5nz0RpXOStaWAPaG6gh8ICE
h2d8donimqOCPLubuRFHgRN/HC7+ivl/45RdDnY+OGRkmZQIQ/20vF5KGz8juHNC
9c5ROP39AEltphIx+hSlwkNGj1l266iOw5VQyqQLU70yyDYAgkls0QysRNcwXkeV
p5mBcHgMxC6h+82BKZTFTHxw2fwiQLpDWaML6TDYhm4HWXwIiNcNUYPkvrfcyCH+
x1S1mj+qad0NMTv38eTUOBif4NPBZNZJnJF1bA3o5fsH/NhsbNSyvhPd1ZHsu4NR
MiXPmnpUpfRTYnpEZqHN40xQ8z3dObWxxgjGES/CgFQ4iL3LTA+auIIUwhY/
-----END RSA PRIVATE KEY-----";
    private $public_key="-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAtP4+C/g5invOYLJo16ra
XeEqTmarx0eg1ersDRkPMvICDfo78vZ62yJXppCuF7f6URt5C1PTG4NdU9vmUMLG
FfnFl98UrXSTwjpGBb6oBUyacE4fjSzdhBhFLfEccQSBwYG4Tro43+RGjBF5SoDo
No9YVcA5PTfchQ4pna1EGmLXqxI5y8GbzNOD/at1OqMsi0dhs58eLW2zfMP256fQ
Ae9bBQg2qbBT3xGs9icEE1ASJQjfJfEQjIDgXu1TL9HnnlmWDulTOYvEZ/j04+ZK
TvItAXF0qGhZlB+TpKMgsxjlptmfjbbPFx5D+KS/oyivH/nxNBGWHMKeI+ihfzFo
erUO90l8KmtYJ6hL3dMCDj67Nbq5E1RhjGyC682a1qzApofGIH00d5yM5aHj5pmH
gseaP5/g6jftXFiIGGIupZe6SxBwkYAWGrbMvjGBl4LXpCt3orsjA8Xl3DRFsj38
ltiltnLMqw5Mlvoqrh/WqJd+SEx/G1r1y7rXdl5Qm1QQgDJN4gsCw1rfUPjH5jNM
PsxChnhgpe/CNvlZUMjIxninMhZ1xFeyU06lqkdG1D5K8xkkliID3hwCRku0RtQk
ghRjY5YDmSHEvj1zyZ5RWpRB6KefnmMhYCkFllKJCiDLlEKpGoH8AWEzQCiPY4VN
LRURU8f7A9x/RXljAzSdewUCAwEAAQ==
-----END PUBLIC KEY-----";
    /**
     * Register services.
     *
     * @return void
     */
    /**
     * Make the authorization service instance.
     *
     * @return \League\OAuth2\Server\AuthorizationServer
     */
    public function makeAuthorizationServer()
    {

        $server = new AuthorizationServer(
            $this->app->make(Bridge\ClientRepository::class),
            $this->app->make(Bridge\AccessTokenRepository::class),
            $this->app->make(Bridge\ScopeRepository::class),
            $this->saveKeyToFile($this->private_key),
            $this->saveKeyToFile($this->public_key)
        );

        $server->setEncryptionKey(app('encrypter')->getKey());

        return $server;
    }
    private function saveKeyToFile($key)
    {
        $tmpDir = sys_get_temp_dir();
        $keyPath = $tmpDir . '/' . sha1($key) . '.key';

        if (!file_exists($keyPath) && !touch($keyPath)) {
            // @codeCoverageIgnoreStart
            throw new \RuntimeException('"%s" key file could not be created', $keyPath);
            // @codeCoverageIgnoreEnd
        }

        if (file_put_contents($keyPath, $key) === false) {
            // @codeCoverageIgnoreStart
            throw new \RuntimeException('Unable to write key file to temporary directory "%s"', $tmpDir);
            // @codeCoverageIgnoreEnd
        }

        if (chmod($keyPath, 0600) === false) {
            // @codeCoverageIgnoreStart
            throw new \RuntimeException('The key file "%s" file mode could not be changed with chmod to 600', $keyPath);
            // @codeCoverageIgnoreEnd
        }

        return 'file://' . $keyPath;
    }
    protected function registerResourceServer()
    {
        $this->app->singleton(ResourceServer::class, function () {
            return new ResourceServer(
                $this->app->make(Bridge\AccessTokenRepository::class),
                $this->saveKeyToFile($this->public_key)
            );
        });
    }
    public function register()
    {
        $this->commands([
            PassportKeysCommand::class
        ]);
        $this->registerAuthorizationServer();

        $this->registerResourceServer();

        $this->registerGuard();


    }
}
