<?php
function yocommerce_admin_get_requests(){ // admin panel
     if (isset($_POST['yocommerce_paypal_username']) && isset($_POST['yocommerce_paypal_password'])
		&& isset($_POST['yocommerce_paypal_signature']) && isset($_POST['yocommerce_paypal_sandbox']) ) {
       		 
		 global $wpdb;
		 // clear table
         $wpdb->query("TRUNCATE TABLE `wp_plugin_yocommerce_settings`");
		 // write new first string
	     $wpdb->insert("wp_plugin_yocommerce_settings", array(
		  "setting_paypal_username" =>$_POST['yocommerce_paypal_username'],
		  "setting_paypal_password" => $_POST['yocommerce_paypal_password'],
		  "setting_paypal_signature" => $_POST['yocommerce_paypal_signature'],
          "setting_paypal_sandbox_flag" => $_POST['yocommerce_paypal_sandbox']
	   ));
	}
    
}
add_action( 'admin_init', 'yocommerce_admin_get_requests' ); 

function register_yocommerce_admin_page(){
	add_menu_page( 'YoCommerce', 'YoCommerce', 'manage_options', 'yocommerce', 'yocommerce_admin_page', plugins_url( 'images/icon.png' ) ); 
}

function yocommerce_admin_page(){
 	
	global $wpdb;
		
	$settings = $wpdb->get_results( "SELECT * FROM `wp_plugin_yocommerce_settings` " );
        $settings = $settings[0];
	?>
	<style type="text/css">
			   #yocommerce_paypal_input{width:500px;}
			   #yocommerce_admin_save_button{width:100px;}
	</style>
		
	<h2>YoCommerce - Admin page</h2>
        
        <?
        
        ?>
	
	<h4>Paypal settings</h4>
	<form id="yocommerce_admin_form" action="#" method="POST">
	  UserName:<br>
	  <input type="text" id="yocommerce_paypal_input" name="yocommerce_paypal_username" value="<? echo $settings->setting_paypal_username; ?>">
	  <br>
	  Password:<br>
	  <input type="text" id="yocommerce_paypal_input" name="yocommerce_paypal_password" value="<? echo $settings->setting_paypal_password; ?>">
	  <br>
	  Signature:<br>
	  <input type="text" id="yocommerce_paypal_input" name="yocommerce_paypal_signature" value="<? echo $settings->setting_paypal_signature; ?>">
	  <br>
	  Sandbox:<br>
	  <?
	  if ( $settings->setting_paypal_sandbox_flag == "true") { ?>
		  <input type="radio" name="yocommerce_paypal_sandbox" value="true" checked="checked" >true
	      <input type="radio" name="yocommerce_paypal_sandbox" value="false" >false
	  <?
	   } else  {
	  ?>
	  	  <input type="radio" name="yocommerce_paypal_sandbox" value="true" >true
	      <input type="radio" name="yocommerce_paypal_sandbox" value="false" checked="checked">false
	  <?
	  }
	  ?>  
	  
          <?
	  $other_attributes = array( 'id' => 'yocommerce_admin_save_button' );
        submit_button( 'Save Settings', 'primary', 'yocommerce_admin_save_button', true, $other_attributes );
        ?>
	</form>
	
	
	<?php
	
	
    // buy products
    if (!isset($_GET['info_order_id'])) {
		  echo '<h4>Orders</h4>';
	 	  
                  global $wpdb; 
		  $orders = $wpdb->get_results( "SELECT * FROM `wp_plugin_yocommerce_orders` " );
			   echo 'ID DATE SUMM <br>';
			   
		  for ($i = 0; $i < count($orders); $i++) {
			  echo $orders[$i]->order_order_id .' ' .  $orders[$i]->order_date . '';
			  echo '<a href="?page=yocommerce&info_order_id=' . $orders[$i]->order_order_id . '">details</a><br>';
		  }
                  
               
	
	} else {
          
	 
	     $order_id = (int) $_GET['info_order_id'];
	     $order = $wpdb->get_results( "SELECT * FROM `wp_plugin_yocommerce_orders`, `wp_plugin_yocommerce_goods` WHERE `wp_plugin_yocommerce_orders`.`order_order_id`=`wp_plugin_yocommerce_goods`.`goods_order_id` AND `wp_plugin_yocommerce_orders`.`order_order_id`='" . $order_id . "' ; ");
                          
               echo '<br><br><b>Buyer\'s Info</b><br>';
               echo $order[0]->order_name . '<br>';
               echo $order[0]->order_email . '<br>';
               echo $order[0]->order_phone . '<br>';
               echo $order[0]->order_address . '<br>';
             
		 echo '<br><a href="/wp-admin/admin.php?page=yocommerce">Back</a>';
		 echo '<h4>Order ' . $order_id . '</h4>';
                 
                
                 
                 
                 
		 echo 'Goods: <br>';
		  
          for ($i = 0; $i < count($order); $i++) {
			  echo '<a href="'. get_permalink($order[$i]->goods_goods_id) . '" target=_blank>' . get_the_title($order[$i]->goods_goods_id) . '</a> ' . $order[$i]->order_summ . '$ <br>';
		  }
                  
                  
	}
        
       
            
        
}
add_action( 'admin_menu', 'register_yocommerce_admin_page' );





?>
