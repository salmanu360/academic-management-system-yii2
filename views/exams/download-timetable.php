 <?php use yii\helpers\Html;
 use yii\widgets\ActiveForm;
 use yii\helpers\Url;?>
 	<style type="text/css">
 	*{ margin:0; padding:0;}
 	table{width:100%;}
 	.centertext{text-align: center;}
 	th, tr, td  {
 		border:1px solid black;
 		padding:5px;
 		font-size:1em;
 	}
 </style>
 <div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
 	<h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
 		<?=Yii::$app->common->getBranchDetail()->address?>
 	</h2>
 </div>
 <h3 style='text-align:center'><?= strtoupper( 'Schedule of '. $examType->type .'(Class '. yii::$app->common->getCGName($class_id,$group_id) .')') ?> 
</h3>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>SUBJECT</th>
						<th>TOTAL MARKS</th>
						<th>PASSING MARKS</th>
						<th>DATE & TIME</th>
					</tr>
				</thead>
				<tbody>
					<?php $i=0;foreach ($examData as $key => $exam) { $i++;?>   
						<tr>
							<td class="centertext"><?= $i; ?></td>
							<td><?= strtoupper($exam->fkSubject->title) ?></td>
							<td class="centertext"><?= $exam->total_marks?></td>
							<td class="centertext"><?= $exam->passing_marks ?></td>
							<td><?= date('d-M-Y H:i:s',strtotime($exam->start_date))?></td>
						</tr> 
					<?php }  ?> 
				</tbody>
			</table>
		</div>
	</div>
</div>