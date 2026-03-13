<?php
use yii\helpers\Url;
use yii\helpers\Html;
?> 
<div class="well" style="background: white"><strong>Update Student <?php echo $model->user->first_name.' '. $model->user->last_name;?></strong>

<a href="<?php echo Url::to(['/student'])?>" class="btn btn-primary pull-right" style="margin-left:5px">Back</a>

<a href="<?= Url::to(['download-form','id'=>Yii::$app->request->get('id')])  ?>" type="button" name="Generate Report" id="generate-employee-profile-pdf" class="btn btn-success pull-right" value="Generate Pdf"> <i class="fa fa-download"></i> Dowload Form </a>
</div>
<div class="student-info-update content_col grey-form"> 
	<h1 class="p_title"><?= Html::encode($this->title) ?></h1>
    <div class="subjects-index shade">  
		<?= $this->render('_form', [
            'model'     => $model,
            'model2'    => $model2,
            'userModel' => $userModel,
            'branch_std_counter'=>$branch_std_counter,
            'StudentEducationalHistoryInfo'=>$StudentEducationalHistoryInfo,
        ]) ?>  
    </div>
</div>
