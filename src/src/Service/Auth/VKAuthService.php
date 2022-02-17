<?php

namespace App\Service\Auth;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class VKAuthService
{
    const CLIENT_ID = "8081921";

    const CLIENT_SECRET = "HZF9pIIX2F8VrnrfEWt0";

    const REDIRECT_URI_CONFIRM = "http://127.10.10.10/vk/confirm";
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }


    public function getAuthLink()
    {
        $url = 'http://oauth.vk.com/authorize?'; // Ссылка для авторизации на стороне ВК

        $params = [ 'client_id' => self::CLIENT_ID, 'redirect_uri'  => self::REDIRECT_URI_CONFIRM, 'response_type' => 'code'];
        return $url . urldecode(http_build_query($params));
    }

    public function auth(string $code){
        $result = true;
        $params = [
            'client_id' => self::CLIENT_ID,
            'client_secret' => self::CLIENT_SECRET,
            'code' => $code,
            'redirect_uri' => self::REDIRECT_URI_CONFIRM
        ];
        try{
            $response = $this->httpClient->request('GET', 'https://oauth.vk.com/access_token', ['query' => $params]);
            $data = json_decode($response->getContent(false), true);
        }catch(\Throwable $exception){
            dd($exception->getMessage());
        }
        if (isset($data['access_token'])) {
            $params = [
                'uids' => $data['user_id'],
                'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
                'access_token' => $data['access_token'],
                'v' => '5.101'];
            $userInfoResponse = $this->httpClient->request('GET', 'https://api.vk.com/method/users.get', ['query' => $params]);

            $result =  $userInfoResponse->getContent(false);

        }
        return $result;
    }
}
