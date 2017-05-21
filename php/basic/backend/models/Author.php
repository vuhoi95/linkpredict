<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "author".
 *
 * @property integer $id_author
 * @property string $author
 */
class Author extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'author';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_author' => 'Id Author',
            'author' => 'Author',
        ];
    }
}
