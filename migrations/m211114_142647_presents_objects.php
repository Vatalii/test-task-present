<?php

use yii\db\Migration;

/**
 * Class m211114_142647_presents_objects
 */
class m211114_142647_presents_objects extends Migration
{
    private $tableName = 'presents_objects';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'present_id' => $this->integer()->unsigned()->unique()->comment("Юзер"),
        ]);


        $this->batchInsert($this->tableName,
            ['id', 'name'],
            [
                [1, 'Подарок 1'],
                [2, 'Подарок 2'],
                [3, 'Подарок 3'],
                [4, 'Подарок 4'],
            ]
        );

        $this->addForeignKey("FK-{$this->tableName}_present_id", $this->tableName, 'present_id', 'presents', 'id');

    }
    public function safeDown()
    {
        $this->dropTable('presents_objects');
    }
}
