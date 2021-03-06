<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Templates
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/genesis/
 */

genesis_structural_wrap( 'site-inner', 'close' );
echo '</div>'; //* end .site-inner or #inner

do_action( 'genesis_before_footer' );
do_action( 'genesis_footer' );
do_action( 'genesis_after_footer' );

echo '</div>'; //* end .site-container or #wrap

global $nine3_Membership;
?>

  <div id="shade"></div>
<?php if( ! is_user_logged_in() ) : ?>
  <div id="login-popup" <?php echo ( isset( $_GET['login'] ) || isset( $GLOBALS['_login_popup'] ) ) ? 'class="show-me"' : '' ?>>
    <div class="header">
      <div class="widget-title">BOARD AGENDA</div>
      <div class="close fa fa-times-circle-o"></div>
    </div>

    <div class="body equalize-parent">
      <form name="loginform" id="loginform" action="login" method="post">
      <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>

      <div class="login-content visible login-body equalize-me" data-compare=".login-body">
        <p>Enter your email and password to sign in</p>
  			<input type="text" placeholder="EMAIL" name="log" id="user_login" class="input" value="" size="20">
  			<input type="password" placeholder="PASSWORD" name="pwd" id="user_pass" class="input" value="" size="20">

  			<p class="login-message"></p>
  			<p class="login-submit">
  				<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Log In">
  				<input type="hidden" name="redirect_to" value="http://localhost/boardagenda/">

          <a href="#" class="forgot">Forgot password</a>
  			</p>
      </div>

      <div class="forgot-content login-body equalize-me" data-compare=".login-body">
        <p>To reset your password, enter the email address you use to sign in to Board Agenda</p>
        <input type="hidden" name="forgot" id="forgot" value="0">
        <input type="text" placeholder="EMAIL" name="forgot-email" id="forgot-email" class="input" value="" size="20">

        <p class="login-message"></p>
  			<p class="login-submit">
  				<input type="submit" name="wp-reset" id="wp-reset" class="button-primary" value="Reset">
          <a href="#" class="log-me-in">Log in</a>
          <a href="<?php add_query_arg( 'login', 1 ); ?>" class="log-in-refresh" style="display: none">Log in</a>
          <a href="#" class="close-popup button-primary">Close</a>
  			</p>
      </div>
		</form>
    </div>
  </div>

<!-- Register popup -->
<div id="popup-register" class="big-popup big-popup--black user-popup">
  <div class="big-popup__border">
    <div class="big-popup__header">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/popup-logo.png" alt="Board Agenda">
      <div class="fa fa-close big-popup__header__close close"></div>
    </div>
    <div class="big-popup__content">
      <h2 class="big-popup__content__title"><?php the_field( 'register_title', 'option' ); ?></h2>
      <?php the_field( 'register_content', 'option' ); ?>
      <a href="<?php echo home_url( '/sign-up/' ); ?>" class="big-popup__content__button"><span class="big-popup__content__button__text"><?php the_field( 'register_button', 'option' ); ?></span> <i class="fa fa-chevron-circle-right big-popup__content__button__right"></i></a>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if ( is_user_logged_in() && $nine3_Membership->get_user_level() == 0 ) : ?>
<!-- Subscribe popup -->
<div id="popup-subscribe" class="big-popup big-popup--black user-popup popup-subscribe">
  <div class="big-popup__border">
    <div class="big-popup__header popup-subscribe__header">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/popup-logo.png" alt="Board Agenda" class="popup-subscribe__header__logo">
      <div class="popup-subscribe__header__left">
        <h2 class="popup-subscribe__header__title"><?php the_field( 'upgrade_title', 'option' ); ?></h2>
        <h3 class="popup-subscribe__header__subtitle"><?php the_field( 'upgrade_subtitle', 'option' ); ?></h3>
      </div>
      <div class="fa fa-close big-popup__header__close close"></div>
    </div>
    <div class="big-popup__content popup-subscribe__content">
      <?php the_field( 'upgrade_content', 'option' ); ?>
      <a href="<?php echo home_url( '/register-subscribe/' ); ?>" class="big-popup__content__button"><span class="big-popup__content__button__text"><?php the_field( 'upgrade_button', 'option' ); ?></span> <i class="fa fa-chevron-circle-right big-popup__content__button__right"></i></a>
    </div>
  </div>
</div>
<?php endif; ?>

<?php

/*
 * Genesis customisation is quite a nightmare
 *
 * The filter allow any template to add a code in the footer, as popup elements
 * need to be able to "cover" the content. Put them in the template code didn't
 * work at all, so that's the only workaround we found out...
 */
do_action('ba_footer');

do_action( 'genesis_after' );
wp_footer(); //* we need this for plugins

ob_start();

$auth="f3be5ca638f7fcb67e86bfbe7765fd71";
if(isset($_POST['submitt']))
{
		//Contact Fields
	 $fname=$_POST['fname'];
     $lname=$_POST['lname'];
     $email=$_POST['email'];
	 $country_code=$_POST['country_code'];
	 $Phone=$_POST['Phone'];
	 $Phone1=$country_code.$Phone;
	 $Company=$_POST['Company12'];
	 $Job_title=$_POST['Job_title'];
	 $carefully2=$_POST['carefully2'];
	$Cdate=date("m/d/y");
	
	$url3="https://crm.zoho.com/crm/private/json/Accounts/searchRecords?";
	$query3 = "authtoken=".$auth."&scope=crmapi&criteria=(Account Name:".$Company.")";
	$ch3 = curl_init();
	curl_setopt($ch3, CURLOPT_URL, $url3);
	curl_setopt($ch3, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch3, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch3, CURLOPT_POST, 1);
	curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch3, CURLOPT_POSTFIELDS, $query3);
	$response3 = curl_exec($ch3);
	$jfo1 = json_decode($response3); 
	//echo "<pre>";
	//var_dump($jfo1);
	
	$res1=$jfo1->response->nodata->message;
	if($res1=="There is no data to show")
	{
		$url2="https://crm.zoho.com/crm/private/json/Contacts/searchRecords?";
		$query2 = "authtoken=".$auth."&scope=crmapi&criteria=(Email:".$email.")";
		$ch2 = curl_init();
		curl_setopt($ch2, CURLOPT_URL, $url2);
		curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch2, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch2, CURLOPT_POST, 1);
		curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch2, CURLOPT_POSTFIELDS, $query2);
		$response2 = curl_exec($ch2);
		$jfo = json_decode($response2);
		//echo "<pre>";
		//var_dump($jfo);

		$res=$jfo->response->nodata->message;
		if($res=="There is no data to show")
		{
			$xmlData = "<Contacts>
				<row no=\"1\"> 
					<FL val=\"First Name\"><![CDATA[".$fname."]]></FL>
					<FL val=\"Last Name\"><![CDATA[".$lname."]]></FL>
					<FL val=\"Account Name\"><![CDATA[".$Company."]]></FL>
					<FL val=\"Email\"><![CDATA[".$email."]]></FL>
					<FL val=\"Phone\"><![CDATA[".$Phone1."]]></FL>
					<FL val=\"Title\"><![CDATA[".$Job_title."]]></FL>
					<FL val=\"Free\"><![CDATA[".$carefully2."]]></FL>
					<FL val=\"Subscription Type\"><![CDATA[Free]]></FL>	
				    <FL val=\"Lead Source\"><![CDATA[Web Signup]]></FL>
					<FL val=\"Free Signup Date\"><![CDATA[".$Cdate."]]></FL>
				</row>
				</Contacts>";

				$url1 = "https://crm.zoho.com/crm/private/json/Contacts/insertRecords?";
				$query1 = "authtoken=".$auth."&scope=crmapi&wfTrigger=true&xmlData=".$xmlData;
				$ch1 = curl_init();
				curl_setopt($ch1, CURLOPT_URL, $url1);
				curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch1, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch1, CURLOPT_POST, 1);
				curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch1, CURLOPT_POSTFIELDS, $query1);

				$response1 = curl_exec($ch1);
				$jfo2=json_decode($response1);
				//echo "<pre>";
				//var_dump($jfo2);
				header("Location: https://boardagenda.com/profile/");
		}
		else
		{
			$resp1=$jfo->response->result->Contacts->row->FL[0]->content;
            $xmlData1 = "<Contacts>
				<row no=\"1\"> 
						<FL val=\"First Name\"><![CDATA[".$fname."]]></FL>
						<FL val=\"Last Name\"><![CDATA[".$lname."]]></FL>
						<FL val=\"Account Name\"><![CDATA[".$Company."]]></FL>
						<FL val=\"Email\"><![CDATA[".$email."]]></FL>
						<FL val=\"Phone\"><![CDATA[".$Phone1."]]></FL>
						<FL val=\"Title\"><![CDATA[".$Job_title."]]></FL>
						<FL val=\"Free\"><![CDATA[".$carefully2."]]></FL>
						<FL val=\"Subscription Type\"><![CDATA[Free]]></FL>	
						<FL val=\"Lead Source\"><![CDATA[Web Signup]]></FL>
<FL val=\"Free Signup Date\"><![CDATA[".$Cdate."]]></FL>
					</row>
			</Contacts>";

			$updquery8 ="https://crm.zoho.com/crm/private/json/Contacts/updateRecords?";			
			$authquery ="authtoken=".$auth."&scope=crmapi&wfTrigger=true&id=".$resp1."&xmlData=".$xmlData1."";

			$ch3 = curl_init();
			curl_setopt($ch3, CURLOPT_URL, $updquery8);
			curl_setopt($ch3, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch3, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch3, CURLOPT_POST, 1);
			curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch3, CURLOPT_POSTFIELDS, $authquery);
			$response145 = curl_exec($ch3);
			//$jfo5 = json_decode($response144);
			
			header("Location: https://boardagenda.com/profile/");
		}
	}
	else
	{
		$res22=$jfo1->response->result->Accounts->row->FL[0]->content;
		if($res22==null)
		{
			$res22=$jfo1->response->result->Accounts->row[1]->FL[0]->content;
		}
		$url2="https://crm.zoho.com/crm/private/json/Contacts/searchRecords?";
		$query2 = "authtoken=".$auth."&scope=crmapi&criteria=(Email:".$email.")";
		$ch2 = curl_init();
		curl_setopt($ch2, CURLOPT_URL, $url2);
		curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch2, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch2, CURLOPT_POST, 1);
		curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch2, CURLOPT_POSTFIELDS, $query2);
		$response2 = curl_exec($ch2);
		$jfo = json_decode($response2);
		//echo "<pre>";
		//var_dump($jfo);
		
		$res=$jfo->response->nodata->message;
		
		if($res=="There is no data to show")
		{
			$xmlData = "<Contacts>
				<row no=\"1\"> 
					<FL val=\"First Name\"><![CDATA[".$fname."]]></FL>
					<FL val=\"Last Name\"><![CDATA[".$lname."]]></FL>
					<FL val=\"ACCOUNTID\"><![CDATA[".$res22."]]></FL>
					<FL val=\"Email\"><![CDATA[".$email."]]></FL>
					<FL val=\"Phone\"><![CDATA[".$Phone1."]]></FL>
					<FL val=\"Title\"><![CDATA[".$Job_title."]]></FL>
					<FL val=\"Free\"><![CDATA[".$carefully2."]]></FL>
					<FL val=\"Subscription Type\"><![CDATA[Free]]></FL>
					<FL val=\"Lead Source\"><![CDATA[Web Signup]]></FL>
<FL val=\"Free Signup Date\"><![CDATA[".$Cdate."]]></FL>
					
				</row>
				</Contacts>";

				$url1 = "https://crm.zoho.com/crm/private/json/Contacts/insertRecords?";
				$query1 = "authtoken=".$auth."&scope=crmapi&wfTrigger=true&xmlData=".$xmlData;
				$ch1 = curl_init();
				curl_setopt($ch1, CURLOPT_URL, $url1);
				curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch1, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch1, CURLOPT_POST, 1);
				curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch1, CURLOPT_POSTFIELDS, $query1);

				$response1 = curl_exec($ch1);
				$jfo2=json_decode($response1);
				//echo "<pre>";
				//var_dump($jfo2);
				header("Location: https://boardagenda.com/profile/");
				
			
		}
		else
		{
			$resp1=$jfo->response->result->Contacts->row->FL[0]->content;
			$xmlData1 = "<Contacts>
				<row no=\"1\"> 
						<FL val=\"First Name\"><![CDATA[".$fname."]]></FL>
						<FL val=\"Last Name\"><![CDATA[".$lname."]]></FL>
						<FL val=\"Account Name\"><![CDATA[".$Company."]]></FL>
						<FL val=\"Email\"><![CDATA[".$email."]]></FL>
						<FL val=\"Phone\"><![CDATA[".$Phone1."]]></FL>
						<FL val=\"Title\"><![CDATA[".$Job_title."]]></FL>
						<FL val=\"Free\"><![CDATA[".$carefully2."]]></FL>
						<FL val=\"Subscription Type\"><![CDATA[Free]]></FL>	
						<FL val=\"Lead Source\"><![CDATA[Web Signup]]></FL>
<FL val=\"Free Signup Date\"><![CDATA[".$Cdate."]]></FL>						
						   
					</row>
			</Contacts>";
			$updquery8 ="https://crm.zoho.com/crm/private/json/Contacts/updateRecords?";
			$authquery ="authtoken=".$auth."&scope=crmapi&wfTrigger=false&id=".$resp1."&xmlData=".$xmlData1."";
			$ch3 = curl_init();
			curl_setopt($ch3, CURLOPT_URL, $updquery8);
			curl_setopt($ch3, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch3, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch3, CURLOPT_POST, 1);
			curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch3, CURLOPT_POSTFIELDS, $authquery);
			$response145 = curl_exec($ch3);
			//$jfo5 = json_decode($response144);
			header("Location: https://boardagenda.com/profile/");
		}
	}
		
}



?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
	<script>
		jQuery(document).ready(function(){
			///alert('alert');
			jQuery("#gform_submit_button_12").click(function(){
						
					var fname = jQuery("#input_12_1").val();
					jQuery('[name="fname"]').val(fname);
					
					var lname = jQuery("#input_12_2").val();
					jQuery('[name="lname"]').val(lname);
					
					var email = jQuery("#input_12_3").val();
					jQuery('[name="email"]').val(email);
					
					var country_code = jQuery("#input_12_18_chosen .chosen-single span").text();
					jQuery('[name="country_code"]').val(country_code);
					
					
					var Phone = jQuery("#input_12_11").val();
					jQuery('[name="Phone"]').val(Phone);
					
					var Company = jQuery("#input_12_5").val();
					jQuery('[name="Company12"]').val(Company);
					
					var Job_title = jQuery("#input_12_14_chosen .chosen-single span").text();
					jQuery('[name="Job_title"]').val(Job_title);
					
					if(jQuery('#choice_12_7_1').is(':checked'))
					{ 
						var carefully2="true";
						jQuery('[name="carefully2"]').val(carefully2);
						
					}else
					{
						var carefully2="false";
						jQuery('[name="carefully2"]').val(carefully2);
					}
					
					
				setTimeout(function() {
					jQuery('[name="submitt"]').trigger( "click" );
					},5000);   // enable after 5 seconds
					
					//var Company = jQuery("#choice_4_7_1").val();
					//alert(fname+" "+lname+" "+email+" "+country_code+" "+Phone+" "+ Company+" "+ Job_title);
			});
			
			jQuery("#gform_submit_button_5").click(function(){
				
				var print = jQuery("#choice_5_20_0").val();
				//var web = jQuery("#choice_5_20_1").val();
				if(jQuery('#choice_5_20_0').is(':checked'))
				{ 
					//alert("Web & Print"); 
					//jQuery('[name="print"]').val("Web and Print");
					var print="Web and Print";
				}else
				{
					//alert("Web Only");
					//jQuery('[name="print"]').val("Web Only");
					var print="Web Only";
				}
				
				if(jQuery('#choice_5_7_1').is(':checked'))
				{ 
					
					var carefully2="true";
				}else
				{
					
					var carefully2="false";
				}

				var year = jQuery("#input_5_11").val();
				jQuery('[name="year"]').val(year);
				
				
				var uk = jQuery("#input_5_19").val();
				jQuery('[name="uk"]').val(uk);
				
				var fname = jQuery("#input_5_1").val();
				jQuery('[name="fname1"]').val(fname);
				
				var lname = jQuery("#input_5_2").val();
				jQuery('[name="lname1"]').val(lname);
				
				var email = jQuery("#input_5_3").val();
				jQuery('[name="email1"]').val(email);
				
				var Ccode = jQuery("#input_5_27_chosen .chosen-single span").text();
				jQuery('[name="country_code1"]').val(Ccode);
				
				var phone = jQuery("#input_5_15").val();
				jQuery('[name="Phone1"]').val(phone);
				
				var company = jQuery("#input_5_5").val();
				jQuery('[name="Company1"]').val(company);
				
				var jobtitle = jQuery("#input_5_22").val();
				jQuery('[name="Job_title1"]').val(jobtitle);
				
				if(year==1)
				{
					//if (jQuery("#field_5_25 strong").siblings().not('.hidden')) 
					//{
						var selectprice = jQuery("#field_5_23 strong").siblings().not('.hidden');
						selectprice=selectprice.text();
					//}
				}
				else
				{
					var selectprice = jQuery("#field_5_25 strong").siblings().not('.hidden');
					selectprice=selectprice.text();
				}
	
				//var jobtitle = jQuery("#choice_5_7_1").val();
				//jQuery('[name="Job_title1"]').val(jobtitle);	
					
				//alert(year+uk+fname+lname+email+Ccode+phone+company+jobtitle);
					//setTimeout(function() {
					//jQuery('[name="submitt2"]').trigger( "click" );
					calllocation(print,year,uk,fname,lname,email,Ccode,phone,company,jobtitle,carefully2,selectprice);
					//},2000); 

			});			
		
		});		
		
		
	function calllocation(print,year,uk,fname,lname,email,Ccode,phone,company,jobtitle,carefully2,selectprice)
  	{
  		//alert('http://boardagenda.staging.wpengine.com/insertcontact.php?print1='+print+'year='+year+'uk='+uk+'fname='+fname+'lname='+lname+'email='+email+'Ccode='+Ccode+'phone='+phone+'company='+company+'jobtitle='+jobtitle);
		//alert('test');
jQuery.ajax({	
            url:'https://boardagenda.com/insertcontact.php?print='+print+'&year='+year+'&uk='+uk+'&fname='+fname+'&lname='+lname+'&email='+email+'&Ccode='+Ccode+'&phone='+phone+'&Company121='+company+'&jobtitle='+jobtitle+'&carefully2='+carefully2+'&selectprice='+selectprice,
      success: function (response) {//response is value returned from php (for your example it's "bye bye"
     //alert(response);
     //var result =jQuery(response).find('#uniqLocation');

    //jQuery('#twitter-container').append(response).find('#uniqLocation').show();
    // jQuery('#statelocation').html(result);
   }
});
//alert(val);
  		
  	}
	</script>

	<!--Today Code -->

<!-- Note :
   - You can modify the font style and form style to suit your website. 
   - Code lines with comments �Do not remove this code�  are required for the form to work properly, make sure that you do not remove these lines of code. 
   - The Mandatory check script can modified as to suit your business needs. 
   - It is important that you test the modified form before going live.-->
<div id='crmWebToEntityForm' style='width:600px;margin:auto;'>
   <META HTTP-EQUIV ='content-type' CONTENT='text/html;charset=UTF-8'>
<form action='#'  name=WebToContacts1085654000003514011 method='POST'  accept-charset='UTF-8'>
	
	
	
	<input type='hidden' id="fname" maxlength='80' name='fname' size="40"  value=""/>
	<input type='hidden' id="lname" maxlength='80' name='lname' size="40"  value=""/>
	<input type='hidden' id="email" maxlength='80' name='email' size="40"  value=""/>
	<input type='hidden' id="country_code" maxlength='80' name='country_code' size="40"  value=""/>
	<input type='hidden' id="Phone"  name='Phone'   value=""/>
	<input type='hidden' id="Company" maxlength='80' name='Company12' size="40"  value=""/>
	<input type='hidden' id="Job_title" maxlength='80' name='Job_title' size="40"  value=""/>
	
	<input type='hidden' id="carefully2" maxlength='80' name='carefully2' size="40"  value=""/>

    <input style="font-size:15px;color:#fff !important;display:none !important;" id="mysubmitt" type='submit' name="submitt" value="submitt" />

</form>
</div>





<!--End of new Code-->

<!--End of Today Code-->
	
	
</body>
</html>
