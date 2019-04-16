<?php

/**
*
* Adding create new resource from the sidebar widget
*
*/
class AddResource extends WP_Widget{

	/**
	*
	* Constructor
	*
	*/
	public function  __construct(){

		//stting everything
		$widget_ops = array(
			'classname' => 'add_resource',
			'description' => 'This widget allows logged in user with add resources capabilities to add a new resource.'
		);

		parent::__construct('add_resource', 'Add Resource', $widget_ops);

	}

	/**
	*
	* This funciton gets all the data we need to make the widget works
	*
	* @return obejct $data
	*/
	private static function get_widget_data(){
		$data = new stdClass();

		/**
		 * Users cannot register themselves as client or resource partner.
		 * They need a free or premium account first, so $url will redirect them
		 * to /register-subscribe page or /sign-up according to that. It's also used
		 * only for free or premium account...
		 */
		// $url = is_user_logged_in() ? home_url('/sign-up/') : home_url('/register-subscribe/');
    // $url = is_user_logged_in() ? home_url('/sign-up/') : home_url('/register-subscribe/');
    if ( is_user_logged_in() && ( ba_is_resource_partner() || current_user_can( 'manage_options' ) ) ) {
      $url = admin_url( 'post-new.php?post_type=resources' );
    } else {
      $url = home_url( '/sign-up/' );
    }
    //$param = ( is_user_logged_in() && ( ba_is_subscriber() || ba_is_client_user() || current_user_can( 'manage_options' ) ) ) ? '/add-resource/' : '/sign-up/';
		// $data->add_resource_url = (ba_is_client_user() || ba_is_resource_author() || ba_is_resource_partner()) ? admin_url( 'post-new.php?post_type=resources' ) : $url;
    $data->add_resource_url = $url;

		//checkin link behaviour
		$data->link_class = true;

		return $data;

	}

	/**
	*
	* Outpusts the content
	*
	*/
	public function widget($args, $instance){

		//getting widget data
		$data = self::get_widget_data();

		//Displaying HTML

		?>

		<div class="widget-wrap">
			<h4 class="widget-title widgettitle"><?php echo $instance['title']; ?></h4>
			<div class="textwidget">
				<p><?php echo $instance['text']; ?></p>
				<a href="<?php echo $data->add_resource_url; ?>" class="button <?php if(!$data->link_class) echo 'log-me-in'; ?>"><?php echo $instance['link_text']; ?></a>
			</div>
		</div>

		<?php
	}

	/**
	*
	* Output the admin  content
	*
	*/
	public function form( $instance ){

    $title     = '';
    $text      = '';
    $link_text = '';

    if( !empty( $instance['title'] ) )
      $title = $instance['title'];

    if( !empty( $instance['text'] ) )
      $text = $instance['text'];

    if( !empty( $instance['link_text'] ) )
      $link_text = $instance['link_text']; 

    ?>
    <p>
      <label for="<?php echo $this->get_field_name( 'title' ); ?>">Title :</label>
      <input type="text" class="widefat" id="<?php echo  $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>">
    </p>
    <p>
      <label for="<?php echo $this->get_field_name( 'text' ); ?>">Text :</label>
      <textarea class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo $text; ?></textarea>
    </p>
    <p>
      <label for"=<?php echo $this->get_field_name( 'text' ); ?>">Link Text :</label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'link_text' ); ?>" name="<?php echo $this->get_field_name( 'link_text' ); ?>" value="<?php echo $link_text; ?>">
    </p>
    <div class="mfc-text">
      
    </div>
    <?php
		
    echo $args['after_widget'];

	}

  /**
   * This method updates the plugin form values
   */
  public function update( $new_instance, $old_instance ) {
    return $new_instance;
  }

}

function register_add_resource_widget(){
	register_widget('AddResource');
}

add_action('widgets_init', 'register_add_resource_widget');


/**
*
* Adding create new resource from the sidebar widget
*
*/
class AddCompanyProfile extends WP_Widget{

  /**
  *
  * Constructor
  *
  */
  public function  __construct(){

    //stting everything
    $widget_ops = array(
      'classname' => 'add_company_profile',
      'description' => 'Add company profile link'
    );

    parent::__construct('add_company_profile', 'Add Company Profile', $widget_ops);

  }

  /**
  *
  * This funciton gets all the data we need to make the widget works
  *
  * @return obejct $data
  */
  private static function get_widget_data(){
    $data = new stdClass();

    /**
     * Users cannot register themselves as client or resource partner.
     * They need a free or premium account first, so $url will redirect them
     * to /register-subscribe page or /sign-up according to that. It's also used
     * only for free or premium account...
     */
    // $url = is_user_logged_in() ? home_url('/sign-up/') : home_url('/register-subscribe/');
    $url = home_url('/sign-up/');
    // $data->add_resource_url = (ba_is_client_user() || ba_is_resource_author() || ba_is_resource_partner()) ? admin_url( 'post-new.php?post_type=resources' ) : $url; 
    $data->add_resource_url = $url;

    //checkin link behaviour
    $data->link_class = true;

    return $data;

  }

  /**
  *
  * Outpusts the content
  *
  */
  public function widget($args, $instance){

    //getting widget data
    $data = self::get_widget_data();

    //Displaying HTML

    ?>

    <div class="widget-wrap">
      <h4 class="widget-title widgettitle"><?php echo $instance['title']; ?></h4>
      <div class="textwidget">
        <p><?php echo $instance['text']; ?></p>
        <a href="<?php echo $data->add_resource_url; ?>" class="button"><?php echo $instance['link_text']; ?></a>
      </div>
    </div>

    <?php
  }

  /**
  *
  * Output the admin  content
  *
  */
  public function form( $instance ){

    $title     = '';
    $text      = '';
    $link_text = '';

    if( !empty( $instance['title'] ) )
      $title = $instance['title'];

    if( !empty( $instance['text'] ) )
      $text = $instance['text'];

    if( !empty( $instance['link_text'] ) )
      $link_text = $instance['link_text']; 

    ?>
    <p>
      <label for="<?php echo $this->get_field_name( 'title' ); ?>">Title :</label>
      <input type="text" class="widefat" id="<?php echo  $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>">
    </p>
    <p>
      <label for="<?php echo $this->get_field_name( 'text' ); ?>">Text :</label>
      <textarea class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo $text; ?></textarea>
    </p>
    <p>
      <label for"=<?php echo $this->get_field_name( 'text' ); ?>">Link Text :</label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'link_text' ); ?>" name="<?php echo $this->get_field_name( 'link_text' ); ?>" value="<?php echo $link_text; ?>">
    </p>
    <div class="mfc-text">
      
    </div>
    <?php
    
    echo $args['after_widget'];

  }

  /**
   * This method updates the plugin form values
   */
  public function update( $new_instance, $old_instance ) {
    return $new_instance;
  }

}

function register_add_company_profile_widget(){
  register_widget('AddCompanyProfile');
}

add_action('widgets_init', 'register_add_company_profile_widget');


/**
 * Due to WPEngine caching, we can't have a dynamic widget to be displayed wherever
 * we need.
 * The only possible workaround is using JavaScript, so we're creating a widget
 * that allow us to specify the ad to show and on which page show the adver.
 * After the page as been loaded the JS code will make an ajax call, and at
 * this point we will check if display it and what.
 */
class BA_Adzone_Widget extends WP_Widget {
  /**
  * Register widget with WordPress
  */
  function __construct() {
      parent::__construct(
          'BA_Adzone_Widget',  //Base ID
          __( 'Advertising', 'theme-slug'),
          array(
              'description' => __( 'Advertising widget', 'theme-slug' ),
          )
      );
  }

  /**
  * Front-end display of widget.
  *
  * @see WP_Widget::widget()
  *
  * @param array $args     Widget arguments.
  * @param array $instance Saved values from database.
  */
  public function widget( $args, $instance ) {
    $adzone_id = isset( $instance['adzone'] ) ? $instance['adzone'] : '';
    $adzone_show = isset( $instance['adzone-show'] ) ? $instance['adzone-show'] : array();
    $adzone_hide = isset( $instance['adzone-hide'] ) ? $instance['adzone-hide'] : array();

    $adzone_show = join(',', $adzone_show);
    $adzone_hide = join(',', $adzone_hide);
    echo $args['before_widget'];

    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    }

    //Output code goes here
echo <<< WIDGET
  <div class="ba-adzone" data-id="{$adzone_id}" data-show="{$adzone_show}" data-hide="{$adzone_hide}">
  </div>
WIDGET;

      echo $args['after_widget'];
  }

  /**
  * Back-end widget form.
  *
  * @see WP_Widget::form()
  *
  * @param array $instance Previously saved values from database.
  */
  public function form( $instance ) {
    global $pro_ads_adzones, $pro_ads_multisite;

    $args =  array(
      'posts_per_page' => -1,
      'post_type'      => 'page',
    );
    $pages = new WP_Query( $args );
    $adzone_id = isset( $instance['adzone'] ) ? $instance['adzone'] : '';
    $adzone_show = isset( $instance['adzone-show'] ) ? $instance['adzone-show'] : array();
    $adzone_hide = isset( $instance['adzone-hide'] ) ? $instance['adzone-hide'] : array();
    ?>
    <p>
        <label for="<?php echo $this->get_field_id( 'adzone' ); ?>"><?php _e( 'Select your adzone:', 'theme-slug' ); ?></label>

        <select class="widefat" id="<?php echo $this->get_field_id( 'adzone' ); ?>" name="<?php echo $this->get_field_name( 'adzone' ); ?>">
          <option value="">Select your adzone</option>
          <?php
            $adzones = $pro_ads_adzones->get_adzones();
            /***
             * Multisite ___________________________________________________________________ */
            $pro_ads_multisite->wpproads_wpmu_load_from_main_start();
            foreach( $adzones as $i => $adzone ) {
              ?>
              <option value="<?php echo $adzone->ID; ?>" <?php if ( $adzone->ID == $adzone_id ) echo 'selected="selected"'; ?>>
                <?php echo get_the_title($adzone->ID); ?>
                        </option>
                <?php
            }
          ?>
        <select>
    </p>
    <p>
      <label for="">Show widget on:</label>
        <select class="widefat ba-adzone-select" id="<?php echo $this->get_field_id( 'adzone-show' ); ?>" name="<?php echo $this->get_field_name( 'adzone-show' ); ?>[]" multiple="multiple">
          <optgroup label="Pages">
          <?php
            while( $pages->have_posts() ) : $pages->the_post(); ?>
              <option value="<?php echo get_the_ID(); ?>" <?php if ( in_array( get_the_ID(), $adzone_show ) ) echo 'selected="selected"'; ?>>
                <?php the_title(); ?>
              </option>
          <?php endwhile; ?>
          </optgroup>

          <optgroup label="WordPress">
            <option value="front-page" <?php selected( in_array( 'front-page', $adzone_show ), 1 ) ?>>Front page</option>
          </optgroup>
        <select>
    </p>
    <p>
      <label for="">Hide widget on:</label>
        <select class="widefat ba-adzone-select" id="<?php echo $this->get_field_id( 'adzone-hide' ); ?>" name="<?php echo $this->get_field_name( 'adzone-hide' ); ?>[]" multiple="multiple">
          <optgroup label="Pages">
          <?php
            while( $pages->have_posts() ) : $pages->the_post(); ?>
              <option value="<?php echo get_the_ID(); ?>" <?php if (  in_array( get_the_ID(), $adzone_hide ) ) echo 'selected="selected"'; ?>>
                <?php the_title(); ?>
              </option>
          <?php endwhile; ?>
          </optgroup>

          <optgroup label="WordPress">
            <option value="front-page" <?php selected( in_array( 'front-page', $adzone_hide ), 1 ) ?>>Front page</option>
          </optgroup>
        <select>
    </p>
    <script>jQuery('#<?php echo $this->get_field_id( 'adzone-show' ); ?>').select2();</script>
    <script>jQuery('#<?php echo $this->get_field_id( 'adzone-hide' ); ?>').select2();</script>
  <?php
  }

  /**
  * Sanitize widget form values as they are saved.
  *
  * @see WP_Widget::update()
  *
  * @param array $new_instance Values just sent to be saved.
  * @param array $old_instance Previously saved values from database.
  *
  * @return array Updated safe values to be saved.
  */
  public function update( $new_instance, $old_instance ) {
      $instance = array();
      $instance['adzone'] = ( empty( $new_instance['adzone'] ) ) ? '' : strip_tags( $new_instance['adzone'] );
      $instance['adzone-show'] = ( empty( $new_instance['adzone-show'] ) ) ? array() : $new_instance['adzone-show'];
      $instance['adzone-hide'] = ( empty( $new_instance['adzone-hide'] ) ) ? array() : $new_instance['adzone-hide'];

      return $instance;
  }
}

add_action( 'widgets_init', function(){
  register_widget( 'BA_Adzone_Widget' );
});
