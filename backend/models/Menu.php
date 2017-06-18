<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property integer $parent_id
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    //与自身建立对应关系
    public function getParent(){
        return $this->hasOne(self::className(),['id'=>'parent_id']);
    }
    public  function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
    //添加的分类
    public static function DropMenus(){
        //查询分类数据并展示
        $menus=ArrayHelper::merge([['id'=>0,'label'=>'顶级分类','parent_id'=>0]],self::find()->where(['parent_id'=>0])->asArray()->all());
        return $menus;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label','parent_id','sort'],'required'],
            [['parent_id', 'sort'], 'integer'],
            [['label'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '菜单名',
            'url' => '路由',
            'parent_id' => '父类',
            'sort' => '排序',
        ];
    }
}
