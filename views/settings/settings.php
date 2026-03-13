<?php
use yii\helpers\Html;
use app\widgets\Alert;
$this->registerCssFile(Yii::getAlias('@web').'/css/site.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->title = 'Main Settings';
?>
<?=Alert::widget();?> 
<section class="invoice">
<div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-wrench"></i> <?= Html::encode($this->title) ?>
          </h2>
        </div>
      </div>
<div class="free-generator content_col exam-form grey-form"> 
   	<div class="form-center shade fee-gen">   
        <?= $this->render('_form', [
            'model' => $model,
            'modelHead' => $modelHead,
            'modelFeeHeads' => $modelFeeHeads,
        ]) ?>
    </div>
</div>
</section>
