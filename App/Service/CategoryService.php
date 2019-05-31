<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2018/12/29
 * Time: 14:47
 */

namespace App\Service;

use EasySwoole\Component\Singleton;
use EasySwoole\Mysqli\Config;
use EasySwoole\Mysqli\Mysqli;

class CategoryService
{
    use Singleton;

    private $db;
    private $table;

    public function __construct()
    {
        $conf = new Config(\EasySwoole\EasySwoole\Config::getInstance()->getConf("MYSQL"));
        $this->db = new Mysqli($conf);
        $this->table = "category";
    }

    public function getCategoryCount()
    {
        return $this->db->count($this->table);
    }

    public function getCategoryList($params)
    {
        $start = $params['page'] ?: 1;
        $pageSize = $params['pageSize'] ?: 10;
        $limit = [($start - 1) * $pageSize, $pageSize];

        $result = $this->db->get($this->table, $limit, 'id as categoryId, name as categoryName, create_time as createTime, update_time as updateTime,
                                        status, article_count as articleCount, can_del as canDel');

        return [
            'page'=> $start,
            'pageSize'=> $pageSize,
            'count'=> $this->getCategoryCount(),
            'list'=> $result
        ];

    }

    /**
     * @param $categoryName
     * @return array|string
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function addCategory($categoryName)
    {
        if (empty($categoryName)) return ['msg' => '请添加分类名称'];

        $info = $this->db
            ->where('name',  $categoryName, '=')
            ->getOne($this->table, '');

        if (!empty($info)) return ['msg' => '分类已经存在'];

        $id = UtilService::getInstance()->create_id();
        $insertData = [
            'name' => $categoryName,
            'status' => 0,
            'id' => $id,
            'can_del' => 0,
            'create_time' => time()
        ];

        $result = false;
        try {
            $result = $this->db
                ->insert($this->table, $insertData);

        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        if ($result) {
            return $id;
        }

        return 0;
    }

    /**
     * @param $categoryId
     * @return Mysqli|mixed|null|string
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function categoryInfo($categoryId)
    {
        if (empty($categoryId)) return [];

        $info = $this->db
            ->where('id', $categoryId, '=')
            ->getOne($this->table, 'id, name, article_count as articleCount');

        return $info;
    }

    /**
     * @param $categoryId
     * @return bool
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function hadCategory($categoryId)
    {
        $info = $this->db
            ->where('id', $categoryId, '=')
            ->getOne($this->table, 'id');

        if (!empty($info)) return true;
        return false;
    }

}