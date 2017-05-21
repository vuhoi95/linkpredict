<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Paper */

$this->title = 'Update Paper: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Papers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id_paper]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="paper-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
