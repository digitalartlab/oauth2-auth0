<?php
namespace Riskio\OAuth2\Client\Test\Provider;

use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\TestCase;
use Riskio\OAuth2\Client\Provider\Auth0 as OauthProvider;
use RuntimeException;

class Auth0Test extends TestCase
{
    const DEFAULT_ACCOUNT = 'mock_account';
    const DEFAULT_CUSTOM_DOMAIN = 'auth.example.com';

    protected $config = [
        'account'      => self::DEFAULT_ACCOUNT,
        'customDomain' => self::DEFAULT_CUSTOM_DOMAIN,
        'clientId'     => 'mock_client_id',
        'clientSecret' => 'mock_secret',
        'redirectUri'  => 'none',
    ];

    /**
     * @dataProvider regionDataProvider
     */
    public function testGetAuthorizationUrl($region, $expectedHost)
    {
        $provider = new OauthProvider(array_merge($this->config, ['region' => $region]));
        $url = $provider->getAuthorizationUrl();
        $parsedUrl = parse_url($url);

        $this->assertEquals($expectedHost, $parsedUrl['host']);
        $this->assertEquals('/authorize', $parsedUrl['path']);
    }

    public function testGetAuthorizationUrlWhenAccountIsNotSpecifiedShouldThrowException()
    {
        unset($this->config['account']);

        $provider = new OauthProvider($this->config);

        $this->expectException(RuntimeException::class);
        $provider->getAuthorizationUrl();
    }

    /**
     * @dataProvider regionDataProvider
     */
    public function testGetUrlAccessToken($region, $expectedHost)
    {
        $provider = new OauthProvider(array_merge($this->config, ['region' => $region]));
        $url = $provider->getBaseAccessTokenUrl();
        $parsedUrl = parse_url($url);

        $this->assertEquals($expectedHost, $parsedUrl['host']);
        $this->assertEquals('/oauth/token', $parsedUrl['path']);
    }

    public function testGetAccessTokenUrlWhenAccountIsNotSpecifiedShouldThrowException()
    {
        unset($this->config['account']);

        $provider = new OauthProvider($this->config);

        $this->expectException(RuntimeException::class);
        $provider->getBaseAccessTokenUrl();
    }

    /**
     * @dataProvider regionDataProvider
     */
    public function testGetUrlUserDetails($region, $expectedHost)
    {
        $provider = new OauthProvider(array_merge($this->config, ['region' => $region]));

        $accessTokenDummy = $this->getAccessToken();

        $url = $provider->getResourceOwnerDetailsUrl($accessTokenDummy);
        $parsedUrl = parse_url($url);

        $this->assertEquals($expectedHost, $parsedUrl['host']);
        $this->assertEquals('/userinfo', $parsedUrl['path']);
    }

    /**
     * @expectedException \Riskio\OAuth2\Client\Provider\Exception\AccountNotProvidedException
     */
    public function testGetUserDetailsUrlWhenAccountIsNotSpecifiedShouldThrowException()
    {
        unset($this->config['account']);

        $provider = new OauthProvider($this->config);

        $accessTokenDummy = $this->getAccessToken();
        $provider->getResourceOwner($accessTokenDummy);
    }

    /**
     * @expectedException \Riskio\OAuth2\Client\Provider\Exception\InvalidRegionException
     */
    public function testGetUserDetailsUrlWhenInvalidRegionIsProvidedShouldThrowException()
    {
        $this->config['region'] = 'invalid_region';

        $provider = new OauthProvider($this->config);

        $accessTokenDummy = $this->getAccessToken();
        $provider->getResourceOwner($accessTokenDummy);
    }

    public function regionDataProvider()
    {
        return [
            [
                OauthProvider::REGION_US,
                sprintf('%s.auth0.com', self::DEFAULT_ACCOUNT),
            ],
            [
                OauthProvider::REGION_EU,
                sprintf('%s.%s.auth0.com', self::DEFAULT_ACCOUNT, OauthProvider::REGION_EU),
            ],
            [
                OauthProvider::REGION_AU,
                sprintf('%s.%s.auth0.com', self::DEFAULT_ACCOUNT, OauthProvider::REGION_AU),
            ],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|AccessToken
     */
    private function getAccessToken()
    {
        return $this->getMockBuilder(AccessToken::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
