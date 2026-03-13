<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\WorkingDays */
$this->registerCssFile(Yii::getAlias('@web').'/css/site.css',['depends' => [yii\web\JqueryAsset::className()]]);

$this->title = 'Working Days';
//$this->params['breadcrumbs'][] = ['label' => 'Working Days', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?> 
     <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

