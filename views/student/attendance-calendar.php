<?php 
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\date\DatePicker; 
$this->title='Calendar'; 
 ?>
 <section class="invoice">
<div class="exam-form filters_head"> 
        <?php $form = ActiveForm::begin(); ?> 
        <div class="row">
           <div class="col-sm-12">
            <div class="col-sm-2">
            	<?= $form->field($model, 'fk_class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select Class ...']); ?>
            </div>
            <div class="col-sm-2">
            	 <?php
					echo $form->field($model, 'fk_group_id')->widget(DepDrop::classname(), [
						'options' => ['id'=>'group-id'],
						'pluginOptions'=>[
							'depends'=>['class-id'],
							'prompt' => 'Select Group...',
							'url' => Url::to(['/site/get-group'])
						]
					]);
				?>
            </div>
            <div class="col-sm-2">
                <?php
                echo $form->field($model, 'fk_section_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'section-ids'],
                    'pluginOptions'=>[
                        'depends'=>[
                            'group-id','class-id'
                        ],
                        'prompt' => 'Select section',
                        'url' => Url::to(['/site/get-section'])
                    ]
                ]);
                ?>
            </div>
            <div class="col-md-3">
                 <?php 
                  echo '<label>Select Month:</label>';   
                  echo DatePicker::widget([
                    'name' => 'startdate', 
                    // 'value' => date('Y-m'),
                    'options' => ['placeholder' => ' ','id'=>'monthCalendar','data-url'=>Url::to(['student/cal-show'])],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'startView'=>'year',
                        'minViewMode'=>'months',
                        'format' => 'yyyy-mm',
                        'startDate' => '-1m',
                    ]
                  ]);?>
               </div>
           </div> 
        </div>   
        <?php ActiveForm::end(); ?> 
        </div>
        </section>
        
        <div class="row">
            <div class="col-sm-12">
            <div id="subject-inner"></div>
            </div>
        </div>