
	$(document).on('click','.popups', function(){
		var al=$('#leaveid').val('');
		$('#remarks').val('');
		$('#getValues').empty();
		$('.displayvalidationRemarks').empty();
		var urls = $(this).data('urls');
		var uniqueId=$(this).attr('id');
		$('#unique_id').val(uniqueId);
		var emp_is=$(this).data('empid');
		var dataString = '$id='+ emp_is;
        $('#input_id').val(emp_is);
		var d=$(this).data('date');
		$('#input_date').val(d);
		$.ajax({
			type:'POST',
			dataType: "json",
			data:{  emp_is:emp_is,
				d:d,
			},
			url: urls,
			success:function(msg){
				if(msg){
						$('#leaveid').val(msg.type);
						$('#remarks').text(msg.remarks);
						$('#getValues').html(msg.newprovide);
					}else{
					}
				}
			});
		
	});

	$(document).on('click','.pop', function(e){
		e.preventDefault();
		var url = $(this).data('url');
		var get_uniqueid=$('#unique_id').val();
		var select=$('.leaveSelect').val();
		var remark=$('.remarks').val();

		var employee= $('.getId').val();
		var getDate= $('.getdate').val();
		var date=$(this).attr('dates');

		if(select == ""){
              //alert('please fill remarks..');
          }else if(remark == ""){
          	$('.displayvalidationRemarks').text("Remarks Field Cannot Be Blank");

          }else{
          	$.ajax({
          		type:'POST',
          		data:{  select:select,
          			remark:remark,
          			employee:employee,
          			date:date,
          			getDate:getDate
          		},
          		url:'save-leave/',
          		success:function(result){
          			console.log(result);
          			$('#'+get_uniqueid).html(result);
          			$('#myModal').modal('hide');
				}
			});

          }
      });
$(document).on('click','#deleteEmployee', function(){
		var url = $(this).data('urli');
		var uniqueId=$(this).attr('id');
		var emp_is=$(this).data('empid');
		var d=$(this).data('date');
		var ulri=$('a#'+get_uniqueid);
		$.ajax({
			type:'POST',
			dataType: "json",
			data:{  emp_is:emp_is,
				d:d,
			},
			url: url,
			success:function(msg){
			}
		});
	});
/*employee add form*/
$(document).on('click','.employeeFormSubmit',function(){
	var empParentsFirstName= $('#empParentsFirstName').val();
	var assignrole= $('.assignrole').val();
	var payHeadEmployee= $('.payHeadAdd').val();
	var PayType= $('.paytypeAdd').val();
	if(assignrole == ''){
       $('#assignroleError').html('Role is required'); 
       return false;
    }else{
       $('#assignroleError').html(''); 
    }if(empParentsFirstName == ''){
       $('#pfirstName').html('First Name is required'); 
       return false;
    }else{
       $('#pfirstName').html(''); 
    }if(payHeadEmployee == '' || payHeadEmployee ==  undefined){
    	$('#payHeadError').html('Pay Head is required');
    	return false;
    }else{
    	$('#payHeadError').html('');
    }
    if(PayType == '' || PayType ==  undefined){
    	$('#payTypeError').html('Pay Type is required');
    	return false;
    }else{
    	$('#payTypeError').html('');
    }

    });
/*employee add form ends*/