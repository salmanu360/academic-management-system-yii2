<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
$this->title = 'Fee Arears';
CrudAsset::register($this);
?>
<style>
    #example_filter{
   float: right;
   color: #337ab7;
   } 
</style>
<div class="row">
<div class="box-body">
<div class="col-md-3">
 <input style="border: 1px solid blue;" type="text" id="inputValue" class="form-control" placeholder="Search by Student name Or Reg #." name="search" title="Type in a name" autocomplete="off"/>
</div>
<div class="col-md-3"><input type="submit" value="Search" id="buttonClick" class="btn btn-primary" data-url="<?php echo Url::to(['search-arrears']) ?>">&nbsp;&nbsp;
    <a href="<?php echo Url::to(['index']) ?>" value="refresh" class="btn btn-danger">Refresh</a>
</div>
    </div>
</div>
<div class="fee-arears-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'tableOptions' => [
            'id' => 'example',
            ],
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> 'Create new Fee Arears','class'=>'btn btn-default']).
                    Html::a('<i class="glyphicon glyphicon-download-alt"></i>', ['download'],
                    ['title'=> 'Download Arrears List','class'=>'btn btn-default','target'=>'_blank','data-pjax'=>"0"])
                    .
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
                'heading' => '<i class="glyphicon glyphicon-list"></i> Fee Arears listing',
                'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',                     
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
<?php Modal::end();?>