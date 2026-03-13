<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\HostelDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hostel-detail-form">

    <?php $form = ActiveForm::begin();

    $hostel = ArrayHelper::map(\app\models\Hostel::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'id', 'Name');
    $floor = ArrayHelper::map(\app\models\HostelFloor::find()->all(), 'id', 'title');
    $bed = ArrayHelper::map(\app\models\HostelBed::find()->all(), 'id', 'title');
    $room = ArrayHelper::map(\app\models\HostelRoom::find()->all(), 'id', 'title');
  //  $student = ArrayHelper::map(\app\models\User::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'fk_role_id'=>'3'])->all(), 'id', 'first_name');

  echo $form->field($model, 'fk_hostel_id')->widget(Select2::classname(), [
        'data' => $hostel,
        'options' => ['placeholder' => 'Select Hostel ...','class'=>'hostelAll','data-url'=>url::to(['student/get-hostel-floor'])],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
  if($model->isNewRecord == 1){
    $model->fk_floor_id ="";
echo $form->field($model, 'fk_floor_id')->widget(Select2::classname(), [
        //'data' => $floor,
        'options' => ['placeholder' => 'Select Hostel Floor ...','class'=>'floorAjax','data-url'=>Url::to(['student/get-floor-room'])],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
  }else{
    echo $form->field($model, 'fk_floor_id')->widget(Select2::classname(), [
        'data' => $floor,
        'options' => ['placeholder' => 'Select Hostel Floor ...','class'=>'floorAjax','data-url'=>Url::to(['student/get-floor-room'])],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
  }
  
  if($model->isNewRecord == 1){
    $model->fk_room_id ="";
      echo $form->field($model, 'fk_room_id')->widget(Select2::classname(), [
       // 'data' => $room,
        'options' => ['placeholder' => 'Select Hostel Room ...','class'=>'roomBed','data-url'=>Url::to(['student/get-bed'])],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
  }else{
    echo $form->field($model, 'fk_room_id')->widget(Select2::classname(), [
        'data' => $room,
        'options' => ['placeholder' => 'Select Hostel Room ...','class'=>'roomBed','data-url'=>Url::to(['student/get-bed'])],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
  }
  
  if($model->isNewRecord == 1){
    $model->fk_bed_id ="";
    echo $form->field($model, 'fk_bed_id')->widget(Select2::classname(), [
        //'data' => $bed,
        'options' => ['placeholder' => 'Select Room Bed ...','class'=>'beds','data-url'=>Url::to(['student/check-bed-assigned'])],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
  }else{
    echo $form->field($model, 'fk_bed_id')->widget(Select2::classname(), [
        'data' => $bed,
        'options' => ['placeholder' => 'Select Room Bed ...','class'=>'beds'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
  }
   

    echo $form->field($model, 'fk_student_id')->widget(Select2::classname(), [
        'data' => Yii::$app->common->getBranchStudents(),
        'options' => [
        'placeholder' => 'Select Student ...',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);

    


    echo $form->field($model, 'create_date')->widget(DatePicker::classname(), [
                      'options' => ['value' => date('Y-m-d')],
                     'pluginOptions' => [
                         'autoclose'=>true,
                         'format' => 'yyyy-mm-dd',
                         'todayHighlight' => true,
                     ]
                 ]);
    echo  $form->field($model, 'discount_amount')->textInput(['type'=>'number']);

    echo  $form->field($model, 'is_booked')->hiddenInput(['value'=>'1'])->label(false);

     ?>


  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn green-btn' : 'btn green-btn']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<?php
$script = <<< JS
$(document).on('change','#hosteldetail-fk_bed_id',function(){
    var bedId       = $(this).val();
    var url         = $(this).data('url');
    var thisData    = $(this);
    var stepGenError = $(this).closest('section').find('#overall-error');
    if(bedId){
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {bed_id:bedId},
            url: url,
            cache: false,
            success: function(data)
            {
                if(data.status == 1){
                    stepGenError.val(1);
                    thisData.closest('.form-group').addClass('has-error');
                    thisData.closest('.form-group').removeClass('has-success');
                    thisData.closest('.form-group').find('.help-block').html('This Bed has already taken');
                }else{
                    stepGenError.val(0);
                    thisData.closest('.form-group').addClass('has-success');
                    thisData.closest('.form-group').removeClass('has-error');
                    thisData.closest('.form-group').find('.help-block').html('');
                }
            }
        });
    }
});

JS;
$this->registerJs($script);
?>
