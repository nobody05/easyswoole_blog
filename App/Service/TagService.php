<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/2/16
 * Time: 16:08
 */

namespace App\Service;

use EasySwoole\Component\Singleton;
use EasySwoole\Mysqli\Config;
use EasySwoole\Mysqli\Mysqli;

class TagService
{
    use Singleton;

    private $db;
    private $table;

    public function __construct()
    {
        $conf = new Config(\EasySwoole\EasySwoole\Config::getInstance()->getConf("MYSQL"));
        $this->db = new Mysqli($conf);
        $this->table = "tag";
    }

    /**
     * @param $tagId
     * @return bool
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function hadTag($tagId)
    {
        $tagInfo = $this->db
            ->where('id', $tagId, '=')
            ->getOne($this->table, 'id');
        if (empty($tagInfo)) return false;

        return true;
    }

    /**
     * @param $tagId
     * @return Mysqli|mixed|null|string
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function getTag($tagId)
    {
        if (empty($tagId)) return '请选择标签ID';

        $info = $this->db
            ->where('id', $tagId, '=')
            ->getOne($this->table, 'name');

        return $info;
    }

    /**
     * @param $articleId
     * @return Mysqli|mixed|null
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\JoinFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function getTagByArticleId($articleId)
    {
        $result = $this->db->where('article_tag_mapper.article_id', $articleId, '=')
            ->join('tag', 'tag.id = article_tag_mapper.tag_id', 'LEFT')
            ->getOne('article_tag_mapper', '*');

        return $result ?? [];
    }

    /**
     * @param $page
     * @param $perPage
     * @return array|Mysqli|mixed
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function list($page = '', $perPage = '')
    {
        $limit = null;
        if (!empty($page) && !empty($perPage)) {
            $limit = [($page - 1) * $perPage, $perPage];
        }

        $list = $this->db
            ->where('status', 0)
            ->get($this->table, $limit, 'id as tagId, name as tagName, create_time as createTime, update_time as updateTime,
                                        status, article_count as articleCount');

        return $list ?? [];
    }

    /**
     * @return mixed
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function count()
    {
        return $this->db->where('status', 0)->count($this->table);
    }

    /**
     * @param $tagName
     * @return string
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function add($tagName)
    {
        $id = UtilService::getInstance()->create_id();
        $tag = array(
            'id'=> $id,
            'name'=> $tagName,
            'create_time'=> time()
        );
        $result = $this->db->insert($this->table, $tag);
        if ($result) return "success";

        return "fail";
    }
}