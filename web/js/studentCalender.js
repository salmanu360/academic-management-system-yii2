 

 // ----- student calendar script -----




 $(document).on('click','.studentAttendance', function(){
		//alert('here');
        var al=$('#leaveid').val('');
        $('#remarks').val('');
        $('#getValues').empty();
        $('.displayvalidationRemarks').empty();
        //alert(al);
        var urls = $(this).data('urls');
        var emp_is=$(this).data('empid');
        var uniqueId=$(this).attr('id');
        $('#unique_id').val(uniqueId);
        //$('#unique_id').empty();
        $('#input_stu_id').val(emp_is);
        var dataString = '$id='+ emp_is;
        var d=$(this).data('date');
        $('#input_stu_date').val(d);
        var getName=$(this).data('stu_name');
        $('#input_nameStu').val(getName);
        $.ajax({
            type:'POST',
            dataType: "json",
            data:{  emp_is:emp_is,
               d:d,
           },
           url: urls,

           success:function(msg){
               if(msg){
						//alert('ok');
						//console.log(msg);
						//alert(msg.type);
						// $('#leaveid').val(msg.type);
						// $('#remarks').text(msg.remarks);
						//$('#getValues').html(msg.newprovide);
						$('#leaveid').val(msg.type);
						$('#remarks').val(msg.remarks);
						$('#getValues').html(msg.newprovide);
					}else{
					}
				}
			});
    });

 $(document).on('click','.student_pop', function(e){
   e.preventDefault();
   var url = $(this).data('url'); 
   var get_uniqueid=$('#unique_id').val();
   var select=$('.leaveSelect').val(); 
   var remark=$('.remarks').val(); 
   var pasclasid=$('#pasclasid').val(); 
   var pasgroup_id=$('#pasgroup_id').val(); 
   var passection_id=$('#passection_id').val(); 
   var student= $('.getId').val(); 
   var getDate= $('.getdate').val(); 
   var date=$(this).attr('dates');
   var nameStu=$('#input_nameStu').val();
   if(select == ""){

               }else if(remark == ""){
                		//alert('please fill remarks..');
                        $('.displayvalidationRemarks').text("Remarks Field Cannot Be Blank");

                    }else{
                      $.ajax({
                        type:'POST',
                        data:{  select:select,
                           remark:remark,
                           student:student,
                           date:date,
                           getDate:getDate,
                           nameStu:nameStu,
                           pasclasid:pasclasid,
                           pasgroup_id:pasgroup_id,
                           passection_id:passection_id,
                       },
                       url:'save-leave/',
                       timeout: 2000,
                       cache: false,
                       success:function(result){
                         $('#'+get_uniqueid).html(result);
    					 $('#myModal').modal('hide');
     }
 });

                  }
              });

 $(document).on('click','.closemodel', function(){
 });

 $(document).on('click','.emps_', function(){
   alert('adsf');
});