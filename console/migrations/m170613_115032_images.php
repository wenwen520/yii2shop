<?php

use yii\db\Migration;

class m170613_115032_images extends Migration
{
    public function safeUp()
    {
        $this->createTable('images',[
            'id'=>$this->primaryKey(),
            'image'=>$this->string()->comment('image'),
            'goods_id'=>$this->integer()->comment('商品ID')
        ]);
    }

    public function safeDown()
    {
        echo "m170613_115032_images cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170613_115032_images cannot be reverted.\n";

        return false;
    }
    */
}
