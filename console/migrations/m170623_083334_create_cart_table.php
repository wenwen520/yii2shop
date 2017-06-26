<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m170623_083334_create_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer(),
            'goods_id'=>$this->integer(),
            'amount'=>$this->integer(),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cart');
    }
}
