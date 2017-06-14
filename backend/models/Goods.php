<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 */
class Goods extends \yii\db\ActiveRecord
{
    public $condition;
    public $imgFile;
    public static $is_on_sale_options=[0=>'下架',1=>'在售'];
    public static $status_options=[0=>'回收站',1=>'正常'];
    //与商品详情关联
    public function getContent(){
        return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id']);
    }
    //与商品分类关联
    public function getCate(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
    //与品牌关联
    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
    //与相册关联
    public function getImages(){
        return $this->hasOne(Images::className(),['goods_id'=>'id']);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','market_price','shop_price','stock','sort'],'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'create_time'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
            ['imgFile','file','extensions'=>['jpg','png','gif']],
            ['condition','string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'LOGO',
            'goods_category_id' => '商品分类ID',
            'brand_id' => '品牌ID',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '在售',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '添加时间',
            'condition'=>'',
        ];
    }
}
