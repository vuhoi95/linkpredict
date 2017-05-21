<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "papertemp".
 *
 * @property integer $id_paper
 * @property string $paper
 * @property string $abstracts
 * @property string $title
 * @property integer $year
 * @property string $authors
 */
class Papertemp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'papertemp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['abstracts', 'title', 'authors'], 'string'],
            [['year'], 'integer'],
            [['paper'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_paper' => 'Id Paper',
            'paper' => 'Paper',
            'abstracts' => 'Abstracts',
            'title' => 'Title',
            'year' => 'Year',
            'authors' => 'Authors',
        ];
    }
}
