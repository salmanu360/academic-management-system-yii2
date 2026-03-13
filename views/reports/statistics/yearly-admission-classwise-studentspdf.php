<?php use yii\helpers\Url;
$class_name=\app\models\RefClass::find()->where(['class_id'=>$classid,'fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->one();
?>  
<style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:1px solid black;
    padding:7px;
    font-size:0.8em;
  }
table{width: 100%}
  tr:nth-child(even){background-color: #f2f2f2}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h4 style='text-align:center'> Student Enrolled in <?= $class_name->title?> (<?php echo $years ?>)
</h4> 
<table class="table table-striped">
                          <thead>
                          <tr>
                            <th>SR</th>
                            <th>Registeration No.</th>
                            <th>Name</th>
                            <th>Father Name</th>
                            <th>Class</th>
                            <th>Registeration Date</th>
                           </tr>
                           </thead>
                           <tbody>
                            <?php
                            $count=0;
                            $sum=0;
                           // echo '<pre>';print_r($year);die;
                             foreach ($yearAdmissionstudents as $years) {
                              $count++;
                              ?>
                            <tr>
                              <td><?php echo $count; ?></td>
                              <td><?php echo Yii::$app->common->getUserName($years->user_id); ?></td>
                              <td><?php echo Yii::$app->common->getName($years->user_id); ?></td>
                              <td><?php if(!empty($years['stu_id'])){
                                 echo Yii::$app->common->getParentName($years['stu_id']);
                              }else{
                                echo 'N/A';
                              }  ?></td>
                              <td><?php echo Yii::$app->common->getCGSName($years->class_id,$years->group_id,$years->section_id); ?></td>
                              <td><?php echo $years->registration_date ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                     </table>