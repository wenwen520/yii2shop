<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Goods extends ActiveRecord{
    public $search;
    public function rules(){

    return [
        ['search','safe'],
    ];
            }

}