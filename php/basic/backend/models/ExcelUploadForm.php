<?php
/**
 * Created by PhpStorm.
 * User: thanh
 * Date: 22/6/2016
 * Time: 11:48 AM
 */

namespace backend\models;


use yii\base\Model;
use yii\web\UploadedFile;

class ExcelUploadForm extends Model
{
    /**
     * @var $excelFile UploadedFile
     */
    public $excelFile;

    public $path;

    public function rules()
    {
        return [
            [['excelFile'], 'required'],
            [['excelFile'], 'safe'],
            [['excelFile'], 'file', 'extensions' => ['txt', 'xlsm'], 'checkExtensionByMimeType' => false],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $path = '../../uploads/' . md5($this->excelFile->baseName . date("Y-m-d H:i:s")) . '.' . $this->excelFile->extension;
            $this->path = $path;
            $this->excelFile->saveAs($path);
            return true;
        } else {
            return false;
        }
    }
}