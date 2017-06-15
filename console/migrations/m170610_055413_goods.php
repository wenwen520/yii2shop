<?php

use yii\db\Migration;

class m170610_055413_goods extends Migration
{
    public function safeUp()
    {
        $this->createTable('goods',[
           'id'=>$this->primaryKey(),
            'name'=>$this->string(20)->comment('商品名称'),
            'sn'=>$this->string(20)->comment('货号'),
            'logo'=>$this->string(255)->comment('LOGO'),
            'goods_category_id'=>$this->integer()->comment('商品分类ID'),
            'brand_id'=>$this->integer()->comment('品牌ID'),
            'market_price'=>$this->decimal(10,2)->comment('市场价格'),
            'shop_price'=>$this->decimal(10,2)->comment('商品价格'),
            'stock'=>$this->integer()->comment('库存'),
            'is_on_sale'=>$this->smallInteger(1)->comment('在售'),
            'status'=>$this->smallInteger(1)->comment('状态'),
            'sort'=>$this->integer()->comment('排序'),
            'create_time'=>$this->integer()->comment('添加时间'),
        ]);
    }

    public function safeDown()
    {
        echo "m170610_055413_goods cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170610_055413_goods cannot be reverted.\n";

        return false;
    }
    */
}
