<!DOCTYPE html>
<html lang="en">
<meta content="IE=edge" http-equiv="X-UA-Compatible" />
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<meta content="width=device-width, initial-scale=1" name="viewport" />
<head>
<title><?php bloginfo('name'); ?></title>
<style type="text/css">
a, a:link, a:visited {
	color:#FF8C00;
	text-decoration: none !important;
}
 a {text-decoration: none !important;}
a:hover, a:active {
	color:#FF8C00!important;
	text-decoration:none;
}
</style>
</head>
<body>
<!-- main container start-->
<div style="margin-top:10px; margin-bottom:10px;"> 
        <!-- middel width container start-->		
	<div style="width:75%; margin:0px auto; text-align:center; "> 
	    <!-- logo container start-->	
	    <div style="border:1px solid none; border-radius:10px 10px 0 0;  margin-bottom: -20px; background-color:#2d5c70;">
        </div>
            <!-- logo container end-->	
            <!-- text container start-->	
	    <div style="background-color:#fff;margin-top:0px; border-left: 1px solid #ccc;border-right: 1px solid #ccc;">      			
		    	 <br>	
		    	 <h2>Welcome to <?php bloginfo('name'); ?></h2>	 		
			 <p>With your new account, you can now create event listings</p><br>
		         <p style="padding:25px;color:#fff;border:1px solid #777;border-radius:5px; width:50%; margin:0px auto;background-color:#000000;"> 
		               <font style="color: #ff7200; font-size:18px;font-family:bitter;">Your login information </font><br>
		               <font style="color: #fff !important; text-decoration:none;"> Email : <?php echo esc_html($user_email); ?></font><br>
			       <font style="color: #fff;"> Username : <?php echo $user_name; ?></font><br>
			  </p>		
	   </div>  			
	</div>
	<!-- middel width container end-->			
  </div>
 <!-- main container end -->
</body>
</html>