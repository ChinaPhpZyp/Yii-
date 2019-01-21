<?php
/**
 * Introduce ：
 * Created by Zyp.
 * Date: 2018/9/26
 */
namespace app\models;

use yii;
use SqlTranslator\SqlTranslator;
use SqlTranslator\Database;
use SqlTranslator\DatabaseException;
use yii\base\Exception;

class    Models extends \yii\db\ActiveRecord
{
    public static $check          = false;
    protected     $_db            = '';
    protected     $_db_translator = '';
    protected     $_className     = '';

    public function __construct()
    {
        $this->_className = get_class($this);
        $dsn              = Yii::$app->db->dsn;
        $username         = Yii::$app->db->username;
        $password         = Yii::$app->db->password;
        preg_match('/(\w+):host=([\d+\.]+)(:(\d+))?;dbname=(\w+)/', $dsn, $match);
        $this->_db            = (new Database())->config(
            'mysql://' . $username . ':' . $password . '@' . $match[2] . ':' . $match[4] . '/' . $match[5]
        )
                                                ->pick('pdo');
        $this->_db_translator = new SqlTranslator();

        //参数验证
        if ($this::$check) {
            $this->setScenario(Yii::$app->controller->action->id);
            $this->load(Yii::$app->controller->params, '');
            if (!$this->validate()) {
                return Yii::$app->controller->echoJson(1, $this->errors);
            }
            self::$check = false;
        }
    }

    public function database()
    {
        return $this->_db;
    }

    public function translator()
    {
        return $this->_db_translator;
    }

    public function scenarios()
    {
    }

    public function attributeLabels()
    {
    }

    public function rules()
    {
    }

    //通用方法
    public function add($params)
    {
        try {
            $classname = $this->_className;
            $insert    = $this->_db_translator->insert->into($classname::tableName(), array_keys($params))
                                                      ->values(array_values($params));
            if ($this->_db->query($insert)) {
                /*同步索引*/
                $this->getSyncEx($classname::tableName());

                return $this->_db->lastInsertId();
            }
        } catch (\Exception $e) {
            throw new CustomErrorHttpException($e->getMessage(), 201);
        }
    }

    /**
     * 更新
     * @param $params
     * @param $id
     * @return bool
     * @throws CustomErrorHttpException
     */
    public function edit($params, $id)
    {
        $classname = $this->_className;
        try {
            $update = $this->_db_translator->update->set($classname::tableName(), $params)
                                                   ->where('id=?', $id);
            $this->_db->query($update);

            /*同步索引*/
            $this->getSyncEx($classname::tableName());

            return true;
        } catch (\Exception $e) {
            throw new CustomErrorHttpException($e->getMessage(), 201);
        }
    }

    public function remove($id)
    {
        try {
            $classname = $this->_className;
            $delete    = $this->_db_translator->delete->from($classname::tableName())
                                                      ->where('id=?', $id);

            $ret = $this->_db->query($delete);

            if ($ret) {
                /*同步索引*/
                //$this->getSyncEx($classname::tableName());
            }

            return $ret;

        } catch (\Exception $e) {
            throw new CustomErrorHttpException($e->getMessage(), 201);
        }
    }

    public function get($id)
    {
        $classname = $this->_className;
        $select    = $this->_db_translator->select->from(['a' => $classname::tableName()], ['*'])
                                                  ->where('a.id=?', $id);

        return $this->_db->fetch($select);
    }

    public function count($condition)
    {
        $classname = $this->_className;
        $select    = $this->_db_translator->select->from(['a' => $classname::tableName()], ['count(1)']);
        $condition && $this->_condition($condition, $select);

        return $this->_db->fetchOne($select);
    }

    public function gets($condition, $order, $limit = 20, $offset = 0)
    {
        $classname = $this->_className;
        $select    = $this->_db_translator->select->from(['a' => $classname::tableName()], '*')
                                                  ->limit($limit, $offset);
        $this->_condition($condition, $select);
        $this->_order($order, $select);
        $result = $this->_db->fetchAll($select);

        return $result;
    }

    protected function _condition($types, $select)
    {
        if ($select && is_object($select)) {
            foreach ($types as $type => $value) {
                switch ($type) {
                    case 'id' :
                        $value && $select->where(
                            $this->translator()
                                 ->quoteId('a.' . $type, $value, false)
                        );
                        break;
                    case 'kpireport_id' :
                    case 'model_name' :
                        $value && $select->where(
                            $this->translator()
                                 ->quoteId('a.' . $type, $value, false)
                        );
                        break;
                    case 'parent_id' :
                        $value && $select->where(
                            $this->translator()
                                 ->quoteId('a.' . $type, $value, false)
                        );
                        break;
                }
            }
        }
    }

    protected function _order($order, $select)
    {
        if ($select && is_object($select)) {
            foreach ($order as $type => $value) {
                switch ($type) {
                    case 'id' :
                    case 'order' :
                    case 'create_time' :
                        $select->order('a.' . $type, $value);
                        break;
                    case 'dateAdded' :
                        $select->order('a.' . $type, $value);
                        break;
                }
            }
        }
    }

}
