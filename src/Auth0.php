<?php
namespace DigitalArtLab\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use DigitalArtLab\OAuth2\Client\Provider\Exception\AccountNotProvidedException;
use DigitalArtLab\OAuth2\Client\Provider\Exception\Auth0IdentityProviderException;
use DigitalArtLab\OAuth2\Client\Provider\Exception\InvalidRegionException;

class Auth0 extends AbstractProvider
{
    use BearerAuthorizationTrait;

    const REGION_US = 'us';
    const REGION_EU = 'eu';
    const REGION_AU = 'au';

    protected $availableRegions = [self::REGION_US, self::REGION_EU, self::REGION_AU];

    protected $region = self::REGION_EU;

    protected $account;

    protected $customDomain;

    protected function getDomain()
    {
        if (empty($this->account)) {
            throw new AccountNotProvidedException();
        }
        if (!in_array($this->region, $this->availableRegions)) {
            throw new InvalidRegionException();
        }

        $domain = 'auth0.com';

        if ($this->region !== self::REGION_US) {
            $domain = $this->region . '.' . $domain;
        }

        return 'https://' . $this->account . '.' . $domain;
    }

    protected function getCustomDomain() {
        if (empty($this->customDomain)) {
            return $this->getDomain();
        }
        return 'https://' . $this->customDomain;
    }

    public function getBaseAuthorizationUrl()
    {
        return $this->getCustomDomain() . '/authorize';
    }

    public function getBaseAccessTokenUrl(array $params = [])
    {
        return $this->getCustomDomain() . '/oauth/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->getDomain() . '/userinfo';
    }

    public function getDefaultScopes()
    {
        return ['openid', 'email'];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            return Auth0IdentityProviderException::fromResponse(
                $response,
                $data['error'] ?: $response->getReasonPhrase()
            );
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new Auth0ResourceOwner($response);
    }
}
