<?php 
use yii\helpers\Url;
use kartik\date\DatePicker;
$this->title = 'Receivable Reports';
?>
<div class="row">
	<div class="col-md-11">
		<a class="btn btn-block btn-social btn-facebook">
			<i class="fa fa-bar-chart-o"></i> Receivable Reports
		</a>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-11">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_1" data-toggle="tab">Today Receivable</a></li>
				<li><a href="#tab_2" data-toggle="tab">Current Month Receivable</a></li>
				<li><a href="#tab_3" data-toggle="tab">Date Receivable</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<?php if(count($todayRecievalble) > 0){ ?>
					<a style="margin-top: -10px" href="<?= Url::to(['today-receivable-pdf']) ?>" class="btn btn-primary btn-sm pull-right">
						<i class="fa fa-download"> Download Report</i></a>
						<table class="table table-striped">
							<thead>
								<tr class="info">
									<th>SR#</th>
									<th>Receivable Category</th>
									<th>Class</th>
									<th>Name</th>    
									<th>Contact</th>   
									<th>Amount</th> 
								</tr>
							</thead>
							<tbody>
								<?php 
								$count=0;
								$sum=0;
								foreach ($todayRecievalble as $todayRecievalble) { 
									$sum=$sum+$todayRecievalble->amount;
									$count++;
									?>
									<tr>
										<td><?= $count; ?></td>
										<td><?= $todayRecievalble->receivablecategory->title;?></td>
										<td><?= $todayRecievalble->class->title;?></td>
										<td><?= $todayRecievalble->name;?></td>
										<td><?= $todayRecievalble->contact;?></td>
										<td><?= $todayRecievalble->amount;?></td>
									</tr>
									<?php } ?> 
									<tr>
										<td></td>
										<th>Grand Total</th>
										<td></td>
										<td></td>
										<td></td>
										<th> Rs:<?php echo $sum; ?></th>
									</tr>  
								</tbody>
							</table>
							<?php }else{ ?>
							<div class='row'><div class='col-md-1 col-sm-1'></div><div class='Alert alert-danger col-sm-3'><strong>No Receivable Found Today!</strong></div> </div>
							<?php } ?>
						</div>
						<!-- end of tab_1 & start of tab_2 -->
						<div class="tab-pane" id="tab_2">
							<?php if(count($currentMonth) > 0){ ?>
							<a style="margin-top: -10px" href="<?= Url::to(['currentmonth-receivable-pdf']) ?>" class="btn btn-primary btn-sm pull-right">
								<i class="fa fa-download"> Download Report</i></a>
								<table class="table table-striped">
									<thead>
										<tr class="info">
											<th>SR#</th>
											<th>Receivable Category</th>
											<th>Class</th>
											<th>Name</th>    
											<th>Contact</th>   
											<th>Amount</th> 
										</tr>
									</thead>
									<tbody>
										<?php 
										$count=0;
										$sum=0;
										foreach ($currentMonth as $currentMonth) { 
											$sum=$sum+$currentMonth->amount;
											$count++;
											?>
											<tr>
												<td><?= $count; ?></td>
												<td><?= $currentMonth->receivablecategory->title;?></td>
												<td><?= $currentMonth->class->title;?></td>
												<td><?= $currentMonth->name;?></td>
												<td><?= $currentMonth->contact;?></td>
												<td><?= $currentMonth->amount;?></td>
											</tr>
											<?php } ?> 
											<tr>
												<td></td>
												<th>Grand Total</th>
												<td></td>
												<td></td>
												<td></td>
												<th> Rs:<?php echo $sum; ?></th>
											</tr>  
										</tbody>
									</table>
									<?php }else{ ?>
									<div class='row'><div class='col-md-4 col-sm-4'></div><div class='Alert alert-danger col-sm-4'><strong>No Receivable Found This month..!</strong></div> </div>
									<?php } ?>
								</div>
								<!-- end of tab_2 & start of tab_3 -->
								<div class="tab-pane" id="tab_3">
									<div class="row">
										<div class="col-md-3">
											<?php 
											echo '<label>Start Date:</label>';
											echo DatePicker::widget([
												'name' => 'startdate', 
												'value' => date('d-m-Y'),
												'options' => ['placeholder' => ' ','id'=>'startdateReceivablee'],
												'pluginOptions' => [
													'format' => 'dd-m-yyyy',
													'todayHighlight' => true,
													'autoclose'=>true,
												]
												]);?>
											</div>
											<div class="col-md-3">
												<?php echo '<label>End Date:</label>';
												echo DatePicker::widget([
													'name' => 'enddate', 
													'value' => date('d-m-Y'),
													'options' => ['placeholder' => ' ','id'=>'enddateReceivable'],
													'pluginOptions' => [
														'format' => 'dd-m-yyyy',
														'todayHighlight' => true,
														'autoclose'=>true,
													]
													]); ?>
												</div>
												<div class="col-md-1">
													<div style="height: 23px"></div>
													<input type="submit" id="dateReceivableReport" name="submits" class="btn btn-primary" data-url=<?php echo Url::to(['receivable/receivable-date-report']) ?>  />
												</div>
											</div><br />
											<div class="row">
												<div class="col-md-12" id="showDateReceivable"></div>
											</div>
										</div>
										<!-- end of tab_3 & start of tab_4 -->
									</div>
								</div>
							</div>
						</div>