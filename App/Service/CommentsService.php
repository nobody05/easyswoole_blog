<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/3/24
 * Time: 16:31
 */

namespace App\Service;


use EasySwoole\Component\Singleton;
use EasySwoole\Mysqli\Config;
use EasySwoole\Mysqli\Mysqli;

class CommentsService
{
    use Singleton;

    private $db;
    private $table;

    public function __construct()
    {
        $conf = new Config(\EasySwoole\EasySwoole\Config::getInstance()->getConf("MYSQL"));
        $this->db = new Mysqli($conf);
        $this->table = "comments";
    }

    /**
     * @param string $page
     * @param string $pageSize
     * @return Mysqli|mixed
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\JoinFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function allList($page = '', $pageSize ='')
    {
        $limit = null;
        if (!empty($pageSize) && !empty($page)) {
            $limit = [($page - 1) * $pageSize, $pageSize];
        }

        $list = $this->db
            ->where('comments.status', 0)
            ->join("article", "article.id = comments.article_id", "LEFT")
            ->get($this->table, $limit, 'comments.id as id, parent_id as parentId, article_id as articleId, reply_id as replyId, name, comments.content as content,
                                        comments.create_time as createTime, is_author as isAuthor, article.title as articleTitle, comments.status as status');

        return $list;

    }

    /**
     * 评论列表
     * @param $articleId
     * @return Mysqli|mixed
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\OrderByFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function list($articleId)
    {
        $list = $this->db
            ->where('article_id', $articleId)
            ->where('parent_id', 0)
            ->where('status', 0)
            ->orderBy('create_time')
            ->get($this->table, null, 'id, parent_id as parentId, article_id as articleId, reply_id as replyId, name, content,
                                        create_time as createTime, is_author as isAuthor');

        foreach ($list as $k=>$info) {
            $child = $this->db
                ->where('article_id', $articleId)
                ->where('parent_id', $info['id'])
                ->where('status', 0)
                ->orderBy('create_time', 'ASC')
                ->get($this->table, null, 'id, parent_id as parentId, article_id as articleId, reply_id as replyId, name, content,
                                            create_time as createTime, is_author as isAuthor');


//            if (!empty($child)) {
                $list[$k]['children'] = $child;
//            }
        }


        return $list;
    }

    /**
     * 添加评论
     * @param $data
     * @return string
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function add($data)
    {
        $comments = array(
            'name'=> $data['name'] ?? '',
            'email'=> $data['email'] ?? '',
            'content'=> $data['content'] ?? '',
            'source_content'=> $data['sourceContent'] ?? '',
            'create_time'=> time(),
            'article_id'=> $data['articleId'] ?? 0,
            'reply_id'=> $data['replyId'] ?? 0,
            'parent_id'=> $data['parentId'] ?? 0
        );


        print_r($comments);

        $result = $this->db->insert($this->table, $comments);

        if ($result) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    /**
     * 评论总数
     * @param $articleId
     * @return mixed
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function count($articleId)
    {
        return $this->db
            ->where('article_id', $articleId)
            ->where('status', 0)
            ->count($this->table);

    }

    /**
     * @return mixed
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function allCount()
    {
        return $this->db
            ->where('status', 0)
            ->count($this->table);

    }

}