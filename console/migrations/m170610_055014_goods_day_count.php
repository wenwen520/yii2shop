<?php

use yii\db\Migration;

class m170610_055014_goods_day_count extends Migration
{
    public function safeUp()
    {
        $this->createTable('goods_day_count',[
           'day'=>$this->date()->comment('日期'),
            'count'=>$this->integer()->comment('商品数'),
        ]);
    }

    public function safeDown()
    {
        echo "m170610_055014_goods_day_count cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170610_055014_goods_day_count cannot be reverted.\n";

        return false;
    }
    */
}
