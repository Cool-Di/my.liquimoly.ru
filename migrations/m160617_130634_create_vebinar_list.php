<?php

use yii\db\Migration;

/**
 * Handles the creation for table `vebinar_list`.
 */
class m160617_130634_create_vebinar_list extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('vebinar_list', [
            'id' => $this->primaryKey(),
            'mouth' => $this->integer(11)->notNull(),
            'title' => $this->string(255)->notNull(),
            'date' => $this->string(255)->notNull(),
            'link' => $this->string(255)->notNull(),
            'video_link' => $this->string(255)->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('vebinar_list');
    }
}
