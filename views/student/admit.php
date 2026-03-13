<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\RefCountries;
use app\models\RefProvince;
use app\models\Profession;
use app\models\RefSession;
use app\models\RefGroup;
use app\models\RefShift;
use app\models\RefClass;      
use app\models\RefSection;
use app\models\RefCities;
use app\models\Zone;
use app\models\Stop;
use app\models\RefGardianType;
use app\models\HostelFloor;
use app\models\HostelRoom;
use app\models\HostelBed;
use app\models\RefDegreeType;
use app\models\RefInstituteType;
use app\models\Hostel;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
$this->title='Official Details';
?>
<style>
	.select2-container .select2-selection--single{
		    height: 34px;
	}
</style>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>
<?php
$id=Yii::$app->request->get('id');
$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<ul class="nav nav-pills nav-justified" id="pills-tab" role="tablist">
  <li class="nav-item active">
    <a id="pills-home-tab" class="nav-link" href="#">Official Details</a>  </li>
  <li class="nav-item ">
    <a id="pills-home-tab" class="nav-link" href="<?php echo Url::to(['personel','id'=>base64_encode($id)]) ?>">Personnel Information</a>  </li>
  <li class="nav-item ">
    <a id="pills-home-tab" class="nav-link" href="/client/fayyaz/ums/admin/candidate/educational-information?id=Mg%3D%3D">Educational Information</a>  </li>
   <li class="nav-item ">
    <a id="pills-home-tab" class="nav-link" href="/client/fayyaz/ums/admin/candidate/document?id=Mg%3D%3D">Uploaded Document</a>  </li>
  <li class="nav-item ">
    <a id="pills-home-tab" class="nav-link" href="/client/fayyaz/ums/admin/candidate/admission?id=Mg%3D%3D">Admission Details</a>  </li>
</ul>
<div class="box-primary">
	<div class="box box-body">
		<div class="row">
			<div class="col-md-6">
			<?php
                  $lastAmitedStudent=\app\models\User::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'fk_role_id'=>3])->orderBy(['id'=>SORT_DESC])->limit(1)->one();
                   $where = "username LIKE 'c%'";
                  $lastCstudent=\app\models\User::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'fk_role_id'=>3])->andWhere($where)->orderBy(['id'=>SORT_DESC])->limit(1)->one();
                  $whereS = "username LIKE 's%'";
                  $lastSstudent=\app\models\User::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'fk_role_id'=>3])->andWhere($whereS)->orderBy(['id'=>SORT_DESC])->limit(1)->one();
                  
                    if(Yii::$app->common->getBranchSettings()->student_reg_type == 'auto'){?>
                    	 <?=$form->field($userModel, 'username')->textInput(['readonly'=>true,'maxlength' => true,'value'=>Yii::$app->common->getBranchDetail()->name.'-'.date("Y").'-'.($branch_std_counter+1),'id'=>'registeration','class'=>'form-control input form-control','id'=>'registeration','data-url'=>Url::to('validate-usrname')])->label(Yii::t('app','Register Number').'<span style="color:#a94442;"> *</span>')?>
                    <?php }else{?>
                    	 <span style="color: red">Last Register No. is:  (<?php echo (!empty($lastAmitedStudent->username)?$lastAmitedStudent->username:'N/A') ?>)
                       </span>
                       <span style="color:red">
                           <?php echo (!empty($lastCstudent->username)?' | '.$lastCstudent->username:'') ?>
                       </span>

                       <span style="color:red">
                           <?php echo (!empty($lastSstudent->username)?' | '.$lastSstudent->username:'') ?>
                       </span>
                        <?=$form->field($model, 'username')->textInput(['maxlength' => true,'id'=>'registeration','class'=>'form-control input form-control','id'=>'registeration','data-url'=>Url::to('validate-usrname')])->label(Yii::t('app','Register Number').'<span style="color:#a94442;"> * </span>')?>
                        <?php } ?>


         <?= $form->field($model, 'first_name')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase','class'=>'input form-control','id'=>'firstnamePersonnel'])->label('First Name <span style="color:red">*</span>') ?>
                
         <?= $form->field($model, 'last_name')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase','class'=>'input form-control','id'=>'lastnamepersonel'])->label(Yii::t('app','Last name').'<span style="color:#a94442;"> *</span>') ?>
		</div>
		<div class="col-md-6">
			<label style="height: 0px"></label>
			<?= $form->field($model, 'email')->textInput()->label('Student Email (Optional)') ?>
                    <?php
                    
                    if(!$model->isNewRecord){
                    	echo $form->field($model, 'Image')->fileInput()->label('Upload Photo');
                        $src=Yii::$app->request->baseUrl.'/uploads/'.$model->Image;
                        echo Html::img( $src, $options = ['width'=>60,'height'=>'60','alt'=>'No Image Uploaded']);
                        echo $form->field($model, 'Image')->hiddenInput()->label(false);

                    }else{
                    	echo $form->field($model, 'Image')->fileInput()->label('Upload Photo');
                    }
                    ?>
         </div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<?= Html::submitButton($model->isNewRecord ? 'Enroll Student' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'stuadmissionform']) ?>
			</div>
		</div>
	</div>
</div>
    <?php ActiveForm::end(); ?>