<?php

use yii\db\Migration;

/**
 * Handles the creation of table `opencartver`.
 */
class m180304_182515_create_opencartver_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('opencartver', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment('Версия Opencart')->notNull(),
            'value'=>$this->string()->comment('Значение')->null(),
        ]);

        $this->batchInsert('opencartver', ['name','value'],
            [
                ['1.5.1.3 - 1.5.5.1','1.5'],
                ['1.5.6 - 1.5.6.4','1.5.6'],
                ['1.5.6 - 1.5.6.4','1.5.6'],
                ['2.0.0.0 - 2.0.3.1','2.0'],
                ['2.1.0.1 - 2.1.0.2','2.1'],
                ['2.2.0.0','2.2'],
                ['2.3.0.0 - 2.3.0.2','2.3'],
                ['2.3.0.0 - 2.3.0.2','2.3'],
                ['3.0.0.0 - 3.0.3.0','3.0'],
            ]
     );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('opencartver');
    }
}
