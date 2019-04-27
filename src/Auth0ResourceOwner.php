<?php
namespace DigitalArtLab\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class Auth0ResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * @var array
     */
    protected $response;

    public function __construct(array $response = [])
    {
        $this->response = $response;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getValueByKey($this->response, 'sub');
    }

    /**
     * Returns email address of the resource owner
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->getValueByKey($this->response, 'email');
    }

    /**
     * Returns full name of the resource owner
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->getValueByKey($this->response, 'name');
    }

    /**
     * Returns given name of the resource owner
     *
     * @return string|null
     */
    public function getGivenName()
    {
        return $this->getValueByKey($this->response, 'given_name');
    }

    /**
     * Returns family name of the resource owner
     *
     * @return string|null
     */
    public function getFamilyName()
    {
        return $this->getValueByKey($this->response, 'family_name');
    }

    /**
     * Returns nickname of the resource owner
     *
     * @return string|null
     */
    public function getNickname()
    {
        return $this->getValueByKey($this->response, 'nickname');
    }

    /**
     * Returns picture url of the resource owner
     *
     * @return string|null
     */
    public function getPictureUrl()
    {
        return $this->getValueByKey($this->response, 'picture');
    }

    /**
     * Returns address of the resource owner
     *
     * @return array|null
     */
    public function getAddress()
    {
        return $this->getValueByKey($this->response, 'address');
    }

    /**
     * Returns phone number of the resource owner
     *
     * @return array|null
     */
    public function getPhoneNumber()
    {
        return $this->getValueByKey($this->response, 'phone_number');
    }

    /**
     * Returns birthdate of the resource owner
     *
     * @return date|null
     */
    public function getBirthdate()
    {
        return $this->getValueByKey($this->response, 'birthdate');
    }

    /**
     * Returns roles of the resource owner
     *
     * @return array|null
     */
    public function getRoles()
    {
        return $this->getValueByKey($this->response, 'http://ckc-zoetermeer.nl/roles');
    }

    /**
     * Returns permissions of the resource owner
     *
     * @return array|null
     */
    public function getPermissions()
    {
        return $this->getValueByKey($this->response, 'http://ckc-zoetermeer.nl/permissions');
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return $this->response;
    }
}
