<?php 
use yii\helpers\Url;
use yii\widgets\Pjax;
Pjax::begin();
 ?>
<div class="panel panel-default panel-body">
  <div class="row">
  <div class="col-md-12">
    <strong style="color:red">All Siblings</strong>
    <a style="margin-left: 5px" class="btn btn-danger pull-right" href="<?php echo Url::to(['general-report']) ?>">Back</a>
    <a href="<?=\yii\helpers\Url::to(['reports/sibling-pdf/']) ?>" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Generate Report</a>
  </div></div>

<div class="table-responsive">
                <table class="table table-striped">
                  <tr class="alert alert-success">
                    <td>Reg. No.</td>
                    <td>Roll #</td>
                    <td>Class</td>
                    <td>Name</td>
                    <td>Parent</td>
                    <td>Parent CNIC</td>
                    <td>Parent Contact</td>
                    <td>Discount</td>
                  </tr>
                    <?php
                    $i=1;
                    $totalSibling=0;
                     foreach ($sibling as $key => $studentCount) {
                      $settings = Yii::$app->common->getBranchSettings();
                      $getParents=\app\models\StudentParentsInfo::find()->where(['stu_id'=>$studentCount->stu_id])->one();
                       $getHeadSiblings=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch()])->one();
                       $getFeeDetails = \app\models\FeeGroup::find()
                       ->where([
                           'fk_branch_id'  =>Yii::$app->common->getBranch(),
                           'fk_class_id'   => $studentCount->class_id,
                           'fk_fee_head_id'   => $getHeadSiblings['id'],
                           'fk_group_id'   => ($studentCount->group_id)?$studentCount->group_id:null,
                       ])->one();?>
                  <tr>
                    <td><?php echo Yii::$app->common->getUserName($studentCount->user_id) ?></td>
                    <td><?php echo ($studentCount->roll_no)?$studentCount->roll_no:'N/A'; ?></td>
                    <td><?php echo Yii::$app->common->getCGSName($studentCount->class_id,$studentCount->group_id,$studentCount->section_id) ?></td>
                    <td><?php echo Yii::$app->common->getName($studentCount->user_id) ?></td>
                    <td><?php echo Yii::$app->common->getParentName($studentCount->stu_id) ?></td>
                    <td><?php echo $getParents->cnic ?></td>
                    <td><?php echo ($getParents->contact_no)?($getParents->contact_no):'N/A'; ?></td>
                    <td>Rs. <?php if($studentCount->avail_sibling_discount == 1 && $getFeeDetails->fk_fee_head_id == $getHeadSiblings->id && $getHeadSiblings->sibling_discount ==1){
                    echo $amount=$getFeeDetails->amount*$settings->sibling_discount/100;
                    $totalSibling=$totalSibling+$amount;
            }?></td>
                  </tr>
                    <?php $i++;}?>
               <tr>
                 <th colspan="7">Total</th>
                 <th>Rs. <?php echo $totalSibling; ?></th>
               </tr>
             </table>
             <?php echo \yii\widgets\LinkPager::widget(['pagination' => $pages]); ?>
             <?php Pjax::end() ?>
           </div>
           </div>