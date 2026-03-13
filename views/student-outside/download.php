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

        <h3 style='text-align:center'>Acadmy Students List</h3>

<div style="width: 100%; float:right background:none; font-size:13px;">
                <table class="table table-striped" style="background:none;" cellpadding="8">
                    <thead>
                    <tr> 
                        <th style="background:none;">S#&nbsp;&nbsp;</th>
                        <th style="background:none;">&nbsp;&nbsp;Class</th>
                        <th style="background:none;">&nbsp;&nbsp;Group</th>
                        <th style="background:none;">&nbsp;&nbsp;Section</th>
                        <th style="background:none;">Registeration Date</th>
                        <th style="background:none;">Parent</th>
                        <th style="background:none;">Organization</th>
                        <th style="background:none;">Contact</th>
                        <th style="background:none;">Parent Contact</th>
                    </tr>
                    </thead> 
                    <tbody>
                    <?php
                    $i=0;
                    foreach ($outside as $outside){ 
                    $i++;
                    ?>
                                <tr style="background:none;">
                                <td style="background:none;"><?=$i?>&nbsp;&nbsp;</td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$outside->class->title?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=(!empty($outside->group->title))? $outside->group->title :'N/A'?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$outside->section->title?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$outside->regesteration_date?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$outside->parent_name?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$outside->organization?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$outside->contact_no?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$outside->parent_contact?></td>
                                
                                
                                </tr>
                        <?php }?>
                    </tbody>
                </table> 
            </div>