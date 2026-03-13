<?php 
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\RefClass;
use app\models\RefGroup;
$this->title = 'Session Fee';
$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
$this->registerCssFile(Yii::getAlias('@web').'/css/site.css',['depends' => [yii\web\JqueryAsset::className()]]);
?> 
<div class="panel panel-default panel-body"> 
  <div class="row">
  <div class="col-md-12">
    <strong style="color:red">Session Fee Ledger</strong>
    <a class="btn btn-danger pull-right" href="<?php echo Url::to(['accounts']) ?>">Back</a>
    
  </div></div>
<div class="row">
                  <div class="col-md-3 col-sm-3">
                    <label for="class">Class</label>
                  <?= Html::dropDownList('ref_class', null,
                      ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'getdataclass','data-url'=>Url::to(['reports/class-data'])]) ?>
                    
                  </div>
               <div class="col-md-3 col-sm-3">
               <label for="group">Group</label>
               <select name="" id="classdatagroup" class="form-control" data-url="<?= Url::to(['reports/group-data']);?>">
               </select>
                </div>
                <div class="col-md-3 col-sm-3">
            <label for="section">Section</label>
              <select name="" id="classdatasection" class="form-control" data-url="<?= Url::to(['reports/get-session-fee']);?>"></select>
            </div>
            </div>
            </div>

<br/>
               <div class="row">
                <div class="col-md-12" id="counStudent">
                
                </div>
               </div>