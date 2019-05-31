<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/1/2
 * Time: 11:12
 */

namespace App\Bean;

use EasySwoole\Spl\SplBean;

class ArticleBean extends SplBean
{
    protected $id;
    protected $title;
    protected $categoryId;
    protected $createTime;
    protected $deleteTime;
    protected $updateTime;
    protected $publishTime;
    protected $status;
    protected $content;
    protected $htmlContent;
    protected $cover;
    protected $subMessage;
    protected $pageview;
    protected $isEncrypt;

    public function setKeyMapping(): array
    {
        return [
//            'title' => 'title',
//            'id' => 'id',
            'categoryId' => 'category_id',
            'createTime' => 'create_time',
            'deleteTime' => 'delete_time',
            'updateTime' => 'update_time',
            'htmlContent' => 'html_content',
            'subMessage' => 'sub_message',
            'isEncrypt' => 'is_encrypt'
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryId
     */
    public function setCategoryId($categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return mixed
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @param mixed $createTime
     */
    public function setCreateTime($createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * @return mixed
     */
    public function getDeleteTime()
    {
        return $this->deleteTime;
    }

    /**
     * @param mixed $deleteTime
     */
    public function setDeleteTime($deleteTime): void
    {
        $this->deleteTime = $deleteTime;
    }

    /**
     * @return mixed
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * @param mixed $updateTime
     */
    public function setUpdateTime($updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    /**
     * @return mixed
     */
    public function getPublishTime()
    {
        return $this->publishTime;
    }

    /**
     * @param mixed $publishTime
     */
    public function setPublishTime($publishTime): void
    {
        $this->publishTime = $publishTime;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getHtmlContent()
    {
        return $this->htmlContent;
    }

    /**
     * @param mixed $htmlContent
     */
    public function setHtmlContent($htmlContent): void
    {
        $this->htmlContent = $htmlContent;
    }

    /**
     * @return mixed
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @param mixed $cover
     */
    public function setCover($cover): void
    {
        $this->cover = $cover;
    }

    /**
     * @return mixed
     */
    public function getSubMessage()
    {
        return $this->subMessage;
    }

    /**
     * @param mixed $subMessage
     */
    public function setSubMessage($subMessage): void
    {
        $this->subMessage = $subMessage;
    }

    /**
     * @return mixed
     */
    public function getPageview()
    {
        return $this->pageview;
    }

    /**
     * @param mixed $pageview
     */
    public function setPageview($pageview): void
    {
        $this->pageview = $pageview;
    }

    /**
     * @return mixed
     */
    public function getisEncrypt()
    {
        return $this->isEncrypt;
    }

    /**
     * @param mixed $isEncrypt
     */
    public function setIsEncrypt($isEncrypt): void
    {
        $this->isEncrypt = $isEncrypt;
    }



}