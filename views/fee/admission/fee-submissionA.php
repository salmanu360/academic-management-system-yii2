<?php
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use app\widgets\Alert;
use yii\bootstrap\Modal;

$this->registerCssFile(Yii::getAlias('@web').'/css/site.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->title = 'Fee Submission';
if(Yii::$app->request->get('ch_id')) {
    $this->registerJs("$('#generate-challan-view')[0].click();",\Yii\web\View::POS_LOAD);
} 
$settings = Yii::$app->common->getBranchSettings();
$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');?>
<?php
if(Yii::$app->request->get('ch_id')) {
    echo  Html::a('generate fee challan.',['student/generate-student-partial-fee-challan', 'challan_id' => Yii::$app->request->get('ch_id'),'stu_id' => Yii::$app->request->get('id')],['style'=>'visibility:hidden;','id'=>'generate-challan-view']);
}
$exteraHeadArrayMap = \app\models\FeeHead::find()->select(['id','title'])->where(['branch_id'=>Yii::$app->common->getBranch(),'extra_head'=>1])->asArray()->all();
?>
<?= Alert::widget()?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
           <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('success') ?>
           </div>
            <?php endif; ?>
<div class="exam-form free-generator content_col grey-form" style="padding-top: 0px"> 
	
    <?php $form = ActiveForm::begin(['id'=>'gen-fee-challan','action'=>'generate-bulk']); ?>
    	<div class="form-center shade fee-gen"> 
        <div class="row">
            <div class="col-sm-4 col-md-6 col-lg-4 rg_item">

    <!-- class start -->
    <?php 
                if(!isset($_GET['c_id'])){

                   echo $form->field($model, 'class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select '.Yii::t('app','Class').'...']);
                }else{

                $selectedClass = $_GET['c_id'];

                 echo $form->field($model, 'class_id')->dropdownList(ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),
                ['id'=>'class-id','options' => [$selectedClass => ['Selected'=>'selected']]]);
                    }
                    ?>
    <!-- class end   -->
            </div>
            <div class="col-sm-4 col-md-6 col-lg-4 rg_item">
                <?php
                // Dependent Dropdown
                if(!isset($_GET['g_id'])){

                   echo $form->field($model, 'group_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'group-id'],
                    'pluginOptions'=>[
                        'depends'=>['class-id'],
                        'prompt' => 'Select '.Yii::t('app','Group').'...',
                        'url' => Url::to(['/site/get-group'])
                    ]
                ]);
                }else{
                $selectedGroup = $_GET['g_id'];
                 echo $form->field($model, 'group_id')->dropdownList(ArrayHelper::map(\app\models\RefGroup::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'group_id', 'title'),
                ['id'=>'group-id','options' => [$selectedGroup => ['Selected'=>'selected']]]);
                    }
                ?>
                 <input type="hidden" name="" id="passClassUrls" data-url=" <?=Url::to(['/fee/generate-challan-std-list-class'])?>">
            </div>
            <div class="col-sm-4 col-md-6 col-lg-4 rg_item">
                <input type="hidden" id="subject-url" value="<?=Url::to(['/fee/oldadmission'])?>">
                <?php

                if(!isset($_GET['s_id'])){

                   echo $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'section-id'],
                    'pluginOptions'=>[
                        'depends'=>[
                            'group-id','class-id'
                        ],
                        'prompt' => 'Select '.Yii::t('app','section').'...',
                        'url' => Url::to(['/site/get-section'])
                    ]
                ]);
                }else{

                $selectedSection = $_GET['s_id'];

                 echo $form->field($model, 'section_id')->dropdownList(ArrayHelper::map(\app\models\RefSection::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active','class_id'=>$_GET['c_id'],'fk_group_id'=>$_GET['g_id']?$_GET['g_id']:null])->all(), 'section_id', 'title'),
                ['id'=>'section-id','options' => [$selectedSection => ['Selected'=>'selected']]]);
                    }
                ?> 
            </div>
        </div>
            <div class="row">
                <div id="generate-monthly-chalan-div" style="display: none;">
                    <div class="col-sm-3 col-md-3 col-lg-3 rg_item">
                        <?php
                        echo '<label>From:</label>';
                        echo DatePicker::widget([
                            'name' => 'StudentInfo[Fromdate]',
                            'value' => date('Y-m-01'),
                            'options' => ['placeholder' => ' ','class'=>'fromDateSlip'],
                            'pluginOptions' => [
                                'format' => 'yyyy-m-dd',
                                'todayHighlight' => true,
                                'autoclose'=>true,
                                //'startDate' => '+0d',
                            ]
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3 rg_item">
                        <?php
                        echo '<label>To:</label>';
                        echo DatePicker::widget([
                            'name' => 'StudentInfo[toDate]',
                            'value' => date('Y-m-t'),
                            'options' => ['placeholder'=>'Select Date','class'=>'toDateSlip'],
                            'pluginOptions' => [
                                'format' => 'yyyy-m-dd',
                                'todayHighlight' => true,
                                'autoclose'=>true,
                                'startDate' => '+0d',
                            ]
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3 rg_items">
                        <?= Html::a('Add Extra Head','javascript:void(0);', ['title'=>'Add Head','class' => 'btn btn-danger',/*'disabled'=>($net_amount-$total_amount <= 0)?true:false,*/'id'=>'add-extra-fee-head','style'=>'margin-top: 30px;'])
                        ?>
                        <div id="extra_head_amount_container"></div>
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3 rg_items">
                      <!--  <a href="javascript:void(0);" data-url="<?/*=Url::to(['fee/generate-bulk'])*/?>" id="generate_bulk_slips_btn" class="btn btn-primary" style="margin-top: 30px;">Generate Bulk Slips</a>-->
                        <button class="btn btn-primary" type="submit" style="margin-top: 30px;">Generate Bulk Slips</button>
                    </div>
                </div>
            </div>
    </div>
    <input type="hidden" name="StudentInfo[diff]" id="diff" value=""/>
    <?php ActiveForm::end(); ?>
        <div class="row"> 
            <div id="subject-inner" class="col-md-6 table-bord">  
            	<?php
					$this->registerJS("$('.cscroll').mCustomScrollbar({theme:'minimal-dark'});", \yii\web\View::POS_LOAD); 
				 ?>
            </div>
            <div id="challan-form-inner"  class="col-md-6 fee-res-right table-bord">
            </div>
        </div>
</div>
<!-- for student list get from get url -->
<?php if(!empty(yii::$app->request->get('s_id'))){ ?>
<input type="hidden" value="<?= $_GET['c_id'] ?>" id="class_id">
<input type="hidden" value="<?= $_GET['g_id'] ?>" id="group_id">
<input type="hidden" value="<?= $_GET['s_id'] ?>" id="section_id">
<!-- end for student list get from get url -->

<?php   
$script= <<< JS
$(document).ready(function() {
var class_id=$('#class_id').val();
var group_id=$('#group_id').val();
var section_id=$('#section_id').val();
if(class_id !=''){
    $('#generate-monthly-chalan-div').slideDown('slow');
} 
getStudentDetailFee(class_id,group_id,section_id);
});
JS;
$this->registerJs($script);
}

Modal::begin([
    'header'=>'<h4>ADD Head</h4>',
    'id'=>'modal-extera-head',
    'options'=>[
        'data-keyboard'=>false,
        'data-backdrop'=>"static"
    ],
    'size'=>'modal-md',
    'footer' =>'<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>'.Html::a('Add Head','javascript:void(0);', ['class' => 'btn green-btn pull-right','id'=>'save-extra-fee-head-bulk']),

]);
?>
    <div class="row">
        <div class="col-md-6 ex_head_division">
            <?php
            /*echo Html::dropDownList('s_id', null,$exteraHeadArrayMap,['class'=>'form-control','prompt'=>'Select Head...','id'=>'ex_head'])*/

            ?>
            <select id="ex_head" class="form-control" name="s_id">
                <option value="">Select Head...</option>
                <?php
                foreach ($exteraHeadArrayMap as $exhead){
                    $check='';
                    /*if(count($custom_ext_head_arr)>0){
                        if(in_array($exhead['id'],$custom_ext_head_arr,true)){
                            $check ='disabled="disabled"';
                        }else{
                            $check='';
                        }
                    }*/
                    echo '<option value="'.$exhead['id'].'" '.$check.'>'.$exhead['title'].'</option>';
                }
                ?>
            </select>
            <div class="help-block"></div>
        </div>
        <div class="col-md-6 ex_head_amount">
            <?=Html::input('number','extra_head_amount',null,['class'=>'form-control','placeholder'=>'Head Amount','id'=>'ex_head_amount'])?>
            <div class="help-block"></div>
        </div>
    </div>
<?php
Modal::end();
 ?>