<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2018/12/29
 * Time: 09:58
 */

namespace App\Service;

use App\Bean\ArticleBean;
use App\Config\Logger;
use EasySwoole\Component\Singleton;
use EasySwoole\Mysqli\Config;
use EasySwoole\Mysqli\Mysqli;

class ArticleService
{
    use Singleton;

    private $db;
    private $table;

    const DELETED_YES_STATUS = 1;
    const DELETED_NO_STATUS = 2;
    const DELETED_PUBLISH_STATUS = 0;

    public function __construct()
    {
        $conf = new Config(\EasySwoole\EasySwoole\Config::getInstance()->getConf("MYSQL"));
        $this->db = new Mysqli($conf);
        $this->table = "article";
    }


    /**
     * @return mixed
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function getArticleCount()
    {
        return $this->db->count($this->table);
    }

    /**
     * @param $articleId
     * @return Mysqli|mixed|null|string
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function getArticle($articleId)
    {
        if (empty($articleId)) return [];

        $info = $this->db
            ->where('id', $articleId, '=')
            ->getOne($this->table, 'id, title, cover, sub_message as subMessage, content, html_content as htmlContent, pageview, status, category_id as categoryId, is_encrypt as isEncrypt,
                                        publish_time as publishTime, create_time as createTime, update_time as updateTime, delete_time as deleteTime');

        return $info;
    }

    /**
     * @param $articleId
     * @return array|Mysqli|mixed|null
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\OrderByFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function getNextArticle($articleId)
    {
        if (empty($articleId)) return [];

        $info = $this->db
            ->where('id', $articleId, '>')
            ->orderBy('id', 'ASC')
            ->getOne($this->table, 'id, title, cover, sub_message as subMessage, content, html_content as htmlContent, pageview, status, category_id as categoryId, is_encrypt as isEncrypt,
                                        publish_time as publishTime, create_time as createTime, update_time as updateTime, delete_time as deleteTime');

        return $info ?? [];
    }

    /**
     * @param $articleId
     * @return array|Mysqli|mixed|null
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\OrderByFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function getPreArticle($articleId)
    {
        if (empty($articleId)) return [];

        $info = $this->db
            ->where('id', $articleId, '<')
            ->orderBy('id', 'DESC')
            ->getOne($this->table, 'id, title, cover, sub_message as subMessage, content, html_content as htmlContent, pageview, status, category_id as categoryId, is_encrypt as isEncrypt,
                                        publish_time as publishTime, create_time as createTime, update_time as updateTime, delete_time as deleteTime');

        return $info ?? [];
    }

    /**
     * @param $articleId
     * @return array|string
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\JoinFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function getArticleAdmin($articleId)
    {
        $tagService = new TagService();
        $categoryService = new CategoryService();
        if (empty($articleId)) return '请选择文章ID';

        $articleInfo = $this->getArticle($articleId);

//        Logger::getInstance()->log($articleId."getArticleAdmin", [$articleInfo]);
        $categoryInfo = [];
        if (!empty($articleInfo['categoryId'])) {
            $categoryInfo = $categoryService->categoryInfo($articleInfo['categoryId']);
        }

        $tagInfo = $tagService->getTagByArticleId($articleId);

        return [
            'article' => $articleInfo,
            'category' => $categoryInfo ?? [],
            'tags' => $tagInfo ?? []
        ];
    }

    /**
     * @param $articleId
     * @return bool
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function hadArticle($articleId)
    {
        $info = $this->db
            ->where('id', $articleId, '=')
            ->getOne($this->table, 'id');

        if (!empty($info)) return true;
        return false;


    }

    /**
     * @param $by
     * @param $status
     * @param $categoryId
     * @param $tagId
     * @param $pageOpt
     * @throws
     * @return array|string
     */
    public function getArticleList($by, $status, $categoryId, $tagId, $pageOpt, $ids=[])
    {
        $tagService = new TagService();
        $categoryService = new CategoryService();
        try {

            switch ($by) {
                case 'category':
                    if ($categoryId == '') {
                        return '分类id不能为空';
                    }
                    $had = $categoryService->hadCategory($categoryId);
                    if (!$had) {
                        return '不存在该分类';
                    }
                    $result = $this->getArticleListByCategoryId($categoryId, $pageOpt['page'], $pageOpt['pageSize']);
                    break;
                case 'tag':
                    if ($tagId == '') {
                        return '标签id不能为空';
                    }
                    $had = $tagService->hadTag($tagId);
                    if (!$had) {
                        return '不存在该标签';
                    }
                    $result = $this->getArticleListByTagId($tagId, $pageOpt['page'], $pageOpt['pageSize']);
                    break;
                case 'id':
                    $result = $this->getArticleListByIds($ids, $pageOpt['page'], $pageOpt['pageSize']);
                    break;
                default:
                    if ($status != '0' && $status != '1' && $status != '2') {
                        $result = $this->getArticleListByStatus(1, $pageOpt['page'], $pageOpt['pageSize']);
                    } else {
                        $result = $this->getArticleListByStatus($status, $pageOpt['page'], $pageOpt['pageSize']);
                    }

                    break;
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());

            return [];
        }

        return $result;
    }

    /**
     * @param $data
     * @return bool|int|mixed
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function updateArticle($data)
    {
        $dbData = [];
        foreach ($data as $k=>$v) {
            $dbData[UtilService::getInstance()->camelCaseToUnderscore($k)] = $v;
        }

//        Logger::getInstance()->log('insertArticle', [$data, $dbData]);

        if (empty($data['id'])) {
            $dbData['id'] = UtilService::getInstance()->create_id();
            $dbData['create_time'] = time();
            $dbData['update_time'] = time();
            $dbData['status'] = 2;

            $result =  $this->db->insert($this->table, $dbData);

//            Logger::getInstance()->log('insertArticle', [$dbData]);
        } else {
            $articleInfo  = $this->db->where('id', $data['id'], '=')
                ->getOne($this->table);
            if (empty($articleInfo)) return '文章不存在';
            $result =  $this->db->where('aid', $articleInfo['aid'], '=')->update($this->table, $dbData);

//            Logger::getInstance()->log('updateArticle', [$dbData]);
        }

        if ($result == true) return 'success';
        return 'fail';
    }

    /**
     * @param $data
     * @return string
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function publishArticle($data)
    {
//        $dbData = (new ArticleBean($data, true))->toArray([
//            'id',
//            'title',
//            'category_id',
//            'content',
//            'html_content',
//            'cover',
//            'sub_message',
////            'pageview',
//            'is_encrypt'
//        ]);

        $dbData = [];
        foreach ($data as $k=>$v) {
            $dbData[UtilService::getInstance()->camelCaseToUnderscore($k)] = $v;
        }

        if (empty($dbData['id'])) {
            return '文章不存在';
        }
        $dbData['status'] = 0;
        $dbData['publish_time'] = time();

        $articleInfo  = $this->db->where('id', $data['id'], '=')
            ->getOne($this->table);
        if (empty($articleInfo)) return '文章不存在';

        $result =  $this->db->where('aid', $articleInfo['aid'], '=')->update($this->table, $dbData);

//        Logger::getInstance()->log("publishArticle", [$dbData, $articleInfo]);

        if ($result == true) {
            // 写入es
            $searchService = new SearchService();
            $searchService->setIndex('articles');
            $searchService->setType('article');

            $searchService->add($articleInfo['aid'], ['id' => $articleInfo['aid'], 'title' => $dbData['title'], 'content' => $dbData['content']]);

            return "success";
        }

        return "fail";
    }


    /**
     * @param $categoryId
     * @param $page
     * @param $pageSize
     * @return mixed
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\JoinFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function getArticleListByCategoryId($categoryId, $page, $pageSize)
    {
        $start = $page ?: 1;
        $pageSize = $pageSize ?: 10;
        $limit = [($start - 1) * $pageSize, $pageSize];

        $articleList = $this->db
            ->join('article', 'article.category_id = category.id', 'LEFT')
            ->where('category.id', $categoryId, '=')
            ->get('category', $limit, 'article.id as id, article.category_id as categoryId, title, cover, pageview, article.status as status, is_encrypt as isEncrypt, category.name as categoryName,
                                    article.create_time as createTime, article.update_time as updateTime,
                                    article.publish_time as publishTime, article.delete_time as deleteTime');

        $data = array(
            'page'=> $page,
            'pageSize'=> $pageSize,
            'count'=> $this->getArticleCount(),
            'list'=> $articleList
        );

        return $data;
    }

    /**
     * @param $tagId
     * @param $page
     * @param $pageSize
     * @return mixed
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\JoinFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function getArticleListByTagId($tagId, $page, $pageSize)
    {
        $start = $page ?: 1;
        $pageSize = $pageSize ?: 10;
        $limit = [($start - 1) * $pageSize, $pageSize];

        $articleList = $this->db
            ->join('article', 'article.category_id = category.id', 'LEFT')
            ->join('category', 'article.id = article_tag_mapper.article_id', 'LEFT')
            ->where('article_tag_mapper.tag_id', $tagId, '=')
            ->get('article_tag_mapper', $limit, 'article.id as id, article.category_id as categoryId, title, cover, pageview, article.status as status, is_encrypt as isEncrypt, category.name as categoryName,
                                        article.create_time as createTime, article.update_time as updateTime, 
                                        article.publish_time as publishTime, article.delete_time as deleteTime');

        $data = array(
            'page'=> $page,
            'pageSize'=> $pageSize,
            'count'=> $this->getArticleCount(),
            'list'=> $articleList
        );

        return $data;
    }

    /**
     * @param $status
     * @param $page
     * @param $pageSize
     * @return mixed
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\JoinFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function getArticleListByStatus($status, $page, $pageSize)
    {
        $start = $page ?: 1;
        $pageSize = $pageSize ?: 10;
        $limit = [($start - 1) * $pageSize, $pageSize];

        $db = $this->db
            ->join('category', 'article.category_id = category.id', 'LEFT')
            ->where('article.status', $status, '=')
            ->orderBy('article.update_time', 'desc');
        $articleList = $db
            ->get($this->table, $limit, 'article.id as id, title, cover, pageview, article.status as status, is_encrypt as isEncrypt, category.name as categoryName, category_id as categoryId,
                                        article.create_time as createTime, article.update_time as updateTime, 
                                        article.publish_time as publishTime, article.delete_time as deleteTime');

        $data = array(
            'page'=> $page,
            'pageSize'=> $pageSize,
            'count'=> $this->getArticleCount(),
            'list'=> $articleList
        );

        return $data;
    }

    public function getArticleListByIds($ids, $page, $pageSize)
    {
        $start = $page ?: 1;
        $pageSize = $pageSize ?: 10;
        $limit = [($start - 1) * $pageSize, $pageSize];

        $db = $this->db
            ->join('category', 'article.category_id = category.id', 'LEFT')
            ->whereIn('article.aid', $ids)
            ->where('article.status', self::DELETED_PUBLISH_STATUS)
            ->orderBy('article.update_time', 'desc');



        $articleList = $db
            ->get($this->table, $limit, 'article.id as id, title, cover, pageview, article.status as status, is_encrypt as isEncrypt, category.name as categoryName, category_id as categoryId,
                                        article.create_time as createTime, article.update_time as updateTime, 
                                        article.publish_time as publishTime, article.delete_time as deleteTime');

        $count = $this->db
            ->join('category', 'article.category_id = category.id', 'LEFT')
            ->whereIn('article.aid', $ids)
            ->where('article.status', self::DELETED_PUBLISH_STATUS)->count('article');
        $data = array(
            'page'=> $page,
            'pageSize'=> $pageSize,
            'count'=> $count,
            'list'=> $articleList
        );

        return $data;

    }

    /**
     * @param $articleId
     * @return mixed
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     */
    public function incrementPageView($articleId)
    {
        return $this->db
            ->where('id', $articleId)
            ->setInc($this->table, 'pageview', 1);
    }

    /**
     * 删除
     * @param $articleId
     * @return mixed
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function delete($articleId)
    {
        $info = $this->getArticle($articleId);
        if (empty($info)) return "文章不存在";

        $result = $this->db->where('id', $articleId)
            ->update($this->table, ['status' => self::DELETED_YES_STATUS]);
        if ($result) {
            return "success";
        }

        return "删除失败";
    }
}