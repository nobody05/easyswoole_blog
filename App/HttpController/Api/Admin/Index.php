<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/1/5
 * Time: 17:46
 */

namespace App\HttpController\Api\Admin;

use App\HttpController\Api\BaseController;
use App\Service\AdminService;

class Index extends BaseController
{
    /**
     * @return \EasySwoole\Mysqli\Mysqli|mixed|null|string
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function login()
    {
        $userName = $this->request()->getRequestParam("username");
        $password = $this->request()->getRequestParam("password");


        $result = AdminService::getInstance()->login($userName, $password);

        if (is_string($result)) {
            return $result;
        }

        $this->writeJson(200, [
            'userId' => $result['user_id'],
            'userName' => $result['username'],
            'lastLoginTime' => $result['last_login_time'],
            'token' => array(
                'accessToken' => $result['access_token'],
                'tokenExpiresIn' => $result['token_expires_in'],
                'exp' => AdminService::WEEK
            )
        ], 'ok');
    }

}