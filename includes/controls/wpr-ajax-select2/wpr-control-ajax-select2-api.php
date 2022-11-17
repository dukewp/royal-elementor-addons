<?php 
namespace ElementsKit_Lite\Core;

class Wpr_Control_Ajax_Select2_Api {

	public function __construct() {
		$this->init();
	}

	public function init() {
		add_action( 'rest_api_init', function() {
			register_rest_route(
				'wpraddons/v1/ajaxselect2',
				'/get_posts_by_post_type/',
				[
					'methods' => \WP_REST_Server::ALLMETHODS,
					'callback' =>  [$this, 'callback'],
					'permission_callback' => '__return_true'
				]
			);
		} );
	}

	public function callback( ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;   
		}

		$query_args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 15,
		);

		if ( isset( $this->request['ids'] ) ) {
			$ids                    = explode( ',', $this->request['ids'] );
			$query_args['post__in'] = $ids;
		}
		if ( isset( $this->request['s'] ) ) {
			$query_args['s'] = $this->request['s'];
		}

		$query   = new \WP_Query( $query_args );
		$options = array();
		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) {
				$query->the_post();
				$options[] = array(
					'id'   => get_the_ID(),
					'text' => get_the_title(),
				);
			}
		endif;

		return array( 'results' => $options );
		wp_reset_postdata();
	}

}

new Wpr_Control_Ajax_Select2_Api();
