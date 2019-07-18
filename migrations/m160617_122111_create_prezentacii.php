<?php

use yii\db\Migration;

/**
 * Handles the creation for table `prezentacii`.
 */
class m160617_122111_create_prezentacii extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('prezentacii', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'img' => $this->string(255)->notNull(),
            'file' => $this->string(255)->notNull(),
            'cat_id' => $this->string(255)->notNull(),
            'show_yn' => $this->integer(1)->notNull()->defaultValue(0)
        ]);
        $this->createTable('prezentacii_cat', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()
        ]);
        $this->insert('prezentacii_cat', [
            'id' => '1',
            'name' => 'Общая информация о бренде и компании',
        ]);
        $this->insert('prezentacii_cat', [
            'id' => '2',
            'name' => 'Дилерская конференция в Сочи',
        ]);
        $this->insert('prezentacii_cat', [
            'id' => '3',
            'name' => 'Новинки',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('prezentacii');
        $this->dropTable('prezentacii_cat');
    }
}
