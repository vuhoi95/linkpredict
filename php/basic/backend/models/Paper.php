<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "paper".
 *
 * @property integer $id_paper
 * @property string $paper
 * @property string $abstracts
 * @property string $title
 * @property integer $year
 * @property string $authors
 */
class Paper extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $yearr;
    public $monthh;
    public static function tableName()
    {
        return 'paper';
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
            [['yearr'],'string'],
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
            'yearr' => 'Năm',
            'authors' => 'Authors',
        ];
    }
}
