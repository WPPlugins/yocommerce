<?php
// Creating the widget 
class yocommerce_wpb_widget extends WP_Widget {
    
    function __construct() {
    parent::__construct(
    // Base ID of your widget
    'wpb_widget', 
    
    // Widget name will appear in UI
    __('YoCommerce Basket', 'wpb_widget_domain'), 
    
    // Widget description
    array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'wpb_widget_domain' ), ) 
    );
    }
    
    // Creating widget front-end
    // This is where the action happens
    public function widget( $args, $instance ) {
    $title = apply_filters( 'widget_title', $instance['title'] );
    // before and after widget arguments are defined by themes
    echo $args['before_widget'];
    if ( ! empty( $title ) )
    echo $args['before_title'] . $title . $args['after_title'];
    
         
    global $wpdb;
    
    // basket
    $goods_in_basket = $wpdb->get_results ("
                SELECT *,COUNT(`wp_plugin_yocommerce_goods`.`goods_goods_id`) as count
                FROM `wp_plugin_yocommerce_goods`,`wp_posts`
                WHERE `wp_posts`.`ID`=`wp_plugin_yocommerce_goods`.`goods_goods_id`
                       AND `wp_plugin_yocommerce_goods`.`goods_order_id`='" . $_SESSION['yocommerce_order_id'] . "'
                GROUP BY `wp_plugin_yocommerce_goods`.`goods_goods_id`
                ORDER BY `wp_posts`.`ID` DESC        
            ");            
                     
    $count = count($goods_in_basket);
    if ($count > 0) {
  
        
        foreach($_SESSION['yocommerce_buy_errors'] as $error) {
            echo $error . '<br>';
        }
            
            
        /***/

        
         // This is where you run the code and display the output
            echo __( '<center>         
              <form action=\'?yocommerce_buy\' METHOD=\'POST\'>
              Your name<br>
              <input type="text" name="yocommerce_buy_name" value="' . $_SESSION['yocommerce_buy_name'] . '"> <br>
              Your email<br>
              <input type="text" name="yocommerce_buy_email" value="' . $_SESSION['yocommerce_buy_email'] . '"> <br>
              Your phone<br>             
              <input type="text" name="yocommerce_buy_phone" value="' . $_SESSION['yocommerce_buy_phone'] . '">  <br>
                            Your address<br>
              <input type="text" name="yocommerce_buy_address" value="' . $_SESSION['yocommerce_buy_address'] . '"><br><br>  
                         
              <input type=\'image\' name=\'submit\' src=\'/wp-content/plugins/yocommerce/buy.png\' border=\'1\' align=\'top\' alt=\'Check out with PayPal\'/>
              </form>
              </center>
            ', 'wpb_widget_domain' );
        
        $html_show.='<center>';
    
        
        for ($i = 0; $i < $count; $i++) {
           // name of goods
           $costOfAll = $costOfAll + get_post_meta($goods_in_basket[$i]->ID,'cost',true) * $goods_in_basket[$i]->count ;
           $html_show.=$goods_in_basket[$i]->post_title . ' X ' . $goods_in_basket[$i]->count  . '<br>';
        }
      
        $_SESSION['yocommerce_amount'] = $costOfAll;
        $html_show.= 'Total ' .$costOfAll . ' $ <br>';
    
        $html_show.='<a href="/?yocommerce_basket=clear"><input type="submit" name="submit" id="submit" class="button button-primary" value="Empty Basket"  /></a>';
         
        $html_show.='<center>';
              
    echo __( $html_show, 'wpb_widget_domain' );
    
    }
    
    echo $args['after_widget'];
    }
            
    // Widget Backend
    public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) {
    $title = $instance[ 'title' ];
    }
    else {
    $title = __( 'Your basket', 'wpb_widget_domain' );
    }
    // Widget admin form
    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <?php
    }
        
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    return $instance;
    }
    
} // Class wpb_widget ends here


?>