<?php
session_start();
if(isset($_SESSION['customer_id']) && $_SESSION['customer_id'])header("Location:".$project_url."dashbord.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mini Banking System</title>
        <!-- Bootstrap core CSS -->
        <link href="theme/css/bootstrap.min.css" rel="stylesheet">
        <link href="theme/fonts/css/font-awesome.min.css" rel="stylesheet">    
        <!-- Custom styling plus plugins -->
        <link href="theme/css/custom.css" rel="stylesheet">
        <script src="theme/js/jquery.min.js"></script>
        <script src="js/static_text.js"></script>
        <script src="js/common.js"></script> 
    </head>
    
    <body style="background:#FFF;">        
        <div class=""> 
            <div id="wrapper">
                <div id="login" class="animate form" align="center" >
                    <h1 align="center"><a href="#" >MBS</a></h1>
                    <section class="login_content" id="login_div">
                       <form name="login_form" id="login_form" action="" method="post">
                            <h1>Login</h1>
                            
                            <div id="login_error"></div>
                            
                            <div>
                                <input type="email" class="form-control" placeholder="Email" required name="login_email" id="login_email"/>
                            </div>
                            <div>
                                <input type="password" class="form-control" placeholder="Password" required name="login_password" id="login_password"/>
                            </div>
                            <br>
                            <div>
                                <input name="login_btn" id="login_btn" name="submit" id="submit" class="btn btn-primary active submit" value="Login" type="submit">
                                <a id="open_register_form" href="javascript:void(0)"><h5>Registration</h5></a>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                        <!-- form -->
                    </section>
                    <section class="login_content hide" id="registration_div" >
                       <form name="registration_form" id="registration_form" action="" method="post">
                            <h1>Registration</h1>                            
                            <div id="regitration_error"></div>
                            
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">Full Name</label>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<input type="text" id="customer_name" name="customer_name" placeholder="Full Name" required class="form-control"/>
								</div>	
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">Personal code</label>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<input type="text" id="personal_code" name="personal_code" placeholder="Personal Code" required class="form-control"/>
								</div>	
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">Email</label>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<input type="email" id="email" name="email" placeholder="Email" required class="form-control"/>
								</div>	
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">Password</label>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<input type="password" class="form-control" placeholder="Password" required name="password" id="password" />
								</div>	
							</div>							   
                            <br>
                            <div>
                                <input name="registration_btn" id="registration_btn" class="btn btn-primary active" value="Registration" type="submit">
                                <a id="open_login_form" href="login.php"><h5>Login ?</h5></a>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                        <!-- form -->
                    </section>
                    <div>
                        <br /> <br />
                        <p>Developed By : Mynul Hasan</p>
                    </div>
                    <!-- content -->
                </div>
            </div>
        </div>
    </body>
</html>


<script>
// login function
$(document).ready(function(){

	$('#open_register_form').click(function(){
		$('#login_div').addClass('hide');
		$('#registration_div').removeClass('hide');
		$('#login_form').trigger('reset');
	});
	
	$('#open_login_form').click(function(){
		$('#registration_div').addClass('hide');
		$('#login_div').removeClass('hide');
		$('#registration_form').trigger('reset');
	});
	

	
	// Login
	$("#login_form").submit(function(){
		//check the username exists or not 
		if($('#login_email').val() == ""){
			$("#login_error").fadeTo(200,0.1,function(){ 
				$(this).html('<div class="alert alert-danger">Please provide Email</div>').fadeTo(900,1);
			});
		}
		else if($('#login_password').val() == ""){
			$("#login_error").fadeTo(200,0.1,function(){ 
				$(this).html('<div class="alert alert-danger">Please provide Password</div>').fadeTo(900,1);
			});
		}
		else{ 
			$.ajax({
				url: "controller/authController.php",
				type:'POST',
				async:false,
				data: "q=login&email="+$('#login_email').val()+"&password="+$('#login_password').val(),
				success: function(data){
					if(data==1){
						$("#login_error").fadeTo(200,0.1,function(){ 
							//add message and change the class of the box and start fading
							$(this).html('<div class="alert alert-success">Logging in.....</div>').fadeTo(900,1,
							function() { 
								document.location='index.php';
							});			
						});
					}
					else if(data==3){
						$("#login_error").fadeTo(200,0.1,function(){ 
							$(this).html('<div class="alert alert-danger">You are not authenticated by the system. Please type Email/Password correctly.</div>').fadeTo(900,1);
						});
					}
					else if(data == 2){
						$("#login_error").fadeTo(200,0.1,function(){ 
							$(this).html('<div class="alert alert-danger">Your password is WRONG.</div>').fadeTo(900,1);
						});					
					}
				 }	
			});
			return false;
		}
	});
	

	
	
	// Registration
	$("#registration_form").submit(function(){
		event.preventDefault();		
		var formData = new FormData($('#registration_form')[0]);
		formData.append("q","insert_customer");
		
		if($.trim($('#customer_name').val()) == ""){
			success_or_error_msg('#regitration_error','danger',"Please Insert Name","#customer_name");			
		}
		else if($.trim($('#personal_code').val()) == ""){
			success_or_error_msg('#regitration_error','danger',"Please Insert Personal Code","#personal_code");			
		}
		else if($.trim($('#email').val()) == ""){
			success_or_error_msg('#regitration_error','danger',"Please Insert Email","#email");			
		}
		else if($.trim($('#password').val()) == ""){
			success_or_error_msg('#regitration_error','danger',"Please Insert Password","#password");			
		}
		else{
			$.ajax({
				url: "controller/customerController.php",
				type:'POST',
				data:formData,
				async:false,
				cache:false,
				contentType:false,processData:false,
				success: function(data){
					if($.trim(data) == 'match'){
						success_or_error_msg('#regitration_error',"danger","Please Insert Unique Email id & Personal Code","#email" );			
					}
					else if($.isNumeric(data)==true && data == 1){
						success_or_error_msg('#regitration_error',"success","Registration Successfully. Please login to access your account");
						$("#registration_form").trigger('reset');
					}
					else{
						success_or_error_msg('#regitration_error',"danger","Registration Error");												
					}
					
				}	
			});
		}	
	});
});


</script>


