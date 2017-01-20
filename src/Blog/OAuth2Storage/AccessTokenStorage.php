<?php
namespace Blog\OAuth2Storage;

use OAuth2\Storage\AccessTokenInterface;

class AccessTokenStorage implements AccessTokenInterface
{

    /*
     public function getAccessToken($oauthToken)
    {
        $token = $this->findOneBy(['token' => $oauthToken]);
        if ($token) {
            $token = $token->toArray();
            $token['expires'] = $token['expires']->getTimestamp();
        }
        return $token;
    }

    public function setAccessToken($oauthToken, $clientIdentifier, $userEmail, $expires, $scope = null)
    {
        $client = $this->_em->getRepository('YourNamespace\Entity\OAuthClient')
                            ->findOneBy(['client_identifier' => $clientIdentifier]);
        $user = $this->_em->getRepository('YourNamespace\Entity\OAuthUser')
                            ->findOneBy(['email' => $userEmail]);
        $token = OAuthAccessToken::fromArray([
            'token'     => $oauthToken,
            'client'    => $client,
            'user'      => $user,
            'expires'   => (new \DateTime())->setTimestamp($expires),
            'scope'     => $scope,
        ]);
        $this->_em->persist($token);
        $this->_em->flush();
    }
     */

    /**
     * Look up the supplied oauth_token from storage.
     *
     * We need to retrieve access token data as we create and verify tokens.
     *
     * @param $oauth_token
     * oauth_token to be check with.
     *
     * @return
     * An associative array as below, and return NULL if the supplied oauth_token
     * is invalid:
     * - expires: Stored expiration in unix timestamp.
     * - client_id: (optional) Stored client identifier.
     * - user_id: (optional) Stored user identifier.
     * - scope: (optional) Stored scope values in space-separated string.
     * - id_token: (optional) Stored id_token (if "use_openid_connect" is true).
     *
     * @ingroup oauth2_section_7
     */
    public function getAccessToken($oauth_token)
    {
        // TODO: Implement getAccessToken() method.
    }

    /**
     * Store the supplied access token values to storage.
     *
     * We need to store access token data as we create and verify tokens.
     *
     * @param        $oauth_token    oauth_token to be stored.
     * @param        $client_id      client identifier to be stored.
     * @param        $user_id        user identifier to be stored.
     * @param int    $expires        expiration to be stored as a Unix timestamp.
     * @param string $scope          OPTIONAL Scopes to be stored in space-separated string.
     *
     * @ingroup oauth2_section_4
     */
    public function setAccessToken($oauth_token, $client_id, $user_id, $expires, $scope = null)
    {
        // TODO: Implement setAccessToken() method.
    }
}