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
				<h2>Transfer Amount</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>			
		
			<div class="row">
				<div class="col-md-3"><b>From Account Number</b></div>
				<div class="col-md-4">
					<select name="account_list" id="account_list" class="form-control">
						<option value="0">Select Option</option>
					</select> 					
				</div>
			</div>			
			<br />  			
			
			<div class="x_content" id="initial_collapse">
			   <div style="height:250px; width:100%; overflow-y:auto">
				<table id="deposit_Table" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped  table-scroll ">
					<thead >
						<tr class="headings">
							<th class="column-title" width="35%">From Account No</th>
							<th class="column-title" width="35%">To Account No</th>
							<th class="column-title text-right" width="20%">Transfered Amount (€)</th>		
							<th class="column-title text-right" width="10%">Status</th>									
						</tr>
					</thead>
					<tbody id="deposit_table_body" class="scrollable">             
						
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
		<div class="x_panel">
			<div class="x_title">
				<h2>Withdrow Amount</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link" id="toggle_form"><i class="fa fa-chevron-down"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<br />             
				<form id="transfer_form" name="transfer_form" enctype="multipart/form-data" class="form-horizontal form-label-left">   
					<div class="row">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-6">To Account Number</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="to_account_no" name="to_account_no" required class="form-control col-lg-12"/>								
							</div>						
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-6">Amount</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="number" id="amount" name="amountt" required class="form-control col-lg-12"/>								
							</div>						
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-6"></label>
						<div class="col-md-6 col-sm-6 col-xs-12">  
							<input type="hidden" id="account_id" name="account_id" />  
							<button type="submit" id="saveBtn" class="btn btn-success">Transfer</button>                    
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
//------------------------------------- general & UI  --------------------------------------
/*
develped by @moynul
*/
$(document).ready(function () {	
	
	var url = project_url+"controller/accountsController.php";

	// save and update for public post/notice
	$('#saveBtn').click(function(event){		
		event.preventDefault();
		var formData = new FormData($('#transfer_form')[0]);
		formData.append("q","save_transfer");

		if($.trim($('#account_list').val()) == 0){
			success_or_error_msg('#form_submit_error','danger',"Please Select From Account No","#account_list");			
		}
		else if($.trim($('#to_account_no').val()) == 0){
			success_or_error_msg('#form_submit_error','danger',"Please Enter To Account No","#to_account_no");			
		}
		else if($.trim($('#amount').val()) == 0){
			success_or_error_msg('#form_submit_error','danger',"Please Enter a valid amount","#amount");			
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
					if(data==1){
						success_or_error_msg('#form_submit_error',"success","Save Successfully");
						account_id = $('#account_id').val();
						load_data(account_id);
						$('#amount').val("");
						$('#to_account_no').val("");
					}
					else if(data==3){
						success_or_error_msg('#form_submit_error',"danger","Not available balance to transfer");																			
					}
					else if(data==4){
						success_or_error_msg('#form_submit_error',"danger","Account number is incorrect");																			
					}
					else{
						success_or_error_msg('#form_submit_error',"danger","Not Saved...");																			
					}
				 }	
			});
		}	
	})

	
	// load account new no	
	load_account_list = function load_account_list(){
		$.ajax({
			url: url,
			type:'POST',
			dataType: "json",
			async:false,
			data: "q=load_account_list",
			success: function(data){
				if(!jQuery.isEmptyObject(data.records)){
					$('#account_list').find('option').not(':first').remove();
					var combo = "";
					$.each(data.records, function(i,data){	
						combo +='<option value="'+data.id+'">'+data.account_no+'</option>';
					});				
					$('#account_list').append(combo);
				}				
			}	
		});	
	}
	
	//function for load new account no
	load_account_list();
	
	//On change Account List Dropdown
	$("#account_list") .change(function () {    
		var account_id = $(this).val();
		$('#account_id').val(account_id);
		load_data(account_id);
	});  
	
	
	load_data = function load_data(account_id){		
		$.ajax({
			url: url,
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "grid_data_transfer",
				account_id: account_id,
			},
			success: function(data){
				var html = "";				
				// for  showing grid's no of records from total no of records 
				var records_array = data.records;
				$('#deposit_Table tbody tr').remove();
				//$("#search_accounts_button").toggleClass('active');
				if(!jQuery.isEmptyObject(records_array)){
					// create and set grid table row
					var colums_array=["id*identifier*hidden","from_account_number","to_account_number","amount*text-right", "status*text-center"];
					// first element is for view , edit condition, delete condition
					// "all" will show /"no" will show nothing
					var condition_array=["all","","all", "","",""];
					// create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_arraymodule_name,table/grid id, is_checkbox to select tr );
					// cauton: not posssible to use multiple grid in same page					
					create_set_grid_table_row(records_array,colums_array,condition_array,"deposit","deposit_Table", 0);
					// show the showing no of records and paging for records 					
				}
				// if the table has no records / no matching records 
				else{
					grid_has_no_result( "deposit_Table",2);
				}
			}
		});
	}
	
});


<!-- ------------------------------------------end --------------------------------------->
</script>