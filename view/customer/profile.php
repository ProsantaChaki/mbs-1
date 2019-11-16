<?php 
session_start();
include("../../config/dbConnect.php");
include("../../config/dbClass.php");
$dbClass = new dbClass;

if(!isset($_SESSION['id']) && $_SESSION['id'] == "") header("Location:".$project_url."../view/login.php");
else{
?>
<div class="x_content">
	<div class="x_panel employee_profile">
		<div class="x_title">
			<h2>Personal Information</h2>
			<div class="clearfix"></div>
		</div>
		<div class="x_content" id="iniial_collapse">
			<br />     
			<form id="customer_update_form" name="customer_update_form" enctype="multipart/form-data" class="form-horizontal form-label-left">   
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Full Name</label>
							<div class="col-md-4 col-sm-4 col-xs-6">
								<input type="text" id="customer_name" name="customer_name" required class="form-control col-lg-12"/>
								<input type="hidden" id="id" name="id"/>
							</div>	
							<label class="control-label col-md-2 col-sm-2 col-xs-6">Email</label>
							<div class="col-md-4 col-sm-4 col-xs-6">
								<input type="email" id="email" name="email" class="form-control col-lg-12"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Personal Code</label>
							<div class="col-md-4 col-sm-4 col-xs-6">
								<input type="text" id="personal_code" name="personal_code" required class="form-control col-lg-12"/>
							</div>	
						</div>	
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-6"></label>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<button  type="submit" id="saveBtn" class="btn btn-success">Update</button>                                        
							</div>
							 <div class="col-md-8 col-sm-8 col-xs-12">
								<div id="form_submit_error" class="text-center" style="display:none"></div>
							 </div>
						</div>
					</div>
					<div class="col-md-1"></div>
				</div>
			</form> 
		</div>
	</div>
</div>	
	

<script>

//------------------------------------- general & UI  --------------------------------------
	
	

$(document).ready(function () {	
	var id 	= "<?php echo $_SESSION['id']; ?>";	
	
	load_customer_profile = function load_customer_profile(){
	    //alert('profile')
		$.ajax({
			url: project_url+"controller/customerController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "get_customer_info"
			},
			success: function(data){
			    //alert('ok')
				$('#id').val(data.records.id);
				$('#customer_name').val(data.records.customer_name);
				$('#email').val(data.records.email);
				$('#personal_code').val(data.records.personal_code);
			}
        });
        //alert(project_url+"controller/customerController.php")

    }
	
	load_customer_profile();
	

	$('#saveBtn').click(function(event){
        //alert('profile')

        event.preventDefault();
		var formData = new FormData($('#customer_update_form')[0]);
		formData.append("q","update_information");

		$.ajax({
			url: project_url+"controller/customerController.php",
			type:'POST',
			data:formData,
			async:false,
			cache:false,
			contentType:false,processData:false,
			success: function(data){
				$('#saveBtn').removeAttr('disabled','disabled');
				if(data==1){
					success_or_error_msg('#form_submit_error',"success","Updated Successfully");
					load_customer_profile("");
				}
				else if(data==2){
					success_or_error_msg('#form_submit_error',"danger","Please Insert Unique Email");
					load_customer_profile("");
				}
				else{
					success_or_error_msg('#form_submit_error',"danger","Error");
				}  
			}	
		});	
	})
				
});
</script>
	
	<?php
} 
?>

