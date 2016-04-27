<?php

namespace App\Interactions\Echosign;

use Psr\Http\Message\ResponseInterface;
use App\Echosign\AccessToken;
use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;

class Provider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    private $api_access_point = 'https://api.na1.echosign.com';

    public $scopeSeparator = '+';
    public $scopes = ['agreement_read', 'agreement_send', 'agreement_write', 'library_read'];

    public function __construct(array $options = [], array $collaborators = [])
    {
        $this->redirectUri = $options['redirectUri'] ?? null;
        $this->scopes = $options['scopes'] ?? null;
        $this->urlAuthorize = $options['urlAuthorize'] ?? null;

        parent::__construct($options, $collaborators);
    }

    public function setApiAccessPoint($api_access_point)
    {
        $this->api_access_point = $api_access_point;
    }

    public function getBaseAuthorizationUrl()
    {
        return $this->urlAuthorize;
    }

    protected function getAuthorizationParameters(array $options)
    {
        $options = [
            'redirect_uri'  => $this->redirectUri,
            'client_id'     => $this->clientId,
            'scope'         => $this->scopes,
            'response_type' => 'code',
        ];

        if (is_array($options['scope'])) {
            $separator = $this->getScopeSeparator();
            $options['scope'] = implode($separator, $options['scope']);
        }

        return $options;
    }

    protected function getAuthorizationQuery(array $params)
    {
        return urldecode(http_build_query($params));
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        if ($params['grant_type'] == 'refresh_token') {
            return $this->api_access_point . '/oauth/refresh';
        }

        return $this->api_access_point . '/oauth/token';
    }

    public function getResourceOwnerDetailsUrl(\League\OAuth2\Client\Token\AccessToken $token)
    {

    }

    protected function getDefaultScopes()
    {
        return $this->scopes;
    }

    protected function getScopeSeparator()
    {
        return $this->scopeSeparator;
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {

    }

    protected function createResourceOwner(array $response, \League\OAuth2\Client\Token\AccessToken $token)
    {

    }

    protected function createAccessToken(array $response, AbstractGrant $grant)
    {
        return new \App\Interactions\Echosign\AccessToken($response);
    }
}
