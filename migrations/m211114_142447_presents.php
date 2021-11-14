<?php

use yii\db\Migration;

/**
 * Class m211114_142447_presents
 */
class m211114_142447_presents extends Migration
{
    public function safeUp()
    {
        $this->createTable('presents', [
            'id' => $this->primaryKey()->unsigned(),
            'type' => $this->smallInteger()->notNull()->defaultValue(1),
            'is_send' => $this->boolean()->notNull()->defaultValue(false),
            'is_refuse_prise' => $this->boolean()->notNull()->defaultValue(false),
            'user_id' => $this->integer()->unique()->notNull()->comment("Юзер"),
        ]);
    }
    public function safeDown()
    {
        $this->dropTable('presents');
    }
}
