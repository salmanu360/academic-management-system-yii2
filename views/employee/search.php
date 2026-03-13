<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use kartik\export\ExportMenu;
use yii\widgets\Pjax;
use app\models\EmployeeParentsInfo;
use app\models\RefDesignation;
use yii\widgets\ActiveForm;
$this->title = 'Employee List';
?>
<div class="box-body table-responsive" id="ajaxCrudDatatable">
	<?php Pjax::begin(['id' => 'pjax-container']) ?> <!-- ajax -->
    <?= \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'id' => 'example',
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
                'template' => "{addEducation} {view} {update} {delete} {changepassword} ",
                'buttons' => [
                    'addEducation'=>function($url, $model, $key){
                    },
                    'view' => function ($url, $model, $key)
                    {
                        return Html::a('<span class="glyphicon glyphicon-user btn btn-info btn-xs toltip" data-placement="bottom" width="20"  title="Profile"></span>', ['employee/view','id'=>$key],['target'=>'_blank','data-pjax'=>"0"]);
                    },
                    'update' => function ($url, $model, $key)
                    {
                       return Html::a('<span class="glyphicon glyphicon-pencil btn btn-primary btn-xs toltip" data-placement="bottom" width="20"  title="Edit">', ['employee/update', 'id' => $key]);
                    },
                    'delete' => function ($url, $model, $key)
                    {
                        $id=base64_encode($model->emp_id);
                        return Html::a('<span class="glyphicon glyphicon-trash btn btn-danger btn-xs" title="Deactivate"></span>', ['employee/delete/','id'=>$id], ['class'=>'your_class','data' => [
                            'confirm' => 'Are you sure you want to Deactivate this employee?',
                            'method' => 'post',
                        ],]);
                        /*return Html::a(Yii::t('yii', '<span class="glyphicon glyphicon-trash toltip" data-placement="bottom" width="20" title="In Active Staff"></span>'), 'update-status/'.$model->emp_id.'', [
                            '	title' => Yii::t('yii', 'update-status'),
                            'aria-label' => Yii::t('yii', 'update-status'),
                            'onclick' => "
                                if (confirm('Are You Sure You Want To In active this Employee...?')) {
                                    $.ajax('$url', {
                                        type: 'POST'
                                    }).done(function(data) {
                                        $.pjax.reload({container: '#pjax-container'});
                                    });
                                }
                                return false;
                            ",
                            'class' => 'btn btn-danger btn-xs',
                        ]);*/
                    },
                       'Certificate' => function ($url, $model, $key)
                    { 
                       return Html::a('<span class="glyphicon glyphicon-file btn btn-success btn-xs toltip" data-placement="bottom" width="20"  title="Best Teacher Certificate">', ['employee/best-certificate', 'id' => $key]);
                    },
                    'changepassword' => function ($url, $model, $key)
                    { 
                       return Html::a('<span data-toggle="modal" data-target="#myModal" id="changePass" data-id='.$model->user_id.' class="glyphicon glyphicon-pushpin btn btn-warning btn-xs toltip" data-placement="bottom" width="20"  title="Change Password">');
                    },
                     /*'pdf'=>function($url, $model, $key){
                        return Html::a('<span class="glyphicon glyphicon-file" data-placement="bottom" width="20"  title="Generate pdf"></span>', ['employee/create-mpdf','id'=>$key]);
                    },*/
                ],
            ],
        ],
    ]); ?>
     <?php Pjax::end() ?>   <!-- end of ajax -->
</div>