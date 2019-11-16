<?php
session_start();
include("../../config/dbConnect.php");
include("../../config/dbClass.php");

$dbClass = new dbClass;
$customer_name = $_SESSION['customer_name'];

if(!isset($_SESSION['id']) && $_SESSION['id'] == "") header("Location:".$project_url."../view/login.php");

?>
<div class="row">
	<div class="col-md-8">
		<div class="x_panel">
			<div class="x_title">
				<h2>Account Information</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
			   <div style="height:250px; width:100%; overflow-y:auto">
				<table id="accounts_Table" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped  table-scroll ">
					<thead >
						<tr class="headings">
							<th class="column-title" width="60%">Account No</th>
							<th class="column-title text-right" width="40%">Balance (€)</th>
						</tr>
					</thead>
					<tbody id="account_table_body" class="scrollable">             
						
					</tbody>
				</table>
				</div> 
			</div>
		</div>
	</div>
	<div class="col-md-4"></div>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="x_panel account_entry_cl">
			<div class="x_title">
				<h2>Create New Account</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link" id="toggle_form"><i class="fa fa-chevron-down"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content" id="iniial_collapse">
				<br />             
				<form id="account_form" name="account_form" enctype="multipart/form-data" class="form-horizontal form-label-left">   
					<div class="row">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-6">Account No</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="account_no" name="account_no" required  readonly class="form-control col-lg-12"/>
							</div>						
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-6"></label>
						<div class="col-md-6 col-sm-6 col-xs-12">  
							<button type="submit" id="saveBtn" class="btn btn-success">Create Account</button>                    
							<button type="button" id="clearBtn" class="btn btn-primary">Clear</button>                         
						</div>
						 <div class="col-md-12 col-sm-12 col-xs-12">
							<div id="form_submit_error" class="text-center" style="display:none"></div>
						 </div>
					</div>
				</form>  
			</div>
		</div>
	</div>
	<div class="col-md-4"></div>
</div>

<script>

//------------------------------------- grid table codes --------------------------------------
/*
develped by @moynul
=>load grid 
*/
$(document).ready(function (){	
	var url = project_url+"controller/accountsController.php";

	load_data = function load_data(){		
		$.ajax({
			url: project_url+"controller/accountsController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "grid_data",
			},
			success: function(data){					
				var html = "";				
				// for  showing grid's no of records from total no of records 
				var records_array = data.records;
				$('#accounts_Table tbody tr').remove();
				//$("#search_accounts_button").toggleClass('active');
				if(!jQuery.isEmptyObject(records_array)){
					// create and set grid table row
					var colums_array=["id*identifier*hidden","account_no","balance*text-right"];
					// first element is for view , edit condition, delete condition
					// "all" will show /"no" will show nothing
					var condition_array=["all","","all", "","",""];
					// create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_arraymodule_name,table/grid id, is_checkbox to select tr );
					// cauton: not posssible to use multiple grid in same page					
					create_set_grid_table_row(records_array,colums_array,condition_array,"account","accounts_Table", 0);
					// show the showing no of records and paging for records 					
				}
				// if the table has no records / no matching records 
				else{
					grid_has_no_result( "accounts_Table",3);
				}
			}
		});	

	}

	
});


//<!-- ------------------------------------------end --------------------------------------->


//<!-- -------------------------------Form related functions ------------------------------->

/*
develped by @moynul
=>form submition for add/edit
=>clear form
=>load data to edit
=>delete record
=>view 
*/
$(document).ready(function () {		
	var url = project_url+"controller/accountsController.php";

	// save and update for public post/notice
	$('#saveBtn').click(function(event){		
		event.preventDefault();
		var formData = new FormData($('#account_form')[0]);
		formData.append("q","insert_or_update");
		if($.trim($('#account_no').val()) == ""){
			success_or_error_msg('#form_submit_error','danger',"Please Insert Account No","#account_no");			
		}
		else{			
			$.ajax({
				url: url,
				type:'POST',
				data:formData,
				async:false,
				cache:false,
				contentType:false,
				processData:false,
				success: function(data){
					$('#saveBtn').removeAttr('disabled','disabled');
					
					if($.isNumeric(data)==true && data==5){
						success_or_error_msg('#form_submit_error',"danger","Please Insert Unique Account No","#account_no" );			
					}
					else if($.isNumeric(data)==true && data>0){
						success_or_error_msg('#form_submit_error',"success","Save Successfully");
						load_data("");
						clear_form();
					}
					else{
						success_or_error_msg('#form_submit_error',"danger","Not Saved...");																			
					}
				 }	
			});
		}	
	})
	

	// clear function to clear all the form value
	clear_form = function clear_form(){			 
		$("#account_form").trigger('reset');
		load_new_account_no();		
	}
	
	// on select clear button 
	$('#clearBtn').click(function(){
		clear_form();
	});
		
	// load data initially on page load with paging
	load_data("");
	
	// load account new no	
	load_new_account_no = function load_new_account_no(){
		$.ajax({
			url: url,
			type:'POST',
			async:false,
			data: "q=new_account_no",
			success: function(data){
				$('#account_no').val(data);
			}	
		});	
	}
	
	//function for load new account no
	load_new_account_no();
	
});


//<!-- ------------------------------------------end --------------------------------------->
</script>