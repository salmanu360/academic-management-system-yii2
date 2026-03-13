<?php 
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
$this->title='Calendar'; 
 ?>
 <section class="invoice">
<div class="exam-form filters_head"> 
        <?php $form = ActiveForm::begin(); ?> 
        <div class="row">
           <div class="col-sm-9">
            <div class="col-sm-4 fh_item">
            	<?= $form->field($model, 'fk_class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select Class ...']); ?>
            </div>
            <div class="col-sm-4 fh_item">
            	 <?php
					// Dependent Dropdown
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
            <div class="col-sm-4 fh_item">
            	<input type="hidden" id="subject-url" value="<?=Url::to(['/student/attcal'])?>">
                <?php
                // Dependent Dropdown
                echo $form->field($model, 'fk_section_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'section-id'],
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
           </div> 
        </div>   
        <?php ActiveForm::end(); ?> 
        </div>
        </section>

        <div class="row">
            <div class="col-sm-12">
                <div  id="subject-details">
            <div id="subject-inner"></div>
        </div>
            </div>
        </div>
