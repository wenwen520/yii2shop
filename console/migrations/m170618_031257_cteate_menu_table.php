<?php

use yii\db\Migration;

class m170618_031257_cteate_menu_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('menu',[
            'id'=>$this->primaryKey(),
            'label'=>$this->string(20)->comment('菜单名'),
            'url'=>$this->string(40)->comment('路由'),
            'parent_id'=>$this->integer()->comment('父类'),
            'sort'=>$this->integer()->comment('排序'),
        ]);
    }

    public function safeDown()
    {
        echo "m170618_031257_cteate_menu_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170618_031257_cteate_menu_table cannot be reverted.\n";

        return false;
    }
    */
}
