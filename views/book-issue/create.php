<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BookIssue */

 $this->title = 'Create Book Issue';
/*$this->params['breadcrumbs'][] = ['label' => 'Book Issues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="book-issue-create">

    <?= $this->render('_form', [
        'model' => $model,

    ]) ?>

</div>
