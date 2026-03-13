<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
$this->title = 'Exams';
CrudAsset::register($this);
if (isset($_GET['cid'])){?>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h3 style='text-align:center'>Schedule of <?=$exam_id->fkExamType->type.' Exam ('. yii::$app->common->getCGName($class_id,$group_id) .')' ?> 
</h3>
<div class="exam-index content_col grey-form"> 
    <div class="subjects-index shade"> 
        <div id="ajaxCrudDatatable">
            <?=GridView::widget([
                'id'=>'crud-datatable',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax'=>true,
                'columns' => require(__DIR__.'/_columns_pdf.php'),
                'toolbar'=> [
                    ['content'=>
                    Html::a('<i class="glyphicon glyphicon-download-alt"></i>', ['download-exam-schedule','cid'=>$class_id,'gid'=>$group_id,'eid'=>$exam_id],
                    ['title'=> 'Download Exam schedule','class'=>'btn btn-default','data-pjax'=>"0"])
                ],
                ],          
                'striped' => true,
                'condensed' => true,
                'responsive' => true,
                'panel' => [
                    'type' => 'primary', 
                    
                ]
            ])?>
        </div>
    </div>
</div>
<?php }else{ ?>
<div class="exam-index content_col grey-form"> 
    <div class="subjects-index shade"> 
        <div id="ajaxCrudDatatable">
            <?=GridView::widget([
                'id'=>'crud-datatable',
                'class' => '\kartik\grid\DataColumn',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export'=>false,
                'pjax'=>true,
                'columns' => require(__DIR__.'/_columns.php'),
                'toolbar'=> [
                    ['content'=>
                    Html::a('<i class="glyphicon glyphicon-download-alt"></i>', ['download-exam-schedule','cid'=>$class_id,'gid'=>$group_id,'eid'=>$exam_id],
                    ['title'=> 'Download Exam schedule','class'=>'btn btn-default','data-pjax'=>"0"])
                    /*Html::a('<i class="glyphicon glyphicon-download-alt"></i>', ['download-exam-schedule','cid'=>$class_id,'gid'=>$group_id,'sid'=>$section_id,'eid'=>$exam_id],
                    ['title'=> 'Download Exam schedule','class'=>'btn btn-default','data-pjax'=>"0"])*/
                ],
                ],          
                'striped' => true,
                'condensed' => true,
                'responsive' => true,
                'panel' => [
                    'type' => 'primary', 
                    
                ]
            ])?>
        </div>
    </div>
</div>
<?php } ?>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
