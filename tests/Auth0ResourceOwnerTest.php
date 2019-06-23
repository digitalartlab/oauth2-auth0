<?php
namespace DigitalArtLab\OAuth2\Client\Test\Provider;

use PHPUnit\Framework\TestCase;
use DigitalArtLab\OAuth2\Client\Provider\Auth0ResourceOwner;

class Auth0ResourceOwnerTest extends TestCase
{
    public $response = [
        'email' => 'testuser@gmail.com',
        'email_verified' => true,
        'name' => 'Test User',
        'given_name' => 'Test',
        'family_name' => 'User',
        'picture' => 'https://lh5.googleusercontent.com/-NNasdfdfasdf/asfadfdf/photo.jpg',
        'nickname' => 'testuser',
        'birthdate' => '1996-10-19',
        'address' => array(
            'street_address' => 'Leidsewallen 80',
            'postal_code' => '2722 PC',
            'locality' => 'Zoetermeer',
            'country' => 'The Netherlands',
        ),
        'phone_number' => '+31 79 31 61 411',
        'sub' => 'auth0|113974520365241488704',
        'http://ckc-zoetermeer.nl/roles' => array(
            'user',
            'test',
        ),
        'http://ckc-zoetermeer.nl/permissions' => array(
            'ROLE_USER',
            'ROLE_TEST',
        ),
    ];

    public function testGetUserDetails()
    {
        $user = new Auth0ResourceOwner($this->response);

        $namespace = 'http://ckc-zoetermeer.nl';

        $this->assertEquals($this->response['name'], $user->getName());
        $this->assertEquals($this->response['given_name'], $user->getGivenName());
        $this->assertEquals($this->response['family_name'], $user->getFamilyName());
        $this->assertEquals($this->response['sub'], $user->getId());
        $this->assertEquals($this->response['email'], $user->getEmail());
        $this->assertEquals($this->response['address'], $user->getAddress());
        $this->assertEquals($this->response['phone_number'], $user->getPhoneNumber());
        $this->assertEquals($this->response['birthdate'], $user->getBirthdate());
        $this->assertEquals($this->response['http://ckc-zoetermeer.nl/roles'], $user->getRoles($namespace));
        $this->assertEquals($this->response['http://ckc-zoetermeer.nl/permissions'], $user->getPermissions($namespace));

        $this->assertEquals($this->response, $user->toArray());
    }
}
