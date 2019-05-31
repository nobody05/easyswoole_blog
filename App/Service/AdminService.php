<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/1/2
 * Time: 20:33
 */

namespace App\Service;

use EasySwoole\Component\Singleton;
use EasySwoole\Mysqli\Config;
use EasySwoole\Mysqli\Mysqli;

class AdminService
{
    use Singleton;

    private $db;
    private $table;
    const WEEK = 7 * 24 * 3600;

    public function __construct()
    {
        $conf = new Config(\EasySwoole\EasySwoole\Config::getInstance()->getConf("MYSQL"));
        $this->db = new Mysqli($conf);
        $this->table = 'admin';
    }

    public function create()
    {






    }

    /**
     * @param $userName
     * @param $password
     * @return Mysqli|mixed|null|string
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function login($userName, $password)
    {
        if (empty($userName) || empty($password)) {
            return 'need username and password';
        }

        $userInfo = $this->db->where('username', $userName, '=')->getOne($this->table);
        if (empty($userInfo)) return '账号不存在';


        if(!UtilService::getInstance()->cb_passwordEqual($userInfo['password'], $userInfo['salt'], $password)){
            return '密码错误';
        }
        $time = time();

        $data = array(
            'last_login_time' => $time,
            'access_token' => UtilService::getInstance()->create_id(),
            'token_expires_in' => $time + self::WEEK
        );

        // 更新数据
        $this->db->where('username', $userName, '=')->update($this->table, $data);

        return $userInfo;
    }
}