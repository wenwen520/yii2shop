<?php

use yii\db\Migration;

class m170610_060405_goods_intro extends Migration
{
    public function safeUp()
    {
        $this->createTable('goods_intro',[
            'goods_id'=>$this->integer()->comment('商品ID'),
            'content'=>$this->text()->comment('商品详情'),
        ]);
    }

    public function safeDown()
    {
        echo "m170610_060405_goods_intro cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170610_060405_goods_intro cannot be reverted.\n";

        return false;
    }
    */
}
