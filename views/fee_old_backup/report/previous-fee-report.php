<?php 
 $year=date('Y',strtotime($settings->current_session_start));
 $sessionEnd=date('Y',strtotime($settings->current_session_end));
$years = [4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec',1=>'Jan',2=>'Feb',3=>'Mar'];
$yearPlusone=$year+1;
$count_year_available = \app\models\FeeSubmission::find()->where(['like','from_date',$year])->orWhere(['like','from_date',$yearPlusone])->count();
$student_details=\app\models\StudentInfo::find()->where(['stu_id'=>$student_id])->one();
 ?>

 <div class="col-md-12">
   <br/>
<?php

   if($count_year_available >0){
    ?>
    <div class="table-responsive" style="margin-top: -20px">
    <table class="table table-bordered" align="center" style="padding-top: -10px">
      <thead>
          <tr class="success">
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
            $student_name = Yii::$app->common->getName($student_details['user_id']);

            ?>
            <tr>
             
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
                $query = \app\models\FeeSubmission::find()
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
                    $totalAmount=$totalAmount+$total;
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
        
          ?> 
        <tr>
          <th>Total</th>
          <th colspan="12">Rs. <?php echo $totalAmount; ?></th>
        </tr>
      </tbody>
    </table> 
    </div>
     </div>
    <?php
   }?>
</div>