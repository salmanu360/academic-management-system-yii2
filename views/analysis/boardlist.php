<style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:0.3px solid black;
    padding:5px;
    font-size:0.7em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
  .first{ min-width: 40px; width: 40px; }
</style>
<h2 style="text-align: center;padding-top: -50px; font-weight:700; color:#000000; text-transform:capitalize;">
  BOARD OF INTERMEDIATE AND SECONDARY EDUCATION MALAKAND
  </h2>
  <p style="padding-top: -15px;text-align: center;">Enroled Student For Class <?php echo Yii::$app->common->getCGSName($class_val,$groupval,$sectionval) ?></p>
  <p style="padding-top: -15px;text-align: center;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </p>
<table class="table table-striped" width="100%" style="padding-top: -10px">
              <thead>
                <tr class="info">
                  <th width="10">S.No.</th>
                  <th width="80">Enrol No.</th>
                  <th>Name</th>
                  <th>Father Name</th>
                  <th>Gender</th>
                  <th>Class</th>    
                  <th>Date of Birth</th>   
                  <th>Add Date</th> 
                  <th>Remarks</th> 
                </tr>
              </thead>
              <tbody>
                <?php
                $i=0; 
                foreach ($studentDetails as $studentValue) {
                  $i++;
                  ?>
                   <tr>
                    <td><?=$i ?></td>
                   <td></td>
                   <td><?php echo Yii::$app->common->getName($studentValue->user_id)?></td>
                   <td><?php echo Yii::$app->common->getParentName($studentValue->stu_id)?></td>
                   <td><?php echo ($studentValue->gender_type == 1)?'Male':'Female' ?></td>
                   <td><?php echo Yii::$app->common->getCGSName($class_val,$groupval,$sectionval) ?></td>
                   <td><?php echo $studentValue->dob ?></td>
                   <td><?php echo date('d-m-Y',strtotime($studentValue->registration_date)) ?></td>
                   <td></td>
                  </tr>
                  <?php } ?> 
                </tbody>
              </table>