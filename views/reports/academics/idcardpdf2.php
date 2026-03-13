<div class="container" style="background-image: url('<?php echo Yii::$app->request->baseUrl?>/img/bg.png'); background-size: 50% 82%;">
	<div class="columnLeft">
		<div style="width: 100%; height: 16px;  margin-left: 20px; font-size: 12px; margin-top: 20px;"> Student name </div>
		<div style="width: 80%; height: 16px;  margin-left: 20px; font-size: 12px;">Father/Guardian</div>
		<div style="width: 80%; height: 16px;  margin-left: 20px; font-size: 12px;">Gender </div>
		<div style="width: 80%; height: 16px;  margin-left: 20px; font-size: 12px;">DOB </div>
		<div style="width: 80%; height: 16px;  margin-left: 20px; font-size: 12px;">Registration No </div>
		<div style="width: 80%; height: 16px;  margin-left: 20px; font-size: 12px;">Blood group </div>
		<div style="width: 80%; height: 16px;  margin-left: 20px; font-size: 12px;">Date of issue</div>
		
	</div>


	<div class="columnCentre">
		<div style="width: 100%; height: 16px;   font-size: 12px; margin-top: 20px;">  : <?php echo strtoupper(Yii::$app->common->getName($student->user_id)) ?></div>
		<div style="width: 80%; height: 16px;   font-size: 12px;"> :<?php echo $student->registration_date ?></div>
		<div style="width: 80%; height: 16px;   font-size: 12px;"> : Female</div>
		<div style="width: 80%; height: 16px;   font-size: 12px;"> : <?php echo $student->dob ?></div>
		<div style="width: 80%; height: 16px;   font-size: 12px;"> : <?php echo strtoupper(Yii::$app->common->getUserName($student->user_id)) ?></div>
		<div style="width: 80%; height: 16px;   font-size: 12px;"> : AB+</div>
		<div style="width: 80%; height: 16px;   font-size: 12px;"> : 01/03/2017</div>
		
	</div>
	<div class="columnRight">
		<div style="width: 10%; float: left;"><div class="vl"></div></div>
		<div style="width: 90%; float: left; margin-top: 0px;">
			<div style="width: 80%; height: 60px;  margin-left: 40px; margin-top: 40px; padding-top: 5px;">
				<div style="width: 20%; float: left; height:60px; background-size: 60% 40%; background-image: url('<?php echo Yii::$app->request->baseUrl?>/img/pointer.png'); background-position:center; background-repeat: no-repeat;">	
				</div>
				<div style="width: 80%; float: left; height: 60px; font-size: 12px; margin-top: 14px;">
					<?= strtoupper(Yii::$app->common->getBranchDetail()->address) ?>
				</div>
			</div>
			<div style="width: 80%; height: 70px;  margin-left: 40px; ">
				<div style="width: 20%; float: left; height: 60px; padding-bottom:  0px; padding-top: -25px; background-size: 60% 50%;  background-image: url('<?php echo Yii::$app->request->baseUrl?>/img/phone.png'); background-position:center; background-repeat: no-repeat;">
				</div>
				<div style="width: 80%; float: left; height: 60px; font-size: 12px; margin-top: 0px;">
					+92 1234567 <br>   +921234562
				</div>
			</div>
		</div>
	</div>

	<div style="  height: 20px; width: 100%; float: left; margin-top: -50px; margin-bottom: 0px;"><h2 style="text-align: center; color: #ba574c; font-size: 18px; ">Instructions for students</h2></div>

	<div style="width: 20%; float: left; height: 20px;"></div>
	<div class="box">
		<div style="width: 100%;   margin-top: 5px; font-size: 11px; margin-left: 15px;">1. Students must display this card while in school</div>
		<div style="width: 100%;   font-size: 11px; margin-left: 15px;" >2. Student must keep this personally in safe custody</div>
		<div style="width: 100%;   font-size: 11px; margin-bottom:  5px; margin-left: 15px;">3. This card is non transferable</div>
	</div>
	<div style="width: 20%; float: right; height: 20px;"></div>
</div>





  <div class="portions">
  	<div class="portionLeft"></div>
  	<div class="portionRight"></div>

  </div>

<div style="width: 500px; height: 20px; background-color: #0a1529; " ><p class="ownership" >This card is property of <?= strtoupper(Yii::$app->common->getBranchDetail()->address) ?>. If found, please return immediately</p></div>  
