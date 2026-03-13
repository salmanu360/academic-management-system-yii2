 <style type="text/css">
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
<?php $getclassname=\app\models\RefClass::find()->where(['class_id'=>$class_val])->one();?>
<h3 style='text-align:center'>Alumni Report of Class(<?=$getclassname->title ?>)</h3>
<table class="table table-striped">
  <thead>
    <tr class="info">
      <th>SR#</th>
      <th>Name</th>
      <th>Parent Name</th>    
      <th>Class</th>    
      <th>Group</th>    
      <th>Section</th>    
      <th>Parent Contact</th>    
      <th>Next School</th>    
      <th>Reason</th>    
      <th>Date</th>    
    </tr>
  </thead>
  <tbody>
    <?php 
    $count=0;
    $sum=0;
    foreach ($classname as $classname) { 
      
      $count++;
      ?>
      <tr>
       <td><?= $count; ?></td>
       <td>
         <?php 
         $getStudentId=\app\models\StudentInfo::find()->where(['stu_id'=>$classname->stu_id])->one();
         echo Yii::$app->common->getName($getStudentId->user_id);
          ?>
       </td>
       <td><?= Yii::$app->common->getParentName($classname->stu_id);?></td>
       <td><?= $classname->class->title;?></td>
       <td><?php  if($classname->group_id != ''){
                      echo $classname->group->title;

                        }else{
                          echo "N/A";
                        }?></td>
        <td><?php  if($classname->section_id != ''){
                      echo $classname->section->title;

                        }else{
                            echo "N/A";
                        }
        }?></td>
        <td><?php 
           $name=\app\models\StudentParentsInfo::find()->where(['stu_id'=>$classname->stu_id])->one();
           echo $name->contact_no;
         ?></td>
         <td><?= $classname->next_school; ?></td>
         <td><?= $classname->reason_for_leavingschool; ?></td>
         <td><?= $classname->created_date; ?></td>
     </tr>
       
  </tbody>
</table>