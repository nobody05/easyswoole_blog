<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/3/24
 * Time: 17:00
 */

namespace App\Bean;

use EasySwoole\Spl\SplBean;

class CommentBean extends SplBean
{
    protected $articleId;
    protected $parentId;
    protected $replyId;
    protected $name;
    protected $email;
    protected $content;
    protected $sourceContent;
    protected $isAuthor;


    public function setKeyMapping(): array
    {
        return [
            'articleId' => 'article_id',
            'parentId' => 'parent_id',
            'replyId' => 'reply_id',
            'sourceContent' => 'source_content',
            'isAuthor' => 'is_author'
        ];
    }

    /**
     * @return mixed
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @param mixed $articleId
     */
    public function setArticleId($articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param mixed $parentId
     */
    public function setParentId($parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * @return mixed
     */
    public function getReplyId()
    {
        return $this->replyId;
    }

    /**
     * @param mixed $replyId
     */
    public function setReplyId($replyId): void
    {
        $this->replyId = $replyId;
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

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
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
    public function getSourceContent()
    {
        return $this->sourceContent;
    }

    /**
     * @param mixed $sourceContent
     */
    public function setSourceContent($sourceContent): void
    {
        $this->sourceContent = $sourceContent;
    }

    /**
     * @return mixed
     */
    public function getisAuthor()
    {
        return $this->isAuthor;
    }

    /**
     * @param mixed $isAuthor
     */
    public function setIsAuthor($isAuthor): void
    {
        $this->isAuthor = $isAuthor;
    }




}