<?php

use yii\db\Migration;

/**
 * Handles the creation for table `news`.
 */
class m160530_080556_create_news extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('news', [
            'news_id'           => $this->primaryKey(),
            'news_time'         => $this->dateTime()->notNull(),
            'news_name'         => $this->string(255)->notNull(),
            'news_short_desc'   => $this->string(1024),
            'news_desc'         => $this->text(),
            'news_img'          => $this->string(255)->notNull(),
            'news_showme'       => $this->integer(1)->notNull()->defaultValue(0)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('news');
    }
}
