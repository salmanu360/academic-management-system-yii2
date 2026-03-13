<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TodoList */

?>
<div class="todo-list-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
