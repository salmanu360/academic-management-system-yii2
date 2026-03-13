<style type="text/css">
  *{ margin:0; padding:0;}
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
<?php $studentTable=\app\models\StudentInfo::find()->where(['user_id'=>$stu_id])->one(); ?>
<h3 style='text-align:center'>Promotion Details of <?= Yii::$app->common->getName($studentTable->user_id); ?> <?= ($studentTable->gender_type== '0')? 'D/O' : 'S/O' ?> <?= Yii::$app->common->getParentName($studentTable->stu_id);?></h3>
            <table class="table table-striped">
            <thead>
              <tr>
                <th>SR.</th>
                <th>Previous Class</th>
                <th>Previous Section</th>
                <th>Promoted Class</th>
                <th>Promoted Section</th>
                <th>Promoted Date</th>
              </tr>
            </thead>
            <tbody>
            <?php 
              $i=0;
              //echo '<pre>';print_r($promotedData);die;
              foreach ($promotedData as $promoted) {
              	$studentTable=\app\models\StudentInfo::find()->where(['stu_id'=>$promoted->fk_stu_id])->one();
              	//echo $studentTable->user_id;
                $i++;
               ?>
              <tr>
                <td><?= $i; ?></td>
                <td><?= $promoted->classCurrent->title ?></td>
                <td><?= $promoted->section->title ?></td>
                <td><?= $promoted->class->title ?></td>
                <td><?= $promoted->sectionCurrent->title ?></td>
                <td><?= $promoted->promoted_date ?></td>

              </tr>
              <?php } ?>
            </tbody>
          </table>