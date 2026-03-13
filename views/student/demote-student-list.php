<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$settings = Yii::$app->common->getBranchSettings();
$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');
?>
    <section class="invoice">
    <?php Pjax::begin(['enablePushState' => false, 'timeout'=>false, 'id'=>'pjax-container']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        /*'filterModel' => $searchModel,*/
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\CheckboxColumn',
            ],
             [
            'label'=>'Name',
            'value'=>function($data){
                return Yii::$app->common->getName($data->user->id);
            }
            ],

            [
                'label'=>'Father Name',
                'value'     => function($data){
                    $father_record = $data->getStudentParentsInfos()->limit(1)->one();
                    if(count($father_record) >0){
                        return Yii::$app->common->getParentName($father_record->stu_id);
                    }else{
                        return 'N/A';
                    }

                }
            ],[
                'label'=>'Registeration No.',
                'value'     => function($data){
                    return $data->user->username;
                }
            ],
            [
                'label'=>'Admission Date',
                'filter'=>'',
                'value'     => function($data){
                    return date('d M,Y',strtotime($data->registration_date));
                }
            ],
            // show details of pass and fail
            /*[
                'label'=>'Status',
                'filter'=>'',
                'format'=>'raw',
                'value'     => function($data){
                    return '<span style="color:green;font-weight:bold;">Pass</span>';
                }
            ],*/
        ],
    ]); ?>
    <?php Pjax::end() ?>
     <!--upcoming class selection-->
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning" id="success-alert" >
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong><i class="fa fa-info"></i> Note! </strong>Please Select Students to Demote
        </div>
    </div>
    </div>
    <div class="row">
    <div class="col-md-12">

        <div class="col-md-3">
            <?= $form->field($model, 'class_id[next]')->dropDownList($class_array, ['id'=>'class-id-promo','data-url' =>Url::to(['student/get-group']),'prompt' => 'Select Class ...']); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'group_id[next]')->dropDownList([], ['id'=>'group-id-promo','data-url' =>Url::to(['student/get-section']),'prompt' => 'Select Group ...','disabled'=>true]); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'section_id[next]')->dropDownList([], ['id'=>'section-id-promo','prompt' => 'Select Section ...','disabled'=>true]); ?>
        </div>
        <div class="col-md-3">
        	<br />
            <?=Html::submitButton('Demote',['class'=>'btn btn-success btn-promote-std','id'=>'btn-promote-std','data-url'=>Url::to(['student/save-demoted-student'])])?>
        </div>
    </div>
    </div>
    <?php $form = ActiveForm::end(); ?>
    </section>