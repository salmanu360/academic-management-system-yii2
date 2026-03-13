 <style type="text/css">
            *{ margin:0; padding:0;}
            th, tr, td  {
border:1px solid #469DC8;
padding:10px;
font-size:1.5em;
}

tr:nth-child(even){background-color: #f2f2f2}
        </style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
                <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
                    <?=Yii::$app->common->getBranchDetail()->address?>
                </h2>
            </div>

        <h3 style='text-align:center'>All Books In Library</h3>

<div style="width: 100%; float:right background:none; font-size:13px;">
                <table class="table table-striped" style="background:none;" cellpadding="8">
                    <thead>
                    <tr> 
                        <th style="background:none;">S#&nbsp;&nbsp;</th>
                        <th style="background:none;">&nbsp;&nbsp;Category</th>
                        <th style="background:none;">&nbsp;&nbsp;Book Isbn No</th>
                        <th style="background:none;">&nbsp;&nbsp;Book No</th>
                        <th style="background:none;">Title</th>
                        <th style="background:none;">Author</th>
                        <th style="background:none;">Edition</th>
                        <th style="background:none;">No Of Copies</th>
                        <th style="background:none;">Remaining Copies</th>
                        <th style="background:none;">Rack No</th>
                        <th style="background:none;">Shelf No</th>
                        <th style="background:none;">Book Position</th>
                        <th style="background:none;">Book Cost</th>
                        <th style="background:none;">Book Condition</th>
                        <th style="background:none;">Language</th>
                    </tr>
                    </thead> 
                    <tbody>
                    <?php
                    
                    $i=0;
                    //echo '<pre>';print($books);die;
                    foreach ($books as $books){ 
                    $getCategory=\app\models\AddlibraryCategory::find()->where(['id'=>$books->addlibrary_category_id])->one();
                    $i++;
                    ?>
                                <tr style="background:none;">
                                <td style="background:none;"><?=$i?>&nbsp;&nbsp;</td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$getCategory->category_name?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$books->book_isbn_no?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$books->book_no?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$books->title?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$books->author?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$books->edition?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$books->no_of_copies?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$books->remaining_copies?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$books->rack_no?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$books->shelf_no?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$books->book_position?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$books->book_cost?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$books->language?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$books->book_condition?></td>
                                
                                </tr>
                        <?php }?>
                    </tbody>
                </table> 
            </div>