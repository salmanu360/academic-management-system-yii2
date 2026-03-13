<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\widgets\Alert;
use yii\widgets\Pjax;
use app\models\StudentInfo;
use app\models\RefClass;
$this->title = 'Find Students';
$this->registerCssFile(Yii::getAlias('@web').'/css/site.css',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<section class="invoice">
      <div class="content_col student-search"> 
<?= Alert::widget() ?>
<div class="mid_mini shade fee-gen ">
  <div class="ss_head">
    <div class="row">
    <div class="col-sm-4 search_by">
      <div class="input-group-btn">
        <?php
        $a= [''=>'Search By','name' => 'First Name', 'contact' => 'Contact No.', 'reg' => 'Registration No.','class'=>Yii::t('app','Class'),'alumni' => 'Alumni','overall'=>yii::t('app','overl all students')];
        echo Html::DropDownList('searcy_by',null,$a,['class'=>'btn green-btn searchBy']); 
        ?>
      </div>
    </div>
    <div class="col-sm-4"> 
      <div class="input-group-btn">
        <?php
        $status = [1=>'Active',0 =>'Inactive'];
        echo Html::radioList('status', 1, $status, [
            'item' => function($index, $label, $name, $checked, $value) {
              return '<label class="btn btn-white">' . Html::radio($name, $checked, ['value'  => $value, 'autocomplete'=>'off','id'=>'search-status']) . $label . '</label>';
            }
        ]);
        ?>
    </div>
  </div>
  </div>
    </div> 
    <div class="row">
      <div class="col-md-12 search_sr" style="display: none">  
        <label for=""><input type="checkbox" name="name" value="Name" class="classfullname" checked="checked"> Name</label>
        
         <label for=""><input type="checkbox" name="name" value="Name" class="classparentname" checked="checked">
       Parent Name</label>

         <label for=""><input type="checkbox" name="name" value="regno" class="regno" checked="checked">
       Reg No.</label>

        <label for=""><input type="checkbox" name="name" value="class" class="classclass" checked="checked"> <?=Yii::t('app','Class')?></label>

        <label for=""><input type="checkbox" name="name" value="classgroup" class="classgroup" checked="checked">
        <?=Yii::t('app','Group')?></label>
        <label for=""><input type="checkbox" class="sectionClass" checked="checked">
            <?=Yii::t('app','Section')?></label>

        <label for=""><input type="checkbox" name="name" value="dob" class="dob" checked="checked">
            <?=Yii::t('app','DOB')?></label>

        <label for=""><input type="checkbox" name="name" value="address" class="addressclass" checked="checked">
        Address</label>
        <label for=""><input type="checkbox" name="name" value="contactNo" class="contactNo" checked="checked">
            <?=Yii::t('app','Contact')?></label>
        </div> 
        </div> 
        <div class="row">
  <div class="input-group pad_15">  
    <div class="inn_pt">
      <div class="col-md-6 col-sm-6">
      <input type="text" name="search" class="form-control passVal">
    <input type="hidden" class="form-control hiddenPassvalue">
    <?php  $form = ActiveForm::begin(); ?>
    <div class="showclass" style="display:none">
      <?php 
      $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');
      echo Html::DropDownList('leave_type',null,$class_array,['prompt'=>'Select '.Yii::t('app','Class'),'class'=>'form-control classval']) ;
      $form = ActiveForm::end(); 
       ?> 
    </div>
  </div>

    <div class="col-md-3 col-sm-3">
      
    <input type="submit" name="submit1" value="Search" class="btn btn-primary search searchShow" data-url=<?php echo \yii\helpers\Url::to(['student/get-search'])?>>
    </div>
    </div> 
  </div>
</div>
</div>
<div class="col-md-12 no-padding" id="subject-details">
  <div id="displaysearch"></div>
</div>
    </section>