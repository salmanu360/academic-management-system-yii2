<?php
use yii\helpers\Html; 
use yii\helpers\Url; 
use yii\widgets\ActiveForm; 
?> 
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger">Note: To add transport amount, must subtract that amount from transport arears.</div>
    </div>
</div>
<div class="fee-submission-form"> 
    <?php
     $form = ActiveForm::begin(['action'=>Url::to(['update-transport-arear','id'=>$model->id])]); ?> 
<div class="panel panel-info panel-body">
    <div class="row">
        <div class="col-md-12">
    <?= $form->field($model, 'transport_amount')->textInput() ?>
    <?= $form->field($model, 'transport_arrears')->textInput() ?>
        </div>
    </div>
          <div class="form-group"> 
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?> 
        </div> 
    
    
    <?php ActiveForm::end(); ?> 
     
</div> 
</div>