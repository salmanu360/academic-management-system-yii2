<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AddBooks */
?>
<div class="add-books-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'book_isbn_no',
            'book_no',
            'title',
            'author',
            'edition',
            'addlibrary_category_id',
            'publisher',
            'no_of_copies',
            'rack_no',
            'shelf_no',
            'book_position',
            'book_cost',
            'language',
            'book_condition',
            'fk_branch_id',
            'status',
        ],
    ]) ?>

</div>
