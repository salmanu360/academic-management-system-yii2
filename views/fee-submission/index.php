
<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = 'Fee Submissions';
CrudAsset::register($this);
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>
<div class="box box-warning">
    <div class="box-body">
    <div class="row">
        <div class="col-md-3">
            <input type="text" class="form-control" id="inputVal" placeholder="Search by student name or Reg. #" name="search" title="Type in a name" data-url="<?php echo Url::to(['search-grid']) ?>" />
        </div>
        <div class="col-md-2">
            <button id="searchInput" class="btn btn-success">Search</button>
            <a href="" class="btn btn-danger">Refresh</a>
        </div>
    </div>
    </div>
</div>
<div id="loading" style="display: none"><h3 style="color:red">Loading please wait..</h3></div>
<div id="ajaxCrudDatatable"></div>
<?php Modal::begin([
'options' => [
        'id' => 'ajaxCrudModal',
        'tabindex' => false
             ],
   "footer"=>"",
])?>
<?php Modal::end(); ?>