<?php

namespace backend\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * This is the model class for table "goods_category".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property integer $parent_id
 * @property string $intro
 */
class GoodsCategory extends \yii\db\ActiveRecord
{
    //获取当前分类的子分类
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'],'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => '树ID',
            'lft' => '左值',
            'rgt' => '右值',
            'depth' => '层级',
            'name' => '名称',
            'parent_id' => '上级分类ID',
            'intro' => '简介',
        ];
    }
    //nested-sets嵌套集合插件
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',  //可建多课树
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }
}
