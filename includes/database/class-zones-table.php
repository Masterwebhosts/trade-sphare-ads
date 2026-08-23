<?php
/**
 * Advertising zones database table.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles the advertising zones table.
 */
class TSA_Zones_Table {

	/**
	 * Database table name without prefix.
	 */
	const TABLE_NAME = 'tsa_zones';

	/**
	 * Get the complete table name.
	 *
	 * @return string
	 */
	public static function table_name() {
		global $wpdb;

		return $wpdb->prefix . self::TABLE_NAME;
	}

	/**
	 * Create the advertising zones table.
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
			name varchar(191) NOT NULL,
			slug varchar(191) NOT NULL,
			location varchar(50) NOT NULL DEFAULT 'sidebar',
			width smallint(5) unsigned NOT NULL DEFAULT 300,
			height smallint(5) unsigned NOT NULL DEFAULT 250,
			pricing_type varchar(20) NOT NULL DEFAULT 'monthly',
			price decimal(12,2) NOT NULL DEFAULT 0.00,
			currency varchar(10) NOT NULL DEFAULT 'USD',
			status varchar(20) NOT NULL DEFAULT 'active',
			automatic_display tinyint(1) unsigned NOT NULL DEFAULT 1,
			sort_order int(11) NOT NULL DEFAULT 0,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY slug (slug),
			KEY location (location),
			KEY status (status),
			KEY pricing_type (pricing_type),
			KEY automatic_display (automatic_display)
		) {$charset_collate};";

		dbDelta( $sql );
	}

	/**
	 * Get a zone by ID.
	 *
	 * @param int $id Zone ID.
	 * @return object|null
	 */
	public static function get( $id ) {
		global $wpdb;

		$id = absint( $id );

		if ( ! $id ) {
			return null;
		}

		return $wpdb->get_row(
			$wpdb->prepare(
				'SELECT * FROM ' . self::table_name() . ' WHERE id = %d LIMIT 1',
				$id
			)
		);
	}

	/**
	 * Get a zone by slug.
	 *
	 * @param string $slug Zone slug.
	 * @return object|null
	 */
	public static function get_by_slug( $slug ) {
		global $wpdb;

		$slug = sanitize_title( $slug );

		if ( empty( $slug ) ) {
			return null;
		}

		return $wpdb->get_row(
			$wpdb->prepare(
				'SELECT * FROM ' . self::table_name() . ' WHERE slug = %s LIMIT 1',
				$slug
			)
		);
	}

	/**
	 * Get all zones.
	 *
	 * @param array $args Query arguments.
	 * @return array
	 */
	public static function get_all( $args = array() ) {
		global $wpdb;

		$defaults = array(
			'status'   => '',
			'location' => '',
			'orderby'  => 'sort_order',
			'order'    => 'ASC',
			'limit'    => 100,
			'offset'   => 0,
		);

		$args = wp_parse_args( $args, $defaults );

		$allowed_orderby = array(
			'id',
			'name',
			'location',
			'width',
			'height',
			'price',
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

		if ( ! empty( $args['status'] ) ) {
			$where[]  = 'status = %s';
			$values[] = sanitize_key( $args['status'] );
		}

		if ( ! empty( $args['location'] ) ) {
			$where[]  = 'location = %s';
			$values[] = sanitize_key( $args['location'] );
		}

		$limit  = absint( $args['limit'] );
		$offset = absint( $args['offset'] );

		if ( $limit < 1 ) {
			$limit = 100;
		}

		$sql = 'SELECT * FROM ' . self::table_name() .
			' WHERE ' . implode( ' AND ', $where ) .
			" ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d";

		$values[] = $limit;
		$values[] = $offset;

		return $wpdb->get_results(
			$wpdb->prepare( $sql, $values )
		);
	}

	/**
	 * Insert a zone.
	 *
	 * @param array $data Zone data.
	 * @return int|WP_Error
	 */
	public static function insert( $data ) {
		global $wpdb;

		$table_name = self::table_name();
		$now        = current_time( 'mysql' );

		$name = isset( $data['name'] )
			? sanitize_text_field( $data['name'] )
			: '';

		if ( empty( $name ) ) {
			return new WP_Error(
				'tsa_invalid_zone_name',
				__( 'Zone name is required.', 'trade-sphare-ads' )
			);
		}

		/*
		 * Generate the slug from the submitted slug or zone name.
		 *
		 * Arabic and other non-Latin names may result in an empty slug
		 * depending on the WordPress environment. In that case generate
		 * a guaranteed unique fallback slug.
		 */
		$submitted_slug = isset( $data['slug'] )
			? sanitize_title( $data['slug'] )
			: '';

		$slug = $submitted_slug;

		if ( empty( $slug ) ) {
			$slug = sanitize_title( $name );
		}

		if ( empty( $slug ) ) {
			$slug = 'zone-' . wp_generate_password( 8, false, false );
		}

		/*
		 * Make sure the slug is unique.
		 */
		$base_slug = $slug;
		$counter   = 2;

		while ( self::get_by_slug( $slug ) ) {
			$slug = $base_slug . '-' . $counter;
			$counter++;
		}

		$location = isset( $data['location'] )
			? sanitize_key( $data['location'] )
			: 'sidebar';

		$width = isset( $data['width'] )
			? absint( $data['width'] )
			: 300;

		$height = isset( $data['height'] )
			? absint( $data['height'] )
			: 250;

		$pricing_type = isset( $data['pricing_type'] )
			? sanitize_key( $data['pricing_type'] )
			: 'monthly';

		$price = isset( $data['price'] )
			? (float) $data['price']
			: 0;

		$currency = isset( $data['currency'] )
			? strtoupper( sanitize_text_field( $data['currency'] ) )
			: 'USD';

		$status = isset( $data['status'] )
			? sanitize_key( $data['status'] )
			: 'active';

		$automatic_display = isset( $data['automatic_display'] )
			? absint( $data['automatic_display'] )
			: 1;

		$automatic_display = $automatic_display ? 1 : 0;

		$sort_order = isset( $data['sort_order'] )
			? intval( $data['sort_order'] )
			: 0;

		$inserted = $wpdb->insert(
			$table_name,
			array(
				'name'              => $name,
				'slug'              => $slug,
				'location'          => $location,
				'width'             => $width,
				'height'            => $height,
				'pricing_type'      => $pricing_type,
				'price'             => $price,
				'currency'          => $currency,
				'status'            => $status,
				'automatic_display' => $automatic_display,
				'sort_order'        => $sort_order,
				'created_at'        => $now,
				'updated_at'        => $now,
			),
			array(
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
				'%s',
				'%.2f',
				'%s',
				'%s',
				'%d',
				'%d',
				'%s',
				'%s',
			)
		);

		if ( false === $inserted ) {
			/*
			 * Keep the real database error available during development.
			 */
			$error_message = $wpdb->last_error;

			if ( empty( $error_message ) ) {
				$error_message = __(
					'Unable to create the advertising zone.',
					'trade-sphare-ads'
				);
			}

			return new WP_Error(
				'tsa_zone_insert_failed',
				$error_message
			);
		}

		return (int) $wpdb->insert_id;
	}

	/**
	 * Update a zone.
	 *
	 * @param int   $id   Zone ID.
	 * @param array $data Zone data.
	 * @return bool|WP_Error
	 */
	public static function update( $id, $data ) {
		global $wpdb;

		$table_name = self::table_name();
		$id         = absint( $id );

		if ( ! $id ) {
			return new WP_Error(
				'tsa_invalid_zone_id',
				__( 'Invalid zone ID.', 'trade-sphare-ads' )
			);
		}

		$zone = self::get( $id );

		if ( ! $zone ) {
			return new WP_Error(
				'tsa_zone_not_found',
				__( 'Advertising zone not found.', 'trade-sphare-ads' )
			);
		}

		$update = array();
		$format = array();

		if ( isset( $data['name'] ) ) {
			$update['name'] = sanitize_text_field( $data['name'] );
			$format[]       = '%s';
		}

		if ( isset( $data['slug'] ) ) {
			$slug = sanitize_title( $data['slug'] );

			if ( empty( $slug ) ) {
				$slug = $zone->slug;
			}

			$update['slug'] = $slug;
			$format[]       = '%s';
		}

		if ( isset( $data['location'] ) ) {
			$update['location'] = sanitize_key( $data['location'] );
			$format[]           = '%s';
		}

		if ( isset( $data['width'] ) ) {
			$update['width'] = absint( $data['width'] );
			$format[]        = '%d';
		}

		if ( isset( $data['height'] ) ) {
			$update['height'] = absint( $data['height'] );
			$format[]         = '%d';
		}

		if ( isset( $data['pricing_type'] ) ) {
			$update['pricing_type'] = sanitize_key( $data['pricing_type'] );
			$format[]               = '%s';
		}

		if ( isset( $data['price'] ) ) {
			$update['price'] = (float) $data['price'];
			$format[]        = '%.2f';
		}

		if ( isset( $data['currency'] ) ) {
			$update['currency'] = strtoupper(
				sanitize_text_field( $data['currency'] )
			);
			$format[] = '%s';
		}

		if ( isset( $data['status'] ) ) {
			$update['status'] = sanitize_key( $data['status'] );
			$format[]         = '%s';
		}

		if ( isset( $data['automatic_display'] ) ) {
			$update['automatic_display'] = absint(
				$data['automatic_display']
			) ? 1 : 0;

			$format[] = '%d';
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
			$error_message = $wpdb->last_error;

			if ( empty( $error_message ) ) {
				$error_message = __(
					'Unable to update the advertising zone.',
					'trade-sphare-ads'
				);
			}

			return new WP_Error(
				'tsa_zone_update_failed',
				$error_message
			);
		}

		return true;
	}

	/**
	 * Delete a zone.
	 *
	 * @param int $id Zone ID.
	 * @return bool|WP_Error
	 */
	public static function delete( $id ) {
		global $wpdb;

		$table_name = self::table_name();
		$id         = absint( $id );

		if ( ! $id ) {
			return new WP_Error(
				'tsa_invalid_zone_id',
				__( 'Invalid zone ID.', 'trade-sphare-ads' )
			);
		}

		$result = $wpdb->delete(
			$table_name,
			array( 'id' => $id ),
			array( '%d' )
		);

		if ( false === $result ) {
			$error_message = $wpdb->last_error;

			if ( empty( $error_message ) ) {
				$error_message = __(
					'Unable to delete the advertising zone.',
					'trade-sphare-ads'
				);
			}

			return new WP_Error(
				'tsa_zone_delete_failed',
				$error_message
			);
		}

		return true;
	}
}