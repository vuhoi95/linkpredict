<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class SearchForm extends Model
{
    public $author;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // author  are srting
            [['author'], 'string'],           
        ];
    }
}
