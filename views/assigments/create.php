<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Assigments */

$this->title = 'Create Assigments';
$this->params['breadcrumbs'][] = ['label' => 'Assigments', 'url' => ['display-ass']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assigments-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
