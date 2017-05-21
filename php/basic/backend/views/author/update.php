<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Author */

$this->title = 'Update Author: ' . $model->id_author;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_author, 'url' => ['view', 'id' => $model->id_author]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="author-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
