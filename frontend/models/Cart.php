<?php
namespace frontend\models;


use yii\db\ActiveRecord;

class Cart extends ActiveRecord{
    public function rules(){
        return [
            [['member_id','goods_id','amount'],'required'],
        ];
    }
    public function attributeLabels(){
        return[
            'member_id'=>'用户id',
            'goods_id'=>'商品id',
            'amount'=>'数量'
        ];
    }
}