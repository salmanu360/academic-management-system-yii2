<?php 
if (isset($_GET['cid'])){?>
<style type="text/css">
  *{ margin-left:0; padding:0;}
  th, tr, td  {
    border:1px solid black;
    padding:8px;
    font-size:0.8em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h3 style='text-align:center'>Fee Ledger of session  <?= date('d-M-Y',strtotime($sessionStartDate)) .' - '.  date('d-M-Y',strtotime($sessionEndDate))?>
</h3>
<?php }else{?>
<div class="panel panel-default panel-body"> 

<div class="row">
	<div class="col-md-2 pull-right">
	<a href="<?=\yii\helpers\Url::to(['reports/get-session-fee/','cid'=>$classid,'gid'=>$group_id,'sid'=>$section_id]) ?>" name="Generate Report" class="btn btn-success pull-right"><i class="fa fa-download"></i> Generate Report</a>	
	</div>
</div><br>
<?php }?>
	<div class="table-responsive">
<table class="table table-bordered" align="center">
                        <thead>
                           <tr style="background: #3c8dbc">
                            <th>SR</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Roll #</th>
                            <th>Fee</th>
                            <th>Total Fee</th>
                            <th>Arrears</th>
                            <th>Total Arrears</th>
                            
                            </tr>
                         </thead>
                           <tbody>
                            <?php
                            $i=0;
                            $headAmnt=0; 
                            $totalArrears=0;
                            //echo '<pre>';print_r($query);die;
                            foreach ($studentTable as $key => $value) { 
                                $i++;
				            $query = \app\models\FeeSubmission::find()
				            ->select(['sum(head_recv_amount) as head_recv_amount','sum(transport_amount) as transport_amount','transport_arrears'])
				            ->where([
				                'stu_id'=>$value->stu_id
				            ])
				            ->andWhere(['between', 'from_date', $sessionStartDate, $sessionEndDate])
				            ->all();
				            $trasportAllArrears=\app\models\FeeSubmission::find()->where(['stu_id'=>$value->stu_id,'fee_status'=>1])->one();
				            $fee_arrears=\app\models\FeeArears::find()->where(['status'=>1,'stu_id'=>$value->stu_id])->sum('arears');
                              ?>
                              <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo Yii::$app->common->getName($value->user_id) ?></td>
                              <td><?php echo Yii::$app->common->getParentName($value->stu_id) ?></td>
                              <td><?php echo ($value->roll_no)?$value->roll_no:'N/A'; ?></td>
                              <td>
                              	<?php 
                              	$Totaltransport_arrears=0;
                              	$totalFee=0;
                              	foreach ($query as $key => $fee) {
                              		$totalArrears=$totalArrears+$trasportAllArrears->transport_arrears+$fee_arrears;

                              		$Totaltransport_arrears=$Totaltransport_arrears+$trasportAllArrears->transport_arrears;

                              		$headAmnt=$headAmnt+$fee->head_recv_amount+$fee->transport_amount;

                              		$totalFee=$totalFee+$fee->head_recv_amount+$fee->transport_amount;
                              		?>
                              		 <?php echo ($fee->head_recv_amount)?'Rs. '.$fee->head_recv_amount:'' ?>
                              		 <?php echo  ($fee->transport_amount)?'Transport Rs. '.$fee->transport_amount:'' ?>

                              		<?php echo ($trasportAllArrears->transport_arrears)?'Transport Arears: Rs. '.$trasportAllArrears->transport_arrears:'' ?>
                              <?php	} ?>
                              </td>
                              <td>Rs.<?php echo $totalFee ?></td>
                              <td><?php echo ($fee_arrears)?'Rs. '.$fee_arrears:'' ?></td>
                              <td>Rs. <?php echo $Totaltransport_arrears+$fee_arrears; ?></td>
                              </tr>
                            	
                            <?php
                            } ?>
                            <tr>
                            	<td></td>
                            	<th colspan="3">Total</th>
                            	<th>Rs. <?php echo $headAmnt; ?></th>
                            	<th colspan="2">Total Arrears</th>
                            	<th>Rs. <?php echo $totalArrears; ?></th>
                            </tr>
                          </tbody>
                          </table>
</div>
</div>