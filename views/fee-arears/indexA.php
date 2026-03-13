<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
$this->title = 'Fee Arears';
CrudAsset::register($this);
?>
<style>
    #example_filter{
   float: right;
   color: #337ab7;
   } 
</style>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>
<div class="box box-warning">
<div class="row">
<div class="box-body">
<div class="col-md-3">
 <input style="border: 1px solid blue;" type="text" id="inputValue" class="form-control" placeholder="Search by Student name Or Reg #." name="search" title="Type in a name" autocomplete="off"/>
</div>
<div class="col-md-3"><input type="submit" value="Search" id="buttonClick" class="btn btn-primary" data-url="<?php echo Url::to(['update-arrears']) ?>">&nbsp;&nbsp;
    <a href="<?php echo Url::to(['index']) ?>" value="refresh" class="btn btn-danger">Refresh</a>
</div>
    </div>
</div>
</div>
<div id="loading" style="display: none"> <h3 style="color:red">Loading... please wait.</h3></div>
<div id="ajaxCrudDatatable"></div>
<?php Modal::begin([
'options' => [
        'id' => 'ajaxCrudModal',
        'tabindex' => false
             ],
   "footer"=>"",
])?>
<?php Modal::end();?>