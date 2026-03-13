<?php use Da\QrCode\QrCode;
$qrCode = (new QrCode('This is salman khan'))
    ->setSize(250)
    ->setMargin(5)
    ->useForegroundColor(51, 153, 255);

$qrCode->writeFile(__DIR__ . '/code.png'); // writer defaults to PNG when none is specified

// display directly to the browser 
header('Content-Type: '.$qrCode->getContentType());
 $qrCode->writeString();
 ?>
 <div class="container" style="width: 460px;
  
    margin-left: 0px;
  background-color: #fff;
  "> 
  <div class="containerTop" style="height: 50px; width:  100%;   ">
    <div class="topLeft" style="width: 30%;
      height: 90px;
      background-color: #2b2b2b;
      background-image: url('<?php echo Yii::$app->request->baseUrl?>/img/centre.png');
      background-position: center;
      background-repeat: no-repeat;
      background-size: 100% 100%;
      float: left;
      ">
      <div style="height: 4px"> </div>
      <p class="Logo" style="width: 120px;
        height: 50px;
        margin-left: 0px;
        margin-right: 10px;
        background-image: url('<?php echo Yii::$app->request->baseUrl?>/img/logo.png');
        background-position: center;
        background-repeat: no-repeat;
          background-size: 40% 100%;">
      </p>
    </div>
    <div class="topRight" style="float:left; height:90px; width:70%; float:left; background-image: url('<?php echo Yii::$app->request->baseUrl?>/img/university.jpg');   background-size: 100% 100%; ">
      <div class="address" style="width: 70%;
      height: 25px;
      float: right;
      margin-top: 10px;
      margin-right: 5px; 
      color: #fff;
      font-size: 10px;
      padding: 5px;
      text-align: center;"> Gandhara school of exellence Dir lower near Ghulam Abbbas colony Dir lower.</div>
    </div>
    <img style="height: 85px;background-position: center;float: left;border-radius: 35px;
      margin-left:   320px;margin-top: -50px;width: 80px;position: relative" src="<?php echo Yii::$app->request->baseUrl?>/img/image.png" alt="" ><br>
      <p></p>
      <p></p>
  </div>
  <div class="containerBottom" style="height: 10px; width:  100%;  ">
    <div class="background" >
    <div class="bottomLeft" style="width: 35%;
      height: 100%;
      margin-top: 10px;
      float: left;
      font-weight: bold;
    ">
      <div style="font-size: 10px; margin-top: -50px; margin-left: 15px;">Registration No.</div>
    
      <div style="font-size: 10px;margin-left: 15px;">Class</div>
      <div style="font-size: 10px; margin-left: 15px;">Section</div>
      <div style="font-size: 10px; margin-left: 15px;">Roll No.</div>
      <div class="qrcode" style="width: 35px;
        height: 35px;
        background-image: url('<?php echo Yii::$app->request->baseUrl?>/img/qrcode.png');
        margin-left: 10px;
        background-repeat: no-repeat;
        background-size: 100% 100%;
        margin-top: 30px;
        float: left;"></div>
      <div style="width: 100px; float: left; font-size: 9px; margin-top: 15px; margin-left: 5px;">Valid upto: June 2019</div>
    </div>  
    <div class="bottomCentre" style="width: 25%;
        height: 100%;
        float: left;
        margin-left: -10px;
        margin-top: 0px;
        font-weight: bold;">
      <div style="font-size: 10px; margin-top: -50px; ">: <?php echo strtoupper(Yii::$app->common->getUserName($student->user_id)) ?></div>
      <div style="font-size: 10px;">: <?php echo ucfirst($class_name->title) ?></div>
      <div style="font-size: 10px; ">: <?php echo ucfirst($section->title) ?></div>
      <div style="font-size: 10px; ">: <?php echo ($student->roll_no)?$student->roll_no:'N/A' ?></div>
    </div>

    <div class="bottomRight" style="width: 40%;
      float: left;
      height: 70px;
      margin-top: -50px;">
      <div class="name" style="width: 60%;
        float: left;
        height: 15px;
        color: #3267ab;
        margin-left: 43px;
        margin-top: 10px; 
        font-size: 15px;
        text-align: center;
        font-weight: bold;"> <?php echo ucfirst(Yii::$app->common->getName($student->user_id)) ?> </div>
      <div class="sign" style="width: 50%;
        float: right;
        height: 30px;
        margin-top: 25px;
        background-image: url('<?php echo Yii::$app->request->baseUrl?>/img/sign.png');
        background-repeat: no-repeat;
        margin-right: 40px;
         background-size: 100% 150%;
        font-size: 15px;
        text-align: center;
        text-align: center;"></div>
      <div class="authority" style=" width: 50%;
        float: right;
        height: 15px;
        padding-top: 10px;
        margin-right: 40px;
        font-weight: bold; 
        font-size: 10px;
        text-align: center;">Issuing authority</div>
    </div>
  </div>
</div>
</div>