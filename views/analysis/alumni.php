<?php 
use yii\helpers\Html; 
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\RefClass;
use app\models\RefGroup;
use yii\grid\GridView; 
$this->title = 'Alumni';
?>
<div class="pad margin no-print">  
    <div style="margin-bottom: 0!important; background-color: #ffffff !important;padding: 10px;margin-top: -20px"> 
    
            <?php $form = ActiveForm::begin(); ?> 
        <div class="row">
           <div class="col-sm-12">
           		<div class="col-sm-3 fh_item" style="color: black !important;">
                <?php echo  $form->field($model, 'class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select'.' '. Yii::t('app','Class')]);?>
                </div>
                <div class="col-sm-2 fh_item" style="color: black !important;">
                    <?php
                            echo $form->field($model, 'group_id')->widget(DepDrop::classname(), [
                            'options' => ['id'=>'group-id'],
                            'pluginOptions'=>[
                            'depends'=>['class-id'],
                            'prompt' => 'Select Group...',
                            'url' => Url::to(['/site/get-group'])
                        ]
                    ]); ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    echo $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                        'options' => ['id'=>'section-id'],
                        'pluginOptions'=>[
                            'depends'=>[
                                'group-id','class-id'
                            ],
                            'prompt' => 'Select section',
                            'url' => Url::to(['/site/get-section'])
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md-3">
                	<?php 
                    echo  $form->field($model, 'session_id')->dropDownList($session_array, ['prompt' => 'Select'.' '. Yii::t('app','Session')]);?>
                </div>
                <div class="col-md-2">
                    <label>Active/Alumni</label>
                    <?= $form->field($model, 'is_active')->dropDownList([ 1 => 'Active', 0 => 'Alumni'])->label(false) ?>

                </div>
            </div>
                
           </div> 
           <div class="row"> 
            <div class="col-sm-12">
                <button type="submit" class="btn btn-success" style="margin-left: 14px;">Submit</button>
            </div>
           </div>
         
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php if(Yii::$app->request->post('StudentInfo')){ ?>
<div class="panel panel-body">
    <?= Html::a('<i class="fa fa-download"></i>Generate PDF', ['alumnipdf','id'=>$session_id,'class_id'=>$class_id,
'group_id'=>($group_id)?$group_id:null,'section_id'=>$section_id,'is_active'=>$is_active], 
        ['class' => 'btn btn-success','id'=>'generate-employee-pdf']) ?>
 <?= GridView::widget([ 
        'dataProvider' => $dataProvider, 
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'], 
            ['attribute'=>'user_id',
             'label'=>'Reg. No.',
             'value'=>function($data){
             	return Yii::$app->common->getUserName($data->user_id);
             }
        	],
        	['attribute'=>'stu_id',
             'label'=>'Name',
             'value'=>function($data){
             	return Yii::$app->common->getName($data->user_id);
             }
        	],
        	[
             'label'=>'Father',
             'value'=>function($data){
             	return Yii::$app->common->getParentName($data->stu_id);
             }
        	],

            [
             'label'=>'Father CNIC',
             'value'=>function($data){
                 $parentDetail=Yii::$app->common->getParent($data->stu_id);
                 if($parentDetail){
                    return $parentDetail->cnic;
                 }else{
                    return 'N/A';
                 }
                 
             }
            ],

            [
             'label'=>'Father Contact No',
             'value'=>function($data){
                 $parentDetail=Yii::$app->common->getParent($data->stu_id);
                 if($parentDetail){
                    return $parentDetail->contact_no;
                 }else{
                    return 'N/A';
                 }
             }
            ],
            'dob',
            //'contact_no',
            //'emergency_contact_no',
            //'gender_type',
            //'guardian_type_id',
            //'country_id',
            //'province_id',
            //'city_id',
            //'registration_date',
            //'fee_generation_date',
            //'monthly_fee_gen_date',
            //'session_id',
            //'group_id',
            //'shift_id',
            //'class_id',
            //'section_id',
            //'cnic',
            //'location1',
            //'location2',
            //'withdrawl_no',
            //'district_id',
            //'religion_id',
            //'parent_status:boolean',
            //'is_hostel_avail',
            //'fk_stop_id',
            //'fk_fee_plan_type',
            //'is_active',
            //'fk_ref_country_id2',
            //'fk_ref_province_id2',
            //'fk_ref_district_id2',
            //'fk_ref_city_id2',
            //'transport_updated',
            //'hostel_updated',
            //'school_leave',
            //'avail_sibling_discount',
            //'roll_no',
            //'tribe',

            // ['class' => 'yii\grid\ActionColumn'], 
        ], 
    ]); ?>
    </div>
<?php } ?>