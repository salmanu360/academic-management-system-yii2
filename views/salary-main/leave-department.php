<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\RefDepartment;
use app\models\User;
use yii\helpers\ArrayHelper;
?>
<title>Employee Salary Slip</title>

<section class="invoice">
<div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-file-text"></i> Generate PaySLip.
            <small class="pull-right">Date: <?php echo date("d M, Y") ?></small>
          </h2>
        </div>
        </div>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
	<div class="col-md-6">
		<?php

    $department_array = ArrayHelper::map(\app\models\RefDepartment::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'department_type_id', 'Title');

            echo $form->field($model, 'department')->widget(Select2::classname(), [
                'data' => $department_array,
                'options' => ['placeholder' => 'Select Department ...','id'=>'departmentSalary','data-url'=>\yii\helpers\Url::to(['salary-main/get-employee'])],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Select Department');

     ?>
	</div>
</div>
<?php ActiveForm::end(); ?>
<div class="row getEmployees"></div>
