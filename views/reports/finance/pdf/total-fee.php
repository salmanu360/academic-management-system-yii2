<style type="text/css">
*{ margin-left:0; padding:0;}
th, tr, td  {
  border:1px solid black;
  padding:10px;
  font-size:1.5em;
}

tr:nth-child(even){background-color: #f2f2f2}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h3 style='text-align:center'>Total Fee</h3>
<table class="table table-bordered" align="center">
  <thead>
   <tr style="background: #3c8dbc">
    <th>SR</th>
    <th>Class</th>
    <th>Total Students</th>
    <th>Fee</th>
  </tr>
</thead>
<tbody>
  <?php
  $i=1; 
  $total=0;
  foreach ($refClass as $refClassvalue) {
   $feegroup=\app\models\FeeGroup::find()->where(['fk_class_id'=>$refClassvalue->class_id,'fk_branch_id'=>yii::$app->common->getBranch(),'is_active'=>'yes'])->one();
   $studentTable=\app\models\StudentInfo::find()->where(['class_id'=>$refClassvalue->class_id,'is_active'=>1,'fk_branch_id'=>yii::$app->common->getBranch()])->count(); 
   $total=$total+$feegroup['amount'] * $studentTable;

   ?>
   <tr>
    <td><?php echo $i?></td>
    <td><?php echo $refClassvalue->title;?></td>
    <td><?php echo $studentTable;?></td>
    <td><?php echo $feegroup['amount'] * $studentTable;?></td>
  </tr>
  <?php
  $i++;
} 
$discountAllSum=\app\models\FeePlan::find()->where(['branch_id'=>yii::$app->common->getBranch(),'status'=>1])->sum('discount');
?>
<tr>
  <td></td>
  <th>Total</th>
  <th></th>
  <th><?php echo $total;?></th>
</tr>
<tr>
  <td></td>
  <th>Total Discount</th>
  <th></th>
  <th><?php echo $discountAllSum;?></th>
</tr>
<tr>
  <td></td>
  <th>Grand Total</th>
  <th></th>
  <th><?php echo $total-$discountAllSum;?></th>
</tr>
</tbody>
</table>