<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<div class="panel panel-primary">
  <div class="panel-heading">Leaving info <a href="" class="btn btn-success pull-right" style="margin-top: -7px;">Back</a></div>
  <div class="panel-body">
  	 <table class="table">
        <tr>
            <th>Reg. No</th>
            <th>Name</th>
            <th>Father Name</th>
            <th>Class</th>
            <th>Session</th>
            <th>Enroll Class</th>
            <th>Action</th>
        </tr>
        <tr>
            <td><?php echo $username;?></td>
            <td><?= Yii::$app->common->getName($userTable->id) ?></td>
            <td><?= Yii::$app->common->getParentName($studentTable->stu_id) ?></td>
            <td><?php echo Yii::$app->common->getCGSName($studentTable['class_id'],$studentTable['group_id'],$studentTable['section_id']) ?></td>
            <td><?php echo $sessionDetails['title'] ?></td>
            <td><?php if(!empty($StudentLeaveInfo->enrollment_class)){echo ucfirst($StudentLeaveInfo->fkEnrollClass->title);}else{echo "N/A";} ; ?></td>
            <td>
            <a data-toggle="modal" data-target="#myModal" href="#"><span class="glyphicon glyphicon-edit" title="Edit Enroll Class"></span></a>
            <?php if(!empty($StudentLeaveInfo->enrollment_class)){?>
             <a href="<?php echo Url::to(['student/slcslip','id'=>$StudentLeaveInfo['id']])?>"><span class="glyphicon glyphicon-download" title="Download slc"></span></a>
            <?php }?>
            </td>
        </tr>
     </table>
  </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    
    <?php $form = ActiveForm::begin() ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add enroll class</h4>
      </div>
      <div class="modal-body">
        <p>
        <?php 
        $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['status'=>'active'])->all(), 'class_id', 'title'); 
        echo $form->field($StudentLeaveInfo, 'enrollment_class')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select'.' '. Yii::t('app','Class')]);?>
        <?= $form->field($StudentLeaveInfo, 'stu_id')->hiddenInput()->label(false) ?>    
    </p>
      </div>
      <div class="modal-footer">
        <button type="submit" name="updateenroll" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php ActiveForm::end(); ?> 
  </div>
</div>
