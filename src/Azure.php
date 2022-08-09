<?php

namespace CloudMonitor\Azure;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Laravel\Socialite\Two\User;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;

class Azure extends AbstractProvider implements ProviderInterface
{
    const IDENTIFIER = 'AZURE_OAUTH';
    protected $scopes = ['https://graph.microsoft.com/.default'];
    protected $scopeSeparator = ' ';

    /**
     * Create a new provider instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $secret
     * @param  string  $redirectUrl
     * @param  array  $guzzle
     */
    public function __construct(Request $request, $secret, $redirectUrl, $guzzle = [])
    {
        parent::__construct($request, $secret, $redirectUrl, $guzzle);
    }

    /**
     * Authorization URL.
     * 
     * @param string $state
     * @return string
     */
    protected function getAuthUrl(string $state): string
    {
        return $this->buildAuthUrlFromBase('https://login.microsoftonline.com/'. config('azure-ad.tenant') .'/oauth2/v2.0/authorize', $state);
    }

    /**
     * 
     * 
     * @return string
     */
    protected function getTokenUrl(): string
    {
        return 'https://login.microsoftonline.com/'. config('azure-ad.tenant') .'/oauth2/v2.0/token';
    }

    /**
     * 
     * @return array
     */
    protected function getTokenFields($code): array
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
            'approval_prompt' => 'auto',
        ]);
    }

    /**
     * 
     * @param string $token
     * @return 
     */
    protected function getUserByToken(string $token)
    {
        $response = $this->getHttpClient()->get('https://graph.microsoft.com/v1.0/me/', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}