<?php

/* @var $this yii\web\View */
use yii\helpers\ArrayHelper;
use backend\models\Author;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = 'My Web Application';
?>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0; background: #eda509">
            <div class="navbar-header">
                
                <a class="navbar-brand" href="index.html" style="color: white; font-size: 30px">Link Prediction</a>
            </div>
            <!-- /.navbar-header 
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper row">
            <div class="col-md-6">
                <input type="hidden" id="DOTstring" value="<?= $DOTstring ?>"> 
                <div id="mynetwork" style="width: auto;height: 400px;"></div>
            </div>

            <div class="col-md-6">
                <br>
                <?php if(Yii::$app->session->hasFlash('error')):?>
                    <div class="alert alert-danger">
                        <?php echo Yii::$app->session->getFlash('error'); ?>
                    </div>
                <?php endif; ?>
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <?php $form = ActiveForm::begin(); ?>
                            <div class="input-group custom-search-form">
                                
                                <?= $form->field($model, 'author')->textInput(['class' => 'form-control','style' => 'top:-5px;','placeholder' => 'Search...'])->label(false); ?>
                                
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit" style="background: #07b3bc">
                                        <i class="fa fa-search" style="color: white; background: #07b3bc"></i>
                                    </button>
                                </span>
                            </div>
                            <?php ActiveForm::end(); ?>
                            <!-- /input-group -->
                        </li>
                        <?php foreach ($search as $key => $value) { ?>
                        <li>
                            <input id="<?= $key ?>" type="hidden" name="" value="<?= $listDOTstring[$key][0] ?>">
                            <a onclick="getDot(<?= $key ?> )" href="#"><i class="fa fa-calendar" style="color: #07b3bc"></i><span class="fa arrow"></span><?= $key ?></a>
                            <ul class="nav nav-second-level">
                                <?php foreach ($value as $k => $v) { ?> 
                                <li>                            
                                    <div class="timeline-panel" style="background:white;margin: 5px 0;padding: 10px;">
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title"><?= $v['title'] ?></h4>
                                            <p><small class="text-muted"><i class="fa fa-user"></i><?= $v['authors_view'] ?></small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p><?= $v['abstracts'] ?></p>
                                            <p><?= $v['paper'] ?></p>
                                        </div>
                                    </div>                                                       
                                </li>
                                <?php } ?>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php } ?>
                    </ul>
                </div>

                
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="color: white;background: #07b3bc">
                            <i class="fa fa-handshake-o"></i> List authors has link
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
                                <?php foreach ($list_author_link as $key) { ?>
                                <a href="#" class="list-group-item" style="color: black">
                                    <i class="fa fa-user" style="color: #eda509"></i> <?= $key ?>
                                </a>
                                <?php } ?>
                            </div>
                            <!-- /.list-group 
                            <a href="#" class="btn btn-default btn-block" style="color: black; background: pink">View All Alerts</a>-->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                <!-- /.sidebar-collapse -->
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="color: white;background:#07b3bc">
                            <i class="fa fa-handshake-o"></i> List authors will link
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
                                <?php foreach ($author_will_link_view as $key => $value) { ?>
                                <h5><?= $key ?></h5>
                                <?php
                                    foreach ($value as $k => $v) { 
                                ?>
                                <a href="#" class="list-group-item" style="color: black">
                                    <i class="fa fa-user" style="color: #eda509"></i> <?= $v ?>
                                </a>
                                <?php }} ?>
                            </div>
                            <!-- /.list-group
                            <a href="#" class="btn btn-default btn-block" style="color: black; background: powderblue">View All Alerts</a> -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                <!-- /.sidebar-collapse -->
                </div>
        </div>

        
        <!-- /#page-wrapper -->

    </div>