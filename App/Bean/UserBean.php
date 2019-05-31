<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2018/12/28
 * Time: 20:28
 */

namespace App\Bean;

class UserBean extends \EasySwoole\Spl\SplBean {

    protected $id;
    protected $name;

    // 设置字段别名映射
    function setKeyMapping(): array
    {
        return [
            'id' => 'userId',
            'name' => 'userName'
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
}