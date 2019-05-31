<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2018/12/29
 * Time: 14:55
 */

namespace App\Service;

use EasySwoole\Component\Singleton;
use EasySwoole\Mysqli\Config;
use EasySwoole\Mysqli\Mysqli;

class ArticelTagService
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

    public function getArticleCount()
    {
        return $this->db->count($this->table);
    }


}