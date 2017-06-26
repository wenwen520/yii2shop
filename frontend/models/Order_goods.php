<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Order_goods extends ActiveRecord{

    public function rules(){
        return [
            [['order_id','goods_id','goods_name','logo','price','amount','total'],'safe'],
        ];
    }
}