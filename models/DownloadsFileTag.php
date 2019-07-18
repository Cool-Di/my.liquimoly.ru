<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "downloads_file_tag".
 *
 * @property integer $file_id
 * @property integer $tag_id
 */
class DownloadsFileTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'downloads_file_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_id', 'tag_id'], 'required'],
            [['file_id', 'tag_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file_id' => 'File ID',
            'tag_id' => 'Tag ID',
        ];
    }
}
