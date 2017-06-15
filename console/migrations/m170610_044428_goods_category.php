<?php

use yii\db\Migration;

class m170610_044428_goods_category extends Migration
{
    public function safeUp()
    {
        $this->createTable('goods_category', [
            'id' => $this->primaryKey(),
            'tree'=>$this->integer()->comment('树ID'),
            'lft'=>$this->integer()->comment('左值'),
            'rgt'=>$this->integer()->comment('右值'),
            'depth'=>$this->integer()->comment('层级'),
            'name'=>$this->string(50)->comment('名称'),
            'parent_id'=>$this->integer()->comment('上级分类ID'),
            'intro'=>$this->text()->comment('简介'),
        ]);
    }

    public function safeDown()
    {
        echo "m170610_044428_goods_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170610_044428_goods_category cannot be reverted.\n";

        return false;
    }
    */
}
