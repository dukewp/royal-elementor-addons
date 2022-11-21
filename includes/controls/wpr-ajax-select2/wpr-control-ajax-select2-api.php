<?php
use WprAddons\Classes\Utilities;

class Wpr_Control_Ajax_Select2_Api {

	public function __construct() {
		$this->init();
	}

	public function init() {
		add_action( 'rest_api_init', function() {
			register_rest_route(
				'wpraddons/v1/ajaxselect2',
				'/(?P<action>\w+)/',
				[
					'methods' => 'GET',
					'callback' =>  [$this, 'callback'],
					'permission_callback' => '__return_true'
				]
			);
		} );
	}

	public function callback( $request ) {
		return $this->{$request['action']}( $request );
	}

	public function get_taxonomies( $request ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;   
		}

		$args = [
			'orderby' => 'name', 
			'order' => 'DESC',
			'hide_empty' => false,
			'number' => 10,
		];

		if ( isset( $request['ids'] ) ) {
			// $request['ids'] = ('' !== $request['ids']) ? $request['ids'] : '1';
			$ids = explode( ',', $request['ids'] );
			$args['include'] = $ids;
		}
		
		if ( isset( $request['s'] ) ) {
			$args['name__like'] = $request['s'];
		}
		
		$tax = isset($request['tax']) ? $request['tax'] : '';

		$options = [];
		$terms = get_terms( $tax, $args );

		if ( ! empty($terms) ) {
			foreach ( $terms as $term ) {
				$options[] = [
					'id'   => $term->term_id,
					'text' => $term->name,
				];
			}
		}

		wp_reset_postdata();

		return [ 'results' => $options, 'terms' => $request['tax'] ];
	}

	public function get_users( $request ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;   
		}

		$args = [
			'number' => '15',
			'blog_id' => 0
		];

		if ( isset( $request['ids'] ) ) {
			$ids = array_map('intval', explode(',', $request['ids'] ));
			$args['include'] = $ids;
		}

		if ( isset( $request['s'] ) ) {
			$args['search'] = '*'. $request['s'] .'*';
		}

		$options = [];
		$user_query = new \WP_User_Query( $args );

		if ( ! empty( $user_query->get_results() ) ) {
			foreach ( $user_query->get_results() as $user ) {
				$options[] = [
					'id' => $user->ID,
					'text' => $user->display_name,
				];
			}
		}

		wp_reset_postdata();

		return [ 'results' => $options ];
	}

	public function callback_bk( $request ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;   
		}

		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 15,
		);

		if ( isset( $request['ids'] ) ) {
			$ids                    = explode( ',', $request['ids'] );
			$args['post__in'] = $ids;
		}
		
		if ( isset( $request['s'] ) ) {
			$args['s'] = $request['s'];
		}

		$query = new \WP_Query( $args );
		$options = array();
		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) {
				$query->the_post();
				$options[] = array(
					'id' => get_the_ID(),
					'text' => get_the_title(),
				);
			}
		endif;

		wp_reset_postdata();

		return array( 'results' => $options );
	}

}

new Wpr_Control_Ajax_Select2_Api();
