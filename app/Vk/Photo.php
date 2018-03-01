<?php


namespace App\Vk;


class Photo
{

    private function request($ownerId, $count = 100, $offset = 0)
    {
        $auth = new Auth('1b7184dee361d2f65a0dd5c938e2079c4a362497f6cd5ac819c918f58ca6dbfc62901e800925a1d888c9b');
        $response = file_get_contents("https://api.vk.com/method/photos.getAll?owner_id=$ownerId&count=$count&offset=$offset&fields=photo_max_orig&access_token=$auth->authToken");
        return json_decode($response, true);
    }

    public function getCountByUser($ownerId)
    {
        $response = $this->request($ownerId,1);
        return (int)$response['response'][0];
    }

    public function getByUser($ownerId, $count = 200, $offset = 0)
    {
        $data = $this->request($ownerId, $count, $offset);
        return $data;
    }
}