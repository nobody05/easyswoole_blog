<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2018/12/28
 * Time: 19:38
 */

namespace App\Service;

use App\Bean\BlogConfigBean;
use EasySwoole\Component\Singleton;
use EasySwoole\Mysqli\Config;
use EasySwoole\Mysqli\Mysqli;

class BlogService
{
    use Singleton;

    private $db;
    private $table;

    public function __construct()
    {
        $conf = new Config(\EasySwoole\EasySwoole\Config::getInstance()->getConf("MYSQL"));
        $this->db = new Mysqli($conf);
        $this->table = "blog_config";
    }

    /**
     * @return Mysqli|mixed|null
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function getBlogConfig()
    {
        $config = $this->db
            ->getOne('blog_config', 'blog_name as blogName, avatar, sign, wxpay_qrcode as wxpayQrcode,
                                    alipay_qrcode as alipayQrcode, github, salt');

        if ($config && $config['salt']) {
            $config['hadOldPassword'] = true;
        } else {
            $config['hadOldPassword'] = false;
        }

        return $config;
    }

    /**
     * @param $params
     * @return string
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function modify($params)
    {
        $config = $this->db
            ->getOne($this->table);

        if ($config) {
            if (isset($params['settingPassword']) && $params['settingPassword'] == 'true') {
                // 如果有秘钥存在，说明已经有设置过密码了，要对密码进行比较
                if ($config['salt']) {

                    if (!UtilService::getInstance()->cb_passwordEqual($config['view_password'], $config['salt'], $params['oldPassword'])) {
                        return '原密码错误';
                    }
                }
                // 加密新密码
                $encrypt = UtilService::getInstance()->cb_encrypt($params['viewPassword']);
                $config['view_password'] = $encrypt['password'];
                $config['salt'] = $encrypt['salt'];
            }
            $config['blog_name'] = $params['blogName'];
            $config['avatar'] = $params['avatar'];
            $config['sign'] = $params['sign'];
            $config['wxpay_qrcode'] = $params['wxpayQrcode'];
            $config['alipay_qrcode'] = $params['alipayQrcode'];
            $config['github'] = $params['github'];

            $result = $this->db->where('id', $config['id'])->update($this->table, $config);
        } else {
            $config = array();
            if ($params['settingPassword'] == 'true') {
                // 加密新密码
                $encrypt = UtilService::getInstance()->cb_encrypt($params['viewPassword']);
                $config['view_password'] = $encrypt['password'];
                $config['salt'] = $encrypt['salt'];
            }
            $config['blog_name'] = $params['blogName'];
            $config['avatar'] = $params['avatar'];
            $config['sign'] = $params['sign'];
            $config['wxpay_qrcode'] = $params['wxpayQrcode'];
            $config['alipay_qrcode'] = $params['alipayQrcode'];
            $config['github'] = $params['github'];

            $result = $this->db->insert($this->table, $config);
        }

        if ($result) {
            return "success";
        }
        return "fail";
    }

    /**
     * @param string $type
     * @return array|Mysqli|mixed|null
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function getAboutMe($type = 'about')
    {
        $info = $this->db
            ->where("type", $type)
            ->getOne('pages', "type, md, html, id");

        return $info ?? [];
    }

    /**
     * @param $data
     * @return string
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function modifyAboutMe($data)
    {
        $info = $this->getAboutMe($data['type']);
        if (!empty($info)) {
            $result = $this->db
                ->where('id', $info['id'])
                ->update('pages', $data);
        } else {
            $result = $this->db
                ->insert("pages", $data);
        }

        if ($result) {
            return "success";
        }

        return "fail";
    }

}