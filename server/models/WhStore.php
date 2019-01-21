<?php
/**
 * Introduce ：
 * Created by Zyp丶.
 * Date: 2019/1/17
 */
namespace app\models;

use app\models\Models;
Class WhStore extends Models
{
    public static $_tb = 'wh_store';

    public static function className()
    {
        return self::$_tb;
    }

    public function getList($condition,$order,$limit,$offset)
    {
        $select = $this->_db->select()
            ->from(['a'=>self::$_tb],['*'])
            ->limit($limit,$offset);
        return $this->_db->fetchAll($select);
    }
}