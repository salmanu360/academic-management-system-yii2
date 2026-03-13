<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = 'Fee Submissions';
CrudAsset::register($this);

Modal::begin([
    'header'=>'<h4>Update Fee</h4>',
    'id'=>'modal',
    'size'=>'modal-md',
    'footer' =>'<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>',
]);
echo '<div id="modalContent"></div>';
Modal::end();

?>
<div class="fee-submission-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'tableOptions' => [
            'id' => 'myTable',
            //'class'=>'table-striped',
            ],
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    /*Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> 'Create new Fee Submissions','class'=>'btn btn-default'])*/
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    '{toggleData}'.
                    '{export}'
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> Fee Submissions listing',
                'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                /*'after'=>BulkButtonWidget::widget([
                            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Delete All',
                                ["bulk-delete"] ,
                                [
                                    "class"=>"btn btn-danger btn-xs",
                                    'role'=>'modal-remote-bulk',
                                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                    'data-request-method'=>'post',
                                    'data-confirm-title'=>'Are you sure?',
                                    'data-confirm-message'=>'Are you sure want to delete this item'
                                ]),
                        ]). */                       
                        '<div class="clearfix"></div>',
            ]
        ])?>
    </div>
</div>
<?php Modal::begin([
'options' => [
        'id' => 'ajaxCrudModal',
        'tabindex' => false
             ],
   "footer"=>"",
])?>
<?php Modal::end(); ?>

<script type="text/javascript">
    $('.modalButton').click(function(){
    $('#modal').modal('show')
    .find('#modalContent')
    .load($(this).attr('value'));

});
</script>