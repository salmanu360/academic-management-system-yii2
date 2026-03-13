<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\grid\GridView;
$this->registerCssFile(Yii::getAlias('@web').'/css/jquery.signaturepad.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/signature/numeric-1.2.6.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/signature/bezier.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/signature/jquery.signaturepad.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/signature/json2.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/signature/html2canvas.js',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<style type="text/css">
#signArea{
	width:304px;
}
.sign-container {
	width: 60%;
	margin: auto;
}
.sign-preview {
	width: 150px;
	height: 50px;
	border: solid 1px #CFCFCF;
	margin: 10px 5px;
}
.tag-ingo {
	font-family: cursive;
	font-size: 12px;
	text-align: left;
	font-style: oblique;
}
</style>
<?php if (Yii::$app->session->hasFlash('warning')): ?>
    <div class="alert alert-danger alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?= Yii::$app->session->getFlash('warning') ?>
    </div>
<?php endif; ?>
<div class="panel panel-default">
	<div class="panel-body">
		  <?php $form = ActiveForm::begin(); ?>
		  <div class="row">
			<div class="col-md-4">
				<div class="form-group">
				<?php $expenseCategoryArray = ArrayHelper::map(Yii::$app->common->getSignature(), 'id', 'name');
                        echo $form->field($model, 'category')->widget(Select2::classname(), [
                            'data' => $expenseCategoryArray,
                            'options' => ['placeholder' => 'Select Category ...','id'=>'catId'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                   <span id="categoryError" style="color: red"></span>
			</div>
			</div>
			</div>
			<?php ActiveForm::end(); ?>
			<div class="form-group">
				<div id="signArea" >
					<h2 class="tag-ingo">Put signature below,</h2>
					<div class="sig sigWrapper" style="height:auto;">
						<div class="typed"></div>
						<canvas class="sign-pad" id="sign-pad" width="300" height="100"></canvas>
					</div>
				</div>
			</div>
			<button type="submit" id="btnSaveSign" class="btn btn-success" data-url="<?= Url::to(['save-sign'])?>">Save Signature</button>
			<a href="" class="btn btn-danger">Clear</a>
	</div>
</div>
	<?php//$image_list = glob("./doc_signs/*.png");?>
<div class="panel panel-default">
	<div class="panel-body">
		<?= GridView::widget([
        'dataProvider' => $provider,
        'summary'=>'', 
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           // 'fk_empl_id',
             [
             'label'=>'Signature',
             'format'=>'raw',
             'value'=>function($data){
             	$src=Yii::$app->request->baseUrl.'/uploads/doc_signs/'.$data->image.'.png';
                    return Html::img( $src);
             }
            ],
           [
             'label'=>'Category',
             'value'=>function($data){
             	return Yii::$app->common->getSignatureCategory($data->category);
             }
            ],
           ['class' => 'yii\grid\ActionColumn',
            'template'=>"{delete}",
           ],

             
        ],
    ]); ?>
</div>
</div>
<?php
$script= <<< JS
$(document).ready(function() {
	$('#signArea').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:90});
	});
	$("#btnSaveSign").click(function(e){
		var url=$(this).data('url');
		var categoryId=$('#catId').val();
		if(categoryId == ''){
			$('#categoryError').text('Cantegory cannot be blank');
		}else{
		html2canvas([document.getElementById('sign-pad')], {
			onrendered: function (canvas) {
				var canvas_img_data = canvas.toDataURL('image/png');
				var img_data = canvas_img_data.replace(/^data:image\/(png|jpg);base64,/, "");
				$.ajax({
					url: url,
					data: { img_data:img_data,categoryId:categoryId },
					type: 'post',
					dataType: 'json',
					success: function (response) {
						window.location.reload();
					}
					});
				}
				});
			}
				});
JS;
				$this->registerJs($script);
				?>  