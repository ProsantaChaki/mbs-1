<?php session_start();


if(!isset($_SESSION['id']) || $_SESSION['id'] == ""){ ob_start(); header("Location:".$project_url."login.php"); exit();}
else if(!isset($_REQUEST['view']) || $_REQUEST['view'] == ""){ob_start(); header("Location:".$project_url."index.php?module=customer&view=profile"); exit();}
else{
	include("config/dbClass.php");
	$dbClass = new dbClass;		
	$id = $_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mini Banking System</title>   
    <!-- Bootstrap core CSS -->
    <link href="theme/css/bootstrap.min.css" rel="stylesheet">
    <link href="theme/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="theme/css/custom.css" rel="stylesheet">  
    <link href="theme/css/jquery-ui.css" rel="stylesheet">       
    <!--data table-->
    <link href="theme/css/datatables/tools/css/dataTables.tableTools.css" rel="stylesheet">           
	
	
	<script src="theme/js/jquery.min.js"></script>
    <script src="js/static_text.js"></script>
    <script src="js/common.js"></script>
	
</head>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;"> 
                       <a href="" class="site_title" style="font-size:15px !important; font-weight:bold">MBS Dashboard</a>
                    </div>
					<div class="profile">
                        <div class="profile_info">
                            <span><h2><b>Welcome</b> <?php echo $_SESSION['customer_name']; ?></h2><br/></br></span>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <!-- menu prile quick info -->
                    <!-- sidebar menu -->
                     <?php include("view/common_view/left_menu.php"); ?>
                    <!-- /sidebar menu -->
                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Logout" href="logout.php">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>
            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav class="" role="navigation">
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                    <li><a href="index.php"> C_Profile</a></li>
                                    <li><a href="index.php?module=personal&view=profile">  Profile</a></li>
                                    <li><a href="logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->
            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div id="main_container" class="" style="min-height:600px;">
      							<!--
                                
                                All the pages will load here 
                                
                                -->
                            </div>                             
                        </div>
                    </div>
                </div>
                <!-- footer content -->
                <footer>
                    <div class="">
                        <p class="pull-right">
                            <span class="lead"><span class="lead">&copy;  2019 Developed By Momit</span></span>
                        </p>
                    </div>
                    <div class="clearfix"></div>
                </footer>
                <!-- /footer content -->
            </div>
            <!-- /page content -->
        </div>

    </div>

    <div id="custom_notifications" class="custom-notifications dsp_none">
        <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
        </ul>
        <div class="clearfix"></div>
        <div id="notif-group" class="tabbed_notifications"></div>
    </div>


	<div id="wait" style="display:none;width:69px;height:89px;position:absolute;top:50%;left:50%;padding:2px;">	
		 <button class="btn btn-primary btn-lg has-spinner active" disabled >
			<span class="spinner"><i class="fa fa-spinner fa-spin fa-fw "></i></span> <?php echo 'Loading.............'; ?></span>
		</button>
	</div>


  	<!--  <script src="js/post.js"></script>-->
    <script src="theme/js/bootstrap.min.js"></script>
    <script src="theme/js/custom.js"></script> 
    <script src="theme/js/jquery-ui.js"></script> 

    <!-- bootstrap progress js -->
	<script src="theme/js/progressbar/bootstrap-progressbar.min.js" type="text/javascript"></script>
    <script src="theme/js/nicescroll/jquery.nicescroll.min.js" type="text/javascript"> </script>
    
 	

    
<?php
}
?>
</body>
</html>

<script>
    //alert('load index')
$(document).ready(function () {
    //alert('index')

	var customer_id = "<?php echo $_SESSION['id']; ?>";
	
	$('body').on("click", ".dropdown-menu", function (e) {
		$(this).parent().is(".open") && e.stopPropagation();
	});
	
	
});


</script>