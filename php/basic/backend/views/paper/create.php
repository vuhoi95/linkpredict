<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Paper */
/* @var $year backend\models\Paper */

$this->title = 'Thêm bài báo theo năm';
$this->params['breadcrumbs'][] = ['label' => 'Papers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="paper-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'year'  => $year,
    ]) ?>

</div>
