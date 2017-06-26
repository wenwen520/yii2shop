<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Order extends ActiveRecord{

    //发货方式
        public static  $delivery_goods=[
            ['delivery_id'=>0,'delivery_name'=>'普通快递送货上门','delivery_price'=>'￥20.00'],
            ['delivery_id'=>1,'delivery_name'=>'顺丰快递','delivery_price'=>'￥25.00'],
            ['delivery_id'=>2,'delivery_name'=>'邮政平邮','delivery_price'=>'￥10.00'],
            ['delivery_id'=>3,'delivery_name'=>'加急快递送货上门','delivery_price'=>'￥40.00'],

            ];

    //支付方式
        public static $payment_goods= [
            ['payment_id'=>0,'payment_name'=>'货到付款'],
            ['payment_id'=>1,'payment_name'=>'在线支付'],
            ['payment_id'=>2,'payment_name'=>'上门自提'],
            ['payment_id'=>3,'payment_name'=>'邮政汇款'],
            ];





    //获取省
    public function getProvince(){
        return $this->hasOne(Locations::className(),['id'=>'province_id']);
    }
    //获取市
    public function getCity(){
        return $this->hasOne(Locations::className(),['id'=>'city_id']);
    }
    //获取县区
    public function getArea(){
        return $this->hasOne(Locations::className(),['id'=>'area_id']);
    }


    public function rules(){
        return [
            [['delivery_id','delivery_name','delivery_price','price','member_id','name','province','city','area','address','tel',
                'create_time','payment_id','payment_name','total'],'safe']
        ];
    }




}