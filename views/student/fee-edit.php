<?php

use yii\helpers\Html; 
use yii\widgets\ActiveForm; 
$this->title='Fee';
echo $this->render('step_menu');
?> 

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>
<div class="box-primary">
  <div class="box box-body">
<?php $form = ActiveForm::begin(); ?> 
    <table class="table table-striped">
         <thead>
            <tr class="info">
                <td>Fee Head</td>
                <td>Discount</td>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($fee_old as $key => $value) {
                $fee_head_name=\app\models\FeeHead::find()->where(['id'=>$value->fee_head_id])->one();
                ?>
            <tr>
                <td><h5><?php echo strtoupper($fee_head_name->title)?></h5></td>
                <td><?= $form->field($model, 'discount['.$value->id.']')->textInput(['value'=>$value->discount])->label(false) ?>

                </td>
                </td>
    <?= $form->field($model, 'branch_id')->hiddenInput(['value'=>Yii::$app->common->getBranch()])->label(false) ?>
            </tr>
            <?php } ?>
         </tbody>
     </table>
    

    <div class="form-group"> 
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?> 
    </div> 
    <?php ActiveForm::end();?> 

</div> 
</div> 