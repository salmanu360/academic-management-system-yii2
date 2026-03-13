<?php 
use app\models\FeeSubmission;
if (isset($_GET['year'])){?>
<style type="text/css">
  *{ margin-left:0; padding:0;}
  th, tr, td  {
    border:0.3px solid black;
    padding:10px;
    font-size:0.9em;
  }
  tr:nth-child(even){background-color: #f2f2f2}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<?php $studentInfo=\app\models\StudentInfo::find()->where(['class_id'=>$class_id])->one(); ?>
<h3 style='text-align:center'>Total Fee Receive Detail of Class <?php echo Yii::$app->common->getCGSName($class_id,$group_id,$section_id) ?> in <?php echo $year;?>
</h3>
<?php }else{
  ?>
 <a href="<?=\yii\helpers\Url::to(['reports/yearly-classwise-feereport-pdf/','year'=>$year,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id]) ?>" name="Generate Report" class="btn btn-primary pull-right">Generate Report</a>
 
<?php }

$years = [4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec',1=>'Jan',2=>'Feb',3=>'Mar'];
/*$years = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];*/
// $count_year_available = FeeSubmission::find()->where(['like','from_date',$year])->count();

$yearPlusone=$year+1;
$count_year_available = FeeSubmission::find()->where(['like','from_date',$year])->orWhere(['like','from_date',$yearPlusone])->count();
 
 ?>

<div class="col-md-12">
   <br/>
   <?php

   if($count_year_available >0){
    ?>
    <div class="table-responsive">
    <table class="table table-bordered" align="center" style="padding-top: -10px">
      <thead>
          <tr class="success">
            <th>Student Name</th>
            <?php
            foreach ($years as $key => $mon) {
              ?>
              <th><?=$mon?></th>
              <?php
            }
            ?>
          </tr>
      </thead>
      <tbody>
          <?php
          $totalAmount=0;
          foreach ($students as $key => $student_details) {
            $student_id = $student_details['stu_id'];
            $student_name = Yii::$app->common->getName($student_details['user_id']);

            ?>
            <tr>
              <td><?=$student_name?></td>
              <?php

              foreach ($years as $key => $month) { 
                if($key >=1 && $key<=3){
                  // $yearNext = date('Y', strtotime('+1 year', strtotime($year)) );
                   $yearNext = $year+1;
                  //$yearNext = $year; // for start with january
                    $year_month = $yearNext  .'-'.sprintf("%02d", $key); 
                }else{
                    $year_month = $year.'-'.sprintf("%02d", $key); 
                } 
               // echo $year_month;
                //echo '<br>';
                //continue;
                $query = FeeSubmission::find()
                ->select('sum(head_recv_amount) total_amount_receive,transport_amount,hostel_amount,year_month_interval')
                ->where(['stu_id'=>intval($student_id),'from_date'=>$year_month])->asArray()->one();
                $where="from_date like '".$year_month."%' and stu_id=".intval($student_id);
                $fee_arrears_rcv=\app\models\FeeArrearsRcv::find()->where($where)->sum('amount');
                $month_count = explode(',',$query['year_month_interval']);
                
                 
                  if($query['total_amount_receive']>0){
                    $total= $query['total_amount_receive']+ $query['transport_amount']+$query['hostel_amount']+$fee_arrears_rcv;
                    $total_months='';
                    if(count($month_count)>1){
                      foreach ($month_count as $key => $monthval) {
                        $montexplode= explode('-', $monthval);
                        /*if($montexplode[0]==$year){

                        }*/
                        $total_months .= $years[(int)$montexplode[1]].',';
                         
                      }
                      $total .= '<br/><strong>('.rtrim($total_months,",").')</strong>';
                    }else{
                      $total .='';
                    }
                    $totalAmount=intval($totalAmount)+intval($total);
                    echo  '<td>Rs.'.$total.'</td>';
                  }else{
                    echo  "<td></td>";
                  } 
                   
                  ?> 
                <?php
              }
              ?>
            </tr>
            <?php
          }
          ?> 
        <tr>
          <th>Total</th>
          <th colspan="12">Rs. <?php echo $totalAmount; ?></th>
        </tr>
      </tbody>
    </table> 
    </div>
    <?php
   }else{
    ?>
    <div class="alert alert-danger">
       No Records Found.
    </div>
    <?php
   }
   ?>
 
</div>
