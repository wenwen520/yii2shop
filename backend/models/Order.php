<?php
namespace backend\models;


use yii\db\ActiveRecord;

class Order extends ActiveRecord{

    //发货状态
    public static $status=[
        '0'=>'订单取消','1'=>'待发货','2'=>'已发货',
    ];






}