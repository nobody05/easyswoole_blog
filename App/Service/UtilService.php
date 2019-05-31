<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/1/2
 * Time: 20:32
 */

namespace App\Service;

use EasySwoole\Component\Singleton;

class UtilService
{
    use Singleton;

    /**
     * 明文密码哈希
     *
     * @param  string $password         明文密码
     * @return array  password和salt
     */
    function cb_encrypt($password) {
        $salt = password_hash('mypassword', PASSWORD_BCRYPT, ['cost' => 10]);
        $password = md5($password . $salt);
        return [
            'password' => $password,
            'salt' => $salt,
        ];
    }

    /**
     * 密码比对
     *
     * @param  string $hash          哈希值
     * @param  string $salt          盐
     * @param  string $password      明文密码
     * @return bool   一致为真
     */
    function cb_passwordEqual($hash, $salt, $password) {
        $new_hash = md5($password . $salt);
        if (hash_equals($hash, $new_hash)) {
            return true;
        }
        return false;
    }


    /**
     * 创建63进制的唯一id
     * @method create_id
     * @return [type]    [description]
     */
    function create_id() {
        return $this->decTo63(base_convert(md5(uniqid()), 16, 10));
    }

    /**
     * 十进制的字符串转63进制
     * @method decTo63
     * @param  [type]  $str [description]
     * @return [type]       [description]
     */
    function decTo63($str)
    {
        $array63 = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            'a','b','c','d','e','f','g','h','i','j','k','l',
            'm','n','o','p','q','r','s','t','u','v','w','x','y','z',
            'A','B','C','D','E','F','G','H','I','J','K','L',
            'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

        $ayyayLen = count($array63);
        $result = '';
        $quotient = $str;
        $divisor = $str;
        $flag = true;
        while ($flag) {
            $len = strlen($divisor);
            $pos = 1;
            $quotient = 0;
            $div = substr($divisor, 0, 2);
            $remainder = $div[0];
            while ($pos < $len) {
                $div = $remainder == 0 ? $divisor[$pos] : $remainder.$divisor[$pos];
                $remainder = $div % $ayyayLen;
                $quotient = $quotient.floor($div / $ayyayLen);
                $pos++;
            }
            $quotient = $this->trim_left_zeros($quotient);
            $divisor = "$quotient";
            $result = $array63[$remainder].$result;
            if (strlen($divisor) <= 2) {
                if ($divisor < $ayyayLen - 1) {
                    $flag = false;
                }
            }
        }
        $result = $array63[$quotient].$result;
        $result = $this->trim_left_zeros($result);
        return $result;
    }

    function trim_left_zeros($str)
    {
        $str = ltrim($str, '0');
        if (empty($str)) {
            $str = '0';
        }
        return $str;
    }


    function get_param($params, $key, $default = '')
    {
        $param = $default;
        if(isset($params[$key])) {
            $param = $params[$key];
        }
        return $param;
    }

    /**
     * 从请求参数中获取page和pageSize的值（列表才有）
     */
    function get_page($params, $maxPageSize = 99, $defaultPageSize = 15) {
        $key = array(
            'page',
            'pageSize'
        );
        $page = $this->elements($key, $params, '');
        if(!$this->is_p_number($page['page'])) {
            $page['page'] = '0';
        }
        if(!$this->is_p_number($page['pageSize']) || intval($page['pageSize']) > $maxPageSize) {
            $page['pageSize'] = $defaultPageSize;
        }
        return $page;
    }

    function elements($items, array $array, $default = NULL)
    {
        $return = array();

        is_array($items) OR $items = array($items);

        foreach ($items as $item)
        {
            $return[$item] = array_key_exists($item, $array) ? $array[$item] : $default;
        }

        return $return;
    }

    /**
     * 判断是否是手机号
     */
    function is_phone($phone) {
        if (preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断是否是数字
     */
    function is_number($number) {
        if(preg_match("/^\d*$/", $number)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断正整数
     */
    function is_p_number($number) {
        if(preg_match("/^\+?[1-9][0-9]*$/", $number)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 驼峰转下划线
     * @param $name
     * @return string
     */
    public function camelCaseToUnderscore($name)
    {
        return strtolower(preg_replace('/([a-z0-9])([A-Z])/', '$1_$2', $name));
    }

    /**
     * 下划线转驼峰
     * @param $name
     * @return mixed
     */
    public function underscoreToCamelCase($name)
    {
        return preg_replace('/_([a-zA-Z0-9])/e', 'strtoupper("\\1")', $name);
    }
}