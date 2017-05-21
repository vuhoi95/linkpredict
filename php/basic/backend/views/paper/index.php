<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PaperSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
var_dump($dataProvider);
$this->title = 'Papers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="paper-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Paper', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_paper',
            'paper',
            'abstracts:ntext',
            'title:ntext',
            'year',
            // 'authors:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
