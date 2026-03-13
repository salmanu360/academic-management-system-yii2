<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use kartik\export\ExportMenu;
use yii\widgets\Pjax;
use app\models\EmployeeParentsInfo;
use app\models\RefDesignation;
$this->title = 'Inactive List';
?>
<div class="row">
  <div class="col-xs-12">
   <ul class="nav nav-pills">
     <li class="active"><a href="<?php echo Url::to(['index'])?>">Go to Active List</a></li>
</ul>
</div>
</div>
<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title text-danger">Inactive Employee List</h3>
              <div class="box-tools">
                <?= Html::a('<i class="fa fa-download"></i>Generate PDF', ['inactive-emp'], 
        ['class' => 'btn btn-success','id'=>'inactive-employee-pdf']) ?>
              </div>
            </div>
            <div class="box-body table-responsive">
	<?php Pjax::begin(['id' => 'pjax-container']) ?> <!-- ajax -->
    <?= \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'id' => 'example',
            //'class'=>'table-striped',
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

             [
            'label'=>'Name',
            'value'=>function($data){
             return Yii::$app->common->getName($data->user_id);
            }

            ],
           [
            'label'=>'Parent Name',
            'value'=>function($data){
                //return $data->emp_id;
            $prntName=EmployeeParentsInfo::find()->where(['emp_id'=>$data->emp_id])->one();
            if(!empty($prntName->first_name)){
             return $prntName->first_name .' '.$prntName->middle_name .' '.$prntName->last_name;
            }
            else{
                return "N/A";
            }
            }
            ],
            [
            'label'=>'Registration No.',
            'value'=>function($data){
                // echo '<pre>';print_r($data);die;
             if(!empty($data->user->username)){
             return $data->user->username;
             }else{
             return 'N/A';                
             }
             }
             ],
             [
            'label'=>'DOB',
            'value'=>function($data){
             return $data->dob;
             }
             ],
             [
            'label'=>'Hire Date',
            'value'=>function($data){
             return $data->hire_date;
             }
             ],
             [
            'label'=>'Contact #',
            'value'=>function($data){
             return $data->contact_no;
             }
             ],
            'user.email',
             [
            'label'=>'CNIC',
            'value'=>function($data){
             if(!empty($data->cnic)){
              return $data->cnic;
            }else{
              return 'N/A';
            }
             }
             ],
             [
             'label'=>'Designation',
              'value'=>function($data){
                        //return $data->emp_id;
              $designation=RefDesignation::find()->where(['designation_id'=>$data->designation_id])->one();
              if(!empty($designation->Title)){
                     return $designation->Title;
              }
              else{
                  return "N/A";
              }
              }
              ],
             [
                'header'=>'Actions',
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' =>['style' => 'width:140px'],
                'template' => "{addEducation} {view} {delete}",
                'buttons' => [
                    'addEducation'=>function($url, $model, $key){
                    },
                    'view' => function ($url, $model, $key)
                    {
                        return Html::a('<span class="glyphicon glyphicon-eye-open btn btn-info btn-xs toltip" data-placement="bottom" width="20"  title="View Staff"></span>', ['employee/view','id'=>$key],['target'=>'_blank','data-pjax'=>"0"]);

                    },
                    'delete' => function ($url, $model, $key)
                    {
                        $id=base64_encode($model->emp_id);
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', ['employee/reactive/','id'=>$id], ['class'=>'your_class','data' => [
                            'confirm' => 'Are you sure you want to active this employee?',
                            'method' => 'post',
                        ],]);
                    },
                    /*   'Certificate' => function ($url, $model, $key)
                    {
                       return Html::a('<span class="glyphicon glyphicon-file btn btn-success btn-xs toltip" data-placement="bottom" width="20"  title="Best Teacher Certificate">', ['employee/best-certificate', 'id' => $key]);
                    },*/
                     

                ],
            ],
        ],
    ]); ?>
     <?php Pjax::end() ?>   <!-- end of ajax -->
</div>
</div>
</div>
</div>