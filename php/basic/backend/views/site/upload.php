<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', '');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nguoinopthue-import-excel">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="nguoinopthue-form">

        <?php $form = ActiveForm::begin([
            'action' => ['site/upload'],
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>

        <?= $form->field($model, 'excelFile')->fileInput()->label('Chọn tệp') ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Nhập dữ liệu'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
