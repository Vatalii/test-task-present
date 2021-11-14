<?php

use yii\db\Migration;

/**
 * Class m211114_142547_presents_money
 */
class m211114_142547_presents_money extends Migration
{
    private $tableName = 'presents_money';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'sum' => $this->integer()->notNull(),
            'present_id' => $this->integer()->unsigned()->unique()->notNull()->comment("Юзер"),
        ]);

        $this->addForeignKey("FK-{$this->tableName}_present_id", $this->tableName, 'present_id', 'presents', 'id');

    }
    public function safeDown()
    {
       $this->dropTable($this->tableName);
    }
}
