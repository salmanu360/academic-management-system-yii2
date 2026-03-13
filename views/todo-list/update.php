<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TodoList */
?>
<div class="todo-list-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
