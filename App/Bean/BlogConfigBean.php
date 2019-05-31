<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2018/12/28
 * Time: 19:28
 */

namespace App\Bean;

use EasySwoole\Spl\SplBean;

class BlogConfigBean extends SplBean
{
    protected $id;
    protected $blog_name;
    protected $avatar;
    protected $sign;
    protected $wxpay_qrcode;
    protected $alipay_qrcode;
    protected $github;
    protected $view_password;
    protected $salt;

    public function __construct(array $data = null, bool $autoCreateProperty = false)
    {
        parent::__construct($data, $autoCreateProperty);
    }

    public function setKeyMapping(): array
    {
//        array_flip(
        return [
            'blog_name' => 'blogName',
            'wxpay_qrcode' => 'wxpayQrCode',
            'alipay_qrcode' => 'alipayQrCode',
            'view_password' => 'viewPassword'
        ];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getBlogName()
    {
        return $this->blog_name;
    }

    /**
     * @param mixed $blog_name
     */
    public function setBlogName($blog_name): void
    {
        $this->blog_name = $blog_name;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * @param mixed $sign
     */
    public function setSign($sign): void
    {
        $this->sign = $sign;
    }

    /**
     * @return mixed
     */
    public function getWxpayQrcode()
    {
        return $this->wxpay_qrcode;
    }

    /**
     * @param mixed $wxpay_qrcode
     */
    public function setWxpayQrcode($wxpay_qrcode): void
    {
        $this->wxpay_qrcode = $wxpay_qrcode;
    }

    /**
     * @return mixed
     */
    public function getAlipayQrcode()
    {
        return $this->alipay_qrcode;
    }

    /**
     * @param mixed $alipay_qrcode
     */
    public function setAlipayQrcode($alipay_qrcode): void
    {
        $this->alipay_qrcode = $alipay_qrcode;
    }

    /**
     * @return mixed
     */
    public function getGithub()
    {
        return $this->github;
    }

    /**
     * @param mixed $github
     */
    public function setGithub($github): void
    {
        $this->github = $github;
    }

    /**
     * @return mixed
     */
    public function getViewPassword()
    {
        return $this->view_password;
    }

    /**
     * @param mixed $view_password
     */
    public function setViewPassword($view_password): void
    {
        $this->view_password = $view_password;
    }

    /**
     * @return mixed
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param mixed $salt
     */
    public function setSalt($salt): void
    {
        $this->salt = $salt;
    }




}