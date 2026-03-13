<?php
use yii\helpers\Html; 
use yii\helpers\Url; 
use yii\widgets\ActiveForm; 

/* @var $this yii\web\View */ 
/* @var $model app\models\FeeSubmission */ 
/* @var $form yii\widgets\ActiveForm */ 
?> 
<?php if (Yii::$app->session->hasFlash('warning')): ?>
    <div class="alert alert-warning alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?= Yii::$app->session->getFlash('warning') ?>
    </div>
<?php endif; ?>
<div class="fee-submission-form"> 

    <?php
     $form = ActiveForm::begin(['action'=>Url::to(['update-fee','id'=>$model->id])]); ?> 


<div class="panel panel-info panel-body">
    <div class="row">
     <div class="col-md-6">
         <?php $feeArrears=\app\models\FeeArears::findOne(['stu_id'=>$model->stu_id,'fee_head_id'=>$model->fee_head_id,'status'=>1]);
      $arears=(!empty($feeArrears->arears)?$feeArrears->arears:'0');
     if($arears > 0){
     echo '<label>Arears= Rs.'.(!empty($feeArrears->arears)?$feeArrears->arears:'0') .'</label> ';
     ?>
     </div>
    </div>
    <div class="row">
        <div class="col-md-8">
    <label>Last Fee: Rs. 
    <?php echo $model->head_recv_amount ?></label><br>
    <?= $form->field($model, 'head_recv_amount')->hiddenInput()->label(false) ?>
    <label for="">Enter Fee To Add</label>
    <input type="text" class="form-control" name="newFee"><br>
    
    <?= $form->field($model, 'stu_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'fee_head_id')->hiddenInput()->label(false) ?>
        </div>
    </div>
          <div class="form-group"> 
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?> 
        </div> 
    <?php }else{
        echo '<div class="alert alert-danger col-md-12">You cannot add Fee because this student contain no Arear.</div>';
    }?>
    
    <?php ActiveForm::end(); ?> 
     
</div> 
</div>