<?php

/* @var $this yii\web\View */
use yii\bootstrap\Modal;
$this->title = 'My Yii Application';
?>


<div class="site-index">

    <div class="jumbotron">
        <h1>Link prediction!</h1>

        <!-- <p class="lead">You have successfully created your Yii-powered application.</p> -->

        <p>
        	<?php
	            Modal::begin([
	                'toggleButton' => [
	                    'label' => '<i class="glyphicon glyphicon-plus"></i>' . Yii::t('app', 'Chọn file'),
	                    'class' => 'btn btn-info'
	                ],
	                'closeButton' => [
	                    'label' => Yii::t('app', 'Close'),
	                    'class' => 'btn btn-danger btn-sm pull-right',
	                ],
	                'size' => 'modal-md',
	            ]);
	            $model = new \backend\models\ExcelUploadForm();
	            echo $this->render('/site/upload', ['model' => $model]);
	            Modal::end();
          	?>
          	
        </p>
    </div>
</div>
