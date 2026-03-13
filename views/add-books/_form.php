<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;



/* @var $this yii\web\View */
/* @var $model app\models\AddBooks */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="add-books-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'book_isbn_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'book_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'edition')->textInput(['maxlength' => true]) ?>

    <?php $getLibraryCategory_array = ArrayHelper::map(\app\models\AddlibraryCategory::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'id', 'category_name');
                        echo $form->field($model, 'addlibrary_category_id')->widget(Select2::classname(), [
                            'data' => $getLibraryCategory_array,
                            'options' => ['placeholder' => 'Select Category ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>

    <?= $form->field($model, 'publisher')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_of_copies')->textInput() ?>

    <?= $form->field($model, 'rack_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shelf_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'book_position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'book_cost')->textInput() ?>

    <?= $form->field($model, 'language')->textInput() ?>

    <?= $form->field($model, 'book_condition')->dropDownList([ 'As New' => 'As New', 'Fine' => 'Fine', 'Very Good' => 'Very Good', 'Good' => 'Good', 'Fair' => 'Fair', 'Poor' => 'Poor', 'Missing' => 'Missing', 'Lost' => 'Lost', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false); ?>

    <?php if ($model->isNewRecord !=1) {
        echo $form->field($model, 'status')->dropDownList([ 'active' => 'Active', 'inactive' => 'Inactive', ], ['prompt' => '']);
    }else{
        echo $form->field($model, 'status')->hiddenInput(['value'=>'active'])->label(false);
        } ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
