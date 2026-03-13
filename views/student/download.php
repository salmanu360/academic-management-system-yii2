<?php 
use yii\helpers\Url;
$this->title="Admission Form";
$id=Yii::$app->request->get('id');
echo $this->render('step_menu');
?>
<iframe src="<?php echo Url::to(['student/download-form','id'=>$id])?>" style="width: 660px; height:  640px;" frameborder="0">