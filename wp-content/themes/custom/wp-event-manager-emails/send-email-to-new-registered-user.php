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
		    	 <h2 style="font-family:bitter;" >Hey there - welcome to <?php bloginfo('name'); ?>.</h2>	 		
			 <span> <?php $part= explode(" ", get_option( 'blogname' ) ); echo $part[0];?>&#39;s leading event site for all your Events, Business Conferences, Expo & Trade Fairs.</span> <br><br>
			 <p>With your new account, you can now create event listings for your organizer and/or register for events.</p><br>
		         <p style="padding:25px;color:#fff;border:1px solid #777;border-radius:5px; width:50%; margin:0px auto;background-color:#000000;"> 
		               <font style="color: #ff7200; font-size:18px;font-family:bitter;">Your login information </font><br>
		               <font style="color: #fff !important; text-decoration:none;"> Email : <?php echo esc_html($user_email); ?></font><br>
			       <font style="color: #fff;"> Username : <?php echo $user_name; ?></font><br>
			  </p><br>			  
		          <p><?php bloginfo('name'); ?> is the Fastest growing B2B Event Discovery Platform that connects Exhibitors, Organizers and Event Attendees all at one Place.</p>
		          <p>We Mean Business and helps Businesses across the Globe to Find Latest and Most Innovative Events in your Industry.</p><br>		         
		          <p>Get Started : <a href="<?php echo site_url(); ?>" style="color:#F36D00 !important;"><?php bloginfo('name'); ?></a></p><br>
		          <p>we look forward to having you onboard!</p><br>
		              Best wishes,<br>
		              <?php bloginfo('name'); ?>			
		        <br><br>		
	   </div>  
	   <!-- text container end-->
      	   <!-- social container start-->	
			<div style="background-color:#1F1F1F; width:100%; display: inline-block;">
				<h5 style="color:#939393; font-size:12px; font-family:Verdana, Geneva, sans-serif;  ">
					<?php _e( '&quot;We do all the work, and you get all the credit.&quot;', 'wp-event-manager-emails' ); ?></h5>
			
		
			</div>
			<!-- social container end-->			
		      <!-- copyright container start-->	
		      <div>
			   <div style="background-color:#151515;border-radius:0 0 10px 10px;min-height:25px;">
				<h6 style="color:#939393;vertical-align:middle; text-align:center;padding-top:5px; margin:0px;"><?php echo date("Y"); ?> @ <?php bloginfo('name'); ?>, All Right Reserved.</h6>
			  </div>
		     </div>
		     <!-- copyright container end-->				
	</div>
	<!-- middel width container end-->			
  </div>
 <!-- main container end -->
</body>
</html>