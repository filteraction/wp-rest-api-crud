<?php 
/*
Plugin Name: Rest Api
*/
if ( ! defined( 'ABSPATH' ) ) {
exit; 
}

define("API_URL", plugin_dir_url( __FILE__ ));
define("API_ROOT_URI", plugins_url( __FILE__ ));
define("API_ADMIN_URI", admin_url());
define("API_PATH", __DIR__);
define('API_PLUGIN', plugin_basename( __FILE__ ));

// echo API_URL.'<br>';
// echo API_ROOT_URI.'<br>';
// echo API_ADMIN_URI.'<br>';
// echo API_PATH.'<br>';
// echo API_PLUGIN.'<br>';


class RestApi{

	public function __construct()
	{
		
		add_action( 'admin_menu', array( $this, 'crete_menu_page') );
		add_action( 'wp_head', array( $this, 'upl_ajaxurl' ) );
		add_action( 'wp_ajax_update_custom_posts', array( $this,'update_my_custom_post' ) );
		// add_action( 'wp_ajax_nopriv_update_custom_posts', array($this,'update_my_custom_post' ) );
		
	}

	public function get_custom_posts(){
				//http://localhost/notebook/wp-json/wp/posts/?_embed
		$wp_request_url = site_url().'/wp-json/wp/v2/blog?_embed';

		$wp_request_headers = array('Authorization' => 'Basic ' . base64_encode( 'mynotebook:mynotebook' ));
		// print_r($wp_request_headers);
		$body = array('title' => 'Lorem Ipsum ', 'content'=>'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.');

		$wp_posts = wp_remote_request(
		  $wp_request_url,
		  array(
		      'method'    => 'GET',
		      'headers'   => $wp_request_headers
		      // 'body'      => $body
		  )
		);
		// echo wp_remote_retrieve_response_code( $wp_posts ) . ' ' . 
		// wp_remote_retrieve_response_message( $wp_posts );

		$posts = json_decode($wp_posts['body'], true);
		return $posts;
	}


	public function update_my_custom_post(){

		$id = $_POST['id'];
		$title = $_POST['title'];
		$content = $_POST['contet'];



		$wp_request_url = site_url().'/wp-json/wp/v2/blog/'.$id;

		$wp_request_headers = array('Authorization' => 'Basic ' . base64_encode( 'mynotebook:mynotebook' ));
		// print_r($wp_request_headers);
		$body = array('title' => $title, 'content'=>$content, 'status'=>'publish');

		$wp_posts = wp_remote_request(
		  $wp_request_url,
		  array(
		      'method'    => 'POST',
		      'headers'   => $wp_request_headers,
		      'body'      => $body
		  )
		);
		// echo wp_remote_retrieve_response_code( $wp_posts ) . ' ' . 
		// wp_remote_retrieve_response_message( $wp_posts );

		// $posts = json_decode($wp_posts['body'], true);
		// return $posts;

		// echo json_encode($_POST);

		echo wp_remote_retrieve_response_message( $wp_posts );
		die;

	}

	public function upl_ajaxurl(){
		?>
			<script type="text/javascript">
			  var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
			</script>
		<?php
		}


	public function crete_menu_page(){
		$page_title = 'Rest';
		$menu_title = 'Rest';
		$capability = 'manage_options';
		$menu_slug = 'rest';
		$callback = array($this, 'crete_menu');
		$icon = 'dashicons-tickets';
		$postion = 5;
		add_menu_page($page_title, $menu_title, $capability, $menu_slug, $callback, $icon, $postion);

	}

	public function crete_menu(){
		include 'html.php';

	}



}

$obj = new RestApi;