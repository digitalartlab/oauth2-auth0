# Auth0 Provider for OAuth 2.0 Client

[![Build Status](https://travis-ci.org/digitalartlab/oauth2-auth0.svg?branch=master)](https://travis-ci.org/digitalartlab/oauth2-auth0)
[![License](https://img.shields.io/github/license/digitalartlab/oauth2-auth0.svg)](https://github.com/digitalartlab/oauth2-auth0/blob/master/LICENSE)
[![Latest Stable Version](https://img.shields.io/github/tag/digitalartlab/oauth2-auth0.svg?label=version)](https://github.com/digitalartlab/oauth2-auth0/releases)

This package provides Auth0 OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

This version, which is a fork from [DigitalArtLabFr/oauth2-auth0](https://github.com/DigitalArtLabFr/oauth2-auth0) has our special Digital Art Lab flavour, with support for Auth0 custom domains and getting user roles on login.

## Installation

This version currently isn't on Packagist or the likes, so install it like this:

First, add this to your `composer.json`:
```json
{
    "repositories": [
        {
            "url": "https://github.com/digitalartlab/oauth2-auth0.git",
            "type": "git"
        }
    ],
    "require": {
        "digitalartlab/oauth2-auth0": "^2.1"
    }
}
```

Then, run `composer update`.

```
composer update
```

## Usage

Usage is the same as The League's OAuth client, using `DigitalArtLab\OAuth2\Client\Provider\Auth0` as the provider.

### Authorization Code Flow

You have to provide some parameters to the provider:

- region (optional):
   - description: Auth0 region
   - values:
      - DigitalArtLab\OAuth2\Client\Provider\Auth0::REGION_US
      - DigitalArtLab\OAuth2\Client\Provider\Auth0::REGION_EU (default value)
      - DigitalArtLab\OAuth2\Client\Provider\Auth0::REGION_AU
- account:
   - description: Auth0 account name
- customDomain (optional):
    - description: Auth0 custom domain, without `https://` or a trailing slash
- clientId
   - description: The client ID assigned to you by the provider
- clientSecret
   - description: The client password assigned to you by the provider
- redirectUri

```php
$provider = new DigitalArtLab\OAuth2\Client\Provider\Auth0([
    'region'       => '{region}',
    'account'      => '{account}',
    'customDomain' => 'auth.example.com',
    'clientId'     => '{auth0-client-id}',
    'clientSecret' => '{auth0-client-secret}',
    'redirectUri'  => 'https://example.com/callback-url'
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->state;
    header('Location: ' . $authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getName());

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}
```

## Refreshing a Token

Auth0's OAuth implementation does not use refresh tokens.
