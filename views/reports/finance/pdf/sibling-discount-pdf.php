 <style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:0.3px solid black;
    padding:5px;
    font-size:0.9em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
  #first{ min-width: 15px; width: 15px; }
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
      <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
        <?=Yii::$app->common->getBranchDetail()->address?>
    </h2>
</div>
<h3 style='text-align:center'>Sibling Discount Report</h3>
 <div class="table-responsive">
                <table>
                  <tr style="background: #00a65a;color: white">
                    <td>Sr.</td>
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
                      ini_set('max_execution_time', 300);
                       $settings = Yii::$app->common->getBranchSettings();
                      $getParents=\app\models\StudentParentsInfo::find()->where(['stu_id'=>$studentCount->stu_id])->one();
                       $getHeadSiblings=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch()])->one();
                       $getFeeDetails = \app\models\FeeGroup::find()
                       ->where([
                           'fk_branch_id'  =>Yii::$app->common->getBranch(),
                           'fk_class_id'   => $studentCount->class_id,
                           'fk_fee_head_id'   => $getHeadSiblings['id'],
                           'fk_group_id'   => ($studentCount->group_id)?$studentCount->group_id:null,
                       ])->one();
                      ?>
                  <tr>
                    <td><?=$i; ?></td>
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
                 <th colspan="2">Rs. <?php echo $totalSibling; ?></th>
               </tr>
             </table>
           </div>