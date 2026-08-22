<?php
/**
 * Advertising database table.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles the advertising table.
 */
class TSA_Ads_Table {

	/**
	 * Database table name without prefix.
	 */
	const TABLE_NAME = 'tsa_ads';

	/**
	 * Get complete table name.
	 *
	 * @return string
	 */
	public static function table_name() {
		global $wpdb;

		return $wpdb->prefix . self::TABLE_NAME;
	}

	/**
	 * Create advertising table.
	 *
	 * @return void
	 */
	public static function create_table() {
		global $wpdb;

		$table_name      = self::table_name();
		$charset_collate = $wpdb->get_charset_collate();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$sql = "CREATE TABLE {$table_name} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			zone_id bigint(20) unsigned NOT NULL,
			name varchar(191) NOT NULL,
			title varchar(191) NOT NULL DEFAULT '',
			content longtext NOT NULL,
			image_url text NOT NULL,
			target_url text NOT NULL,
			status varchar(20) NOT NULL DEFAULT 'active',
			sort_order int(11) NOT NULL DEFAULT 0,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (id),
			KEY zone_id (zone_id),
			KEY status (status),
			KEY sort_order (sort_order)
		) {$charset_collate};";

		dbDelta( $sql );
	}

	/**
	 * Get an ad by ID.
	 *
	 * @param int $id Ad ID.
	 * @return object|null
	 */
	public static function get( $id ) {
		global $wpdb;

		$table_name = self::table_name();
		$id         = absint( $id );

		if ( ! $id ) {
			return null;
		}

		return $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table_name} WHERE id = %d LIMIT 1",
				$id
			)
		);
	}

	/**
	 * Get all ads.
	 *
	 * @param array $args Query arguments.
	 * @return array
	 */
	public static function get_all( $args = array() ) {
		global $wpdb;

		$table_name = self::table_name();

		$defaults = array(
			'zone_id' => 0,
			'status'  => '',
			'orderby' => 'sort_order',
			'order'   => 'ASC',
			'limit'   => 100,
			'offset'  => 0,
		);

		$args = wp_parse_args( $args, $defaults );

		$allowed_orderby = array(
			'id',
			'name',
			'title',
			'zone_id',
			'status',
			'sort_order',
			'created_at',
		);

		$orderby = in_array(
			$args['orderby'],
			$allowed_orderby,
			true
		) ? $args['orderby'] : 'sort_order';

		$order = 'DESC' === strtoupper( $args['order'] )
			? 'DESC'
			: 'ASC';

		$where  = array( '1=1' );
		$values = array();

		if ( ! empty( $args['zone_id'] ) ) {
			$where[]  = 'zone_id = %d';
			$values[] = absint( $args['zone_id'] );
		}

		if ( ! empty( $args['status'] ) ) {
			$where[]  = 'status = %s';
			$values[] = sanitize_key( $args['status'] );
		}

		$limit  = absint( $args['limit'] );
		$offset = absint( $args['offset'] );

		if ( $limit < 1 ) {
			$limit = 100;
		}

		$sql = "SELECT * FROM {$table_name}
			WHERE " . implode( ' AND ', $where ) . "
			ORDER BY {$orderby} {$order}
			LIMIT %d OFFSET %d";

		$values[] = $limit;
		$values[] = $offset;

		return $wpdb->get_results(
			$wpdb->prepare( $sql, $values )
		);
	}

	/**
	 * Insert an ad.
	 *
	 * @param array $data Ad data.
	 * @return int|WP_Error
	 */
	public static function insert( $data ) {
		global $wpdb;

		$table_name = self::table_name();
		$now        = current_time( 'mysql' );

		$zone_id = isset( $data['zone_id'] )
			? absint( $data['zone_id'] )
			: 0;

		if ( ! $zone_id || ! TSA_Zones_Table::get( $zone_id ) ) {
			return new WP_Error(
				'tsa_invalid_zone',
				__( 'Please select a valid advertising zone.', 'trade-sphare-ads' )
			);
		}

		$name = isset( $data['name'] )
			? sanitize_text_field( $data['name'] )
			: '';

		if ( empty( $name ) ) {
			return new WP_Error(
				'tsa_invalid_ad_name',
				__( 'Ad name is required.', 'trade-sphare-ads' )
			);
		}

		$title = isset( $data['title'] )
			? sanitize_text_field( $data['title'] )
			: '';

		$content = isset( $data['content'] )
			? wp_kses_post( $data['content'] )
			: '';

		$image_url = isset( $data['image_url'] )
			? esc_url_raw( $data['image_url'] )
			: '';

		$target_url = isset( $data['target_url'] )
			? esc_url_raw( $data['target_url'] )
			: '';

		$status = isset( $data['status'] )
			? sanitize_key( $data['status'] )
			: 'active';

		$sort_order = isset( $data['sort_order'] )
			? intval( $data['sort_order'] )
			: 0;

		$inserted = $wpdb->insert(
			$table_name,
			array(
				'zone_id'     => $zone_id,
				'name'        => $name,
				'title'       => $title,
				'content'     => $content,
				'image_url'   => $image_url,
				'target_url'  => $target_url,
				'status'      => $status,
				'sort_order'  => $sort_order,
				'created_at'  => $now,
				'updated_at'  => $now,
			),
			array(
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%s',
				'%s',
			)
		);

		if ( false === $inserted ) {
			return new WP_Error(
				'tsa_ad_insert_failed',
				__( 'Unable to create the advertisement.', 'trade-sphare-ads' )
			);
		}

		return (int) $wpdb->insert_id;
	}

	/**
	 * Update an ad.
	 *
	 * @param int   $id   Ad ID.
	 * @param array $data Ad data.
	 * @return bool|WP_Error
	 */
	public static function update( $id, $data ) {
		global $wpdb;

		$table_name = self::table_name();
		$id         = absint( $id );

		if ( ! $id ) {
			return new WP_Error(
				'tsa_invalid_ad_id',
				__( 'Invalid ad ID.', 'trade-sphare-ads' )
			);
		}

		if ( ! self::get( $id ) ) {
			return new WP_Error(
				'tsa_ad_not_found',
				__( 'Advertisement not found.', 'trade-sphare-ads' )
			);
		}

		$update = array();
		$format = array();

		if ( isset( $data['zone_id'] ) ) {
			$zone_id = absint( $data['zone_id'] );

			if ( ! $zone_id || ! TSA_Zones_Table::get( $zone_id ) ) {
				return new WP_Error(
					'tsa_invalid_zone',
					__( 'Please select a valid advertising zone.', 'trade-sphare-ads' )
				);
			}

			$update['zone_id'] = $zone_id;
			$format[]         = '%d';
		}

		if ( isset( $data['name'] ) ) {
			$update['name'] = sanitize_text_field( $data['name'] );
			$format[]       = '%s';
		}

		if ( isset( $data['title'] ) ) {
			$update['title'] = sanitize_text_field( $data['title'] );
			$format[]        = '%s';
		}

		if ( isset( $data['content'] ) ) {
			$update['content'] = wp_kses_post( $data['content'] );
			$format[]          = '%s';
		}

		if ( isset( $data['image_url'] ) ) {
			$update['image_url'] = esc_url_raw( $data['image_url'] );
			$format[]            = '%s';
		}

		if ( isset( $data['target_url'] ) ) {
			$update['target_url'] = esc_url_raw( $data['target_url'] );
			$format[]             = '%s';
		}

		if ( isset( $data['status'] ) ) {
			$update['status'] = sanitize_key( $data['status'] );
			$format[]         = '%s';
		}

		if ( isset( $data['sort_order'] ) ) {
			$update['sort_order'] = intval( $data['sort_order'] );
			$format[]             = '%d';
		}

		if ( empty( $update ) ) {
			return true;
		}

		$update['updated_at'] = current_time( 'mysql' );
		$format[]             = '%s';

		$result = $wpdb->update(
			$table_name,
			$update,
			array( 'id' => $id ),
			$format,
			array( '%d' )
		);

		if ( false === $result ) {
			return new WP_Error(
				'tsa_ad_update_failed',
				__( 'Unable to update the advertisement.', 'trade-sphare-ads' )
			);
		}

		return true;
	}

	/**
	 * Delete an ad.
	 *
	 * @param int $id Ad ID.
	 * @return bool|WP_Error
	 */
	public static function delete( $id ) {
		global $wpdb;

		$table_name = self::table_name();
		$id         = absint( $id );

		if ( ! $id ) {
			return new WP_Error(
				'tsa_invalid_ad_id',
				__( 'Invalid ad ID.', 'trade-sphare-ads' )
			);
		}

		$result = $wpdb->delete(
			$table_name,
			array( 'id' => $id ),
			array( '%d' )
		);

		if ( false === $result ) {
			return new WP_Error(
				'tsa_ad_delete_failed',
				__( 'Unable to delete the advertisement.', 'trade-sphare-ads' )
			);
		}

		return true;
	}
}
