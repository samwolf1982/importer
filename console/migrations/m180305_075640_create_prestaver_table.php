<?php

use yii\db\Migration;

/**
 * Handles the creation of table `prestaver`.
 */
class m180305_075640_create_prestaver_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('prestaver', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment('Версия Prestashop')->notNull(),
            'value'=>$this->string()->comment('Значение')->null(),
        ]);

        $this->batchInsert('prestaver', ['name','value'],
            [
                ['1.5.5 - 1.5.6','1.5'],
                ['1.6.0 - 1.6.1','1.6'],
                ['1.7.0 - 1.7.3','1.7'],

            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('prestaver');
    }
}
