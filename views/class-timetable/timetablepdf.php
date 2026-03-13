<style>
    table{

width:950px;
margin-left:2%;
border-collapse: separate;
border-spacing: 5px;
border-color: black;

}

th, tr, td  {
border:1px solid #4B8D79;
padding:10px;
font-size:1.5em;
}

tr:nth-child(even){background-color: #f2f2f2}
</style>
<table class="table table-striped table-hover">
    <thead>
        <tr class="info">
            <th>Day </th>
            <th>Subject </th>
            <th>Start Time</th>
            <th>End Time </th>
        </tr>
    </thead>
    <tbody>
    <?php 
     foreach($subjectsdetails as $timetable): ?>       
    <tr>
    <td>
    <?= $timetable->day ?>
    </td>
    <td>
    <?= $timetable->subject->title; ?>
    </td>
    <td>
    	
    <div class="input-group bootstrap-timepicker timepicker">
    <?= $timetable->start_date ?>
    </div>
    </td>
    <td>
    <div class="input-group bootstrap-timepicker timepicker">
    <?= $timetable->end_date ?>
    </div>
    </td>	
    </tr>    
        <?php endforeach; ?>
        </tbody>
        </table>