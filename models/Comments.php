<?php

namespace rangeweb\comments\models;

use common\components\DateHelper;
use common\models\Settings;
use common\modules\users\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%comments}}".
 *
 * @property integer $id
 * @property string $object
 * @property integer $object_id
 * @property string $text
 * @property string $note
 * @property integer $user_id
 * @property string $date_create
 * @property string $date_update
 * @property integer $status
 * @property integer $parent_id
 * @property integer $show_main
 */
class Comments extends \yii\db\ActiveRecord
{
    const STATUS_PUBLIC = 1;
    const STATUS_MODERATE = 2;
    const STATUS_BAN = 3;
    const STATUS_DELETE = 4;

    public $showPublic = true;

    /**
     * @var Используется для хранения потомков (древовидные комменты).
     */
    protected $_children;

    public static function getStatusList()
    {
        return [
            self::STATUS_PUBLIC => 'Опубликован',
            self::STATUS_MODERATE => 'Ожидает проверки',
            //self::STATUS_BAN => 'Забанен',
            self::STATUS_DELETE => 'Удален',
        ];
    }

    public function getStatusTitle()
    {
        return self::getStatusList()[$this->status];
    }

    public function beforeValidate()
    {
        if ($this->parent_id == null) {
            $this->parent_id = 0;
        }
        return parent::beforeValidate();
    }

    /**
     * Выборка запрашиваемых комментариев.
     * @return yii\db\ActiveRecord древовидный массив комментариев.
     */
    public function getComments()
    {
        $condition = '';
        if ($this->showPublic) {
            $condition = ' AND status = 1';
        }

        $comments = self::find()
            ->where('object=:object AND object_id=:object_id'.$condition,[':object'=>$this->object,':object_id' => $this->object_id])
            ->orderBy(['parent_id' => 'ASC', 'date_create' => 'ASC'])
            ->with('author')
            ->all();

        if ($comments) {
            $comments = self::buildTree($comments);
        }
        return $comments;
    }

    public static function countModerateComments()
    {
        $connection = Yii::$app->db;

        $status = self::STATUS_MODERATE;

        $model = $connection->createCommand("
            SELECT object, COUNT(*) as cnt 
            FROM tbl_comments 
            WHERE status = $status
            GROUP BY object");

        $res = $model->queryAll();

        $comments = [];

        foreach ($res as $rs) {
            $comments[$rs['object']] = $rs['cnt'];
        }
        
        return $comments;
    }

    public static function getObjects($d=0)
    {
        $model = self::find()
            ->select(['id', 'object'])
            ->groupBy('object')
            ->all();
        
        $objects = [];

        $objectsTitle = Yii::$app->getModule('comments')->objectsTitle;
        
        foreach ($model as $obj) {
            $objects[$obj->object] = self::getObjectTitle($obj->object, $d);
        }

        return $objects;
    }
    
    public static function getObjectTitle($object, $d=0)
    {
        $objectsTitle = Yii::$app->getModule('comments')->objectsTitle;

        return (isset($objectsTitle[$object])) ? $objectsTitle[$object][$d] : $object;
    }
    

    public function getContentText()
    {
        if ($this->status == self::STATUS_BAN) {
            $this->text = 'Комментарий забанен';
        }
        if ($this->status == self::STATUS_DELETE) {
            $this->text = 'Отзыв удален';
        }
        if ($this->status == self::STATUS_MODERATE) {
            $this->text = 'Отзыв появится после проверки модератором';
        }
        return $this->text;
    }

    /**
     * Создаем дерево комментариев
     * @param array $data массив который нужно обработать
     * @param int $rootID parent_id комментария родителя
     * @return array древовидный массив комментариев
     */
    protected function buildTree(&$data, $rootId = 0) {
        $tree = [];
        foreach ($data as $id => $node) {
            if ($node->parent_id == $rootId) {
                unset($data[$id]);
                $node->children = self::buildTree($data, $node->id);
                $tree[] = $node;
            }
        }
        return $tree;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comments}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'required', 'message' => 'Введите текст отзыва', 'on'=>'create'],
            [['object','object_id','user_id', 'date_create', 'status', 'text'], 'required', 'on'=>'admin-update'],
            [['object_id', 'user_id', 'status', 'parent_id', 'show_main'], 'integer'],
            [['text'], 'string'],
            [['date_create', 'date_update'], 'safe'],
            [['object'], 'string', 'max' => 100],
            [['note'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object' => 'Кому',
            'object_id' => 'ID объекта',
            'text' => 'Текст отзыва',
            'note' => 'Комментарий модератора',
            'user_id' => 'Кто оставил отзыв',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата последнего обновления',
            'status' => 'Статус',
            'parent_id' => 'Parent ID',
            'show_main' => 'Показывать на главной',
        ];
    }


    /**
     * @return array or NULL потомки комментария.
     */
    public function getChildren()
    {
        return $this->_children;
    }
    /**
     * Задает нужное значение свойству $_childs.
     */
    public function setChildren($value)
    {
        $this->_children = $value;
    }

    /**
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->status == self::STATUS_DELETE;
    }

    /**
     * @return boolean
     */
    public function getIsBanned()
    {
        return $this->status == self::STATUS_BAN;
    }

    /**
     * @return boolean
     */
    public function getIsPublished()
    {
        return $this->status == self::STATUS_PUBLIC;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->user_id = Yii::$app->user->id;
            if (Settings::getCacheValue('moderateComments', 'comments') == 1) {
                $this->status = self::STATUS_MODERATE;
            } else {
                $this->status = self::STATUS_PUBLIC;
            }
            if ($this->show_main == null) {
                $this->show_main = 1;
            }
        }

        if ($this->date_create == null) {
            $this->date_create = DateHelper::setFormatDateTime();
        } else {
            $this->date_create = DateHelper::setFormatDateTime($this->date_create);
        }



        /*if ($this->scenario === 'delete') {
            $this->status = self::STATUS_DELETE;
        }*/

        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->date_create = DateHelper::getFormatDateTime($this->date_create);
        $this->date_update = DateHelper::getFormatDateTime($this->date_update);

        return parent::afterFind();
    }


    public function getCreateTime()
    {
        if ($this->date_create == null) {
            return $this->date_create = DateHelper::getFormatDateTime();
        }
        return $this->date_create = DateHelper::getFormatDateTime($this->date_create);
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}
