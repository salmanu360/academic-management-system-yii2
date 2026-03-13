<?php 
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\RefClass;
use app\models\RefGroup;
$this->title = 'Class Summary';

$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
$this->registerCssFile(Yii::getAlias('@web').'/css/site.css',['depends' => [yii\web\JqueryAsset::className()]]);
?>	
<div class="pad margin no-print">  
    <div style="margin-bottom: 0!important; background-color: #ffffff !important;padding: 10px;margin-top: -20px"> 
    
            <?php $form = ActiveForm::begin(); ?> 
        <div class="row">
           <div class="col-sm-9">
           		<div class="col-sm-4 fh_item" style="color: black !important;">
                <?php 
                if(!isset($_GET['id'])){

                    echo  $form->field($model, 'class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select'.' '. Yii::t('app','Class')]);
                }else{

                $selectedClass = $_GET['cid'];

                 echo $form->field($model, 'class_id')->dropdownList(ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),
                ['id'=>'class-id','options' => [$selectedClass => ['Selected'=>'selected']]]);
                    }
                    ?>
					
                </div>
                <div class="col-sm-4 fh_item" style="color: black !important;">
                    <?php
                    if(!isset($_GET['id'])){
                            echo $form->field($model, 'group_id')->widget(DepDrop::classname(), [
                            'options' => ['id'=>'group-id'],
                            'pluginOptions'=>[
                            'depends'=>['class-id'],
                            'prompt' => 'Select Group...',
                            'url' => Url::to(['/site/get-group'])
                        ]
                    ]);
                    }else{
                        echo $form->field($model, 'group_id')->widget(DepDrop::classname(), [
                            'options' => ['id'=>'group-id','value'=>1],
                            'pluginOptions'=>[
                            'depends'=>['class-id'],
                            'prompt' => 'Select Group...',
                            'url' => Url::to(['/site/get-group'])
                        ]
                    ]);?>
                        <?php if(empty($_GET['gid'])){
                            $grpid='';
                            ?>
                     <span style="color:red"><?php echo yii::$app->common->getCGSName($_GET['cid'],$grpid,$_GET['id'])?></span>
                            
                        <?php }else{?>
                     <span style="color:red"><?php echo yii::$app->common->getCGSName($_GET['cid'],$_GET['gid'],$_GET['id'])?></span>
                           <?php } }?>
                </div>
                <div class="col-sm-4 fh_item" style="color: black !important;">
                    <input type="hidden" id="subject-url" value="<?=Url::to(['/analysis/get-section-analysis'])?>">
                    <?php
                    
                    // Dependent Dropdown
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
           </div> 
        </div> 
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div  id="subject-details">
            <div id="subject-inner"></div>
        </div> 
<!-- null -->
<?php if(isset($_GET['id'])){
if(empty($_GET['gid'])){ ?>
<input type="hidden" id="clas" value="<?php echo $_GET['cid']; ?>">
<input type="hidden" id="grp" value="">
<input type="hidden" id="sectn" value="<?php echo $_GET['id']; ?>">
<?php }else{?>
<input type="hidden" id="clas" value="<?php echo $_GET['cid']; ?>">
<input type="hidden" id="grp" value="<?php echo $_GET['gid']; ?>">
<input type="hidden" id="sectn" value="<?php echo $_GET['id']; ?>">
<?php } } 
if(!empty(yii::$app->request->get('id'))){
$script= <<< JS
$(document).ready(function() {
   var sectionId = $('#sectn').val();
   var classId = $('#clas').val();
   var groupId = $('#grp').val();
   //var url = $('#sectn').closest('.col-sm-4').find('#subject-url').val();
   var url=$('#subject-url').val();
   //alert(groupId);
    $("#subject-inner").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
    if(groupId ==''){ 
        groupId=null;
    }
    $.ajax
    ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {class_id:classId,group_id:groupId,section_id:sectionId},
        success: function(data)
        {
            if(data.status== 1){
                $("#subject-inner").empty().html(data.details);
                //$('#subject-details').html();
            }
        }
    });

});
JS;
$this->registerJs($script);
}
?>