<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "images".
 *
 * @property integer $id
 * @property string $image
 * @property integer $goods_id
 */
class Images extends \yii\db\ActiveRecord
{
    public $imgFile;
    public function getGoods(){
        return $this->hasOne(Goods::className(),['id'=>'goods_id']);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['image'], 'string', 'max' => 255],
            [['imgFile'],'file','extensions'=>['jpg','png','gif']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'image',
            'goods_id' => '商品ID',
            'imgFile'=>'商品图片',
        ];
    }
}
