<?php
/**
 * Ad zone form.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

$zone = isset( $zone ) && is_object( $zone ) ? $zone : null;

$is_edit = ! empty( $zone );

$action = $is_edit ? 'update' : 'create';

$name = $is_edit ? $zone->name : '';
$slug = $is_edit ? $zone->slug : '';

$location = $is_edit ? $zone->location : 'sidebar';
$width    = $is_edit ? $zone->width : 300;
$height   = $is_edit ? $zone->height : 250;

$pricing_type = $is_edit ? $zone->pricing_type : 'monthly';
$price        = $is_edit ? $zone->price : 0;
$currency     = $is_edit ? $zone->currency : 'USD';

$status     = $is_edit ? $zone->status : 'active';
$sort_order = $is_edit ? $zone->sort_order : 0;

$automatic_display = $is_edit && isset( $zone->automatic_display )
        ? (int) $zone->automatic_display
        : 1;
?>

<div class="wrap">

        <h1>
                <?php
                echo esc_html(
                        $is_edit
                                ? __( 'Edit Ad Zone', 'trade-sphare-ads' )
                                : __( 'Add New Zone', 'trade-sphare-ads' )
                );
                ?>
        </h1>

        <form method="post">

                <input
                        type="hidden"
                        name="tsa_zone_action"
                        value="<?php echo esc_attr( $action ); ?>"
                >

                <?php if ( $is_edit ) : ?>

                        <input
                                type="hidden"
                                name="zone_id"
                                value="<?php echo esc_attr( $zone->id ); ?>"
                        >

                <?php endif; ?>

                <?php wp_nonce_field( 'tsa_zone_action', 'tsa_zone_nonce' ); ?>

                <table class="form-table">

                        <tr>
                                <th>
                                        <label for="tsa-zone-name">
                                                <?php esc_html_e( 'Zone Name', 'trade-sphare-ads' ); ?>
                                        </label>
                                </th>

                                <td>
                                        <input
                                                type="text"
                                                id="tsa-zone-name"
                                                name="name"
                                                value="<?php echo esc_attr( $name ); ?>"
                                                class="regular-text"
                                                required
                                        >
                                </td>
                        </tr>

                        <tr>
                                <th>
                                        <label for="tsa-zone-slug">
                                                <?php esc_html_e( 'Slug', 'trade-sphare-ads' ); ?>
                                        </label>
                                </th>

                                <td>
                                        <input
                                                type="text"
                                                id="tsa-zone-slug"
                                                name="slug"
                                                value="<?php echo esc_attr( $slug ); ?>"
                                                class="regular-text"
                                        >

                                        <p class="description">
                                                <?php esc_html_e( 'Leave empty to generate automatically.', 'trade-sphare-ads' ); ?>
                                        </p>
                                </td>
                        </tr>

                        <tr>
                                <th>
                                        <label for="tsa-zone-location">
                                                <?php esc_html_e( 'Location', 'trade-sphare-ads' ); ?>
                                        </label>
                                </th>

                                <td>
                                        <select
                                                id="tsa-zone-location"
                                                name="location"
                                        >

                                                <option
                                                        value="sidebar"
                                                        <?php selected( $location, 'sidebar' ); ?>
                                                >
                                                        <?php esc_html_e( 'Sidebar', 'trade-sphare-ads' ); ?>
                                                </option>

                                                <option
                                                        value="top"
                                                        <?php selected( $location, 'top' ); ?>
                                                >
                                                        <?php esc_html_e( 'Top of Article', 'trade-sphare-ads' ); ?>
                                                </option>

                                                <option
                                                        value="middle"
                                                        <?php selected( $location, 'middle' ); ?>
                                                >
                                                        <?php esc_html_e( 'Middle of Article', 'trade-sphare-ads' ); ?>
                                                </option>

                                                <option
                                                        value="bottom"
                                                        <?php selected( $location, 'bottom' ); ?>
                                                >
                                                        <?php esc_html_e( 'Bottom of Article', 'trade-sphare-ads' ); ?>
                                                </option>

                                        </select>
                                </td>
                        </tr>

                        <tr>
                                <th>
                                        <label for="tsa-zone-automatic-display">
                                                <?php esc_html_e( 'Automatic Display', 'trade-sphare-ads' ); ?>
                                        </label>
                                </th>

                                <td>
                                        <select
                                                id="tsa-zone-automatic-display"
                                                name="automatic_display"
                                        >

                                                <option
                                                        value="1"
                                                        <?php selected( $automatic_display, 1 ); ?>
                                                >
                                                        <?php esc_html_e( 'Enabled', 'trade-sphare-ads' ); ?>
                                                </option>

                                                <option
                                                        value="0"
                                                        <?php selected( $automatic_display, 0 ); ?>
                                                >
                                                        <?php esc_html_e( 'Disabled', 'trade-sphare-ads' ); ?>
                                                </option>

                                        </select>

                                        <p class="description">
                                                <?php
                                                esc_html_e(
                                                        'Display advertisements from this zone automatically according to its location.',
                                                        'trade-sphare-ads'
                                                );
                                                ?>
                                        </p>
                                </td>
                        </tr>

                        <tr>
                                <th>
                                        <label for="tsa-zone-width">
                                                <?php esc_html_e( 'Width', 'trade-sphare-ads' ); ?>
                                        </label>
                                </th>

                                <td>
                                        <input
                                                type="number"
                                                id="tsa-zone-width"
                                                name="width"
                                                value="<?php echo esc_attr( $width ); ?>"
                                                min="1"
                                        >

                                        <?php esc_html_e( 'px', 'trade-sphare-ads' ); ?>
                                </td>
                        </tr>

                        <tr>
                                <th>
                                        <label for="tsa-zone-height">
                                                <?php esc_html_e( 'Height', 'trade-sphare-ads' ); ?>
                                        </label>
                                </th>

                                <td>
                                        <input
                                                type="number"
                                                id="tsa-zone-height"
                                                name="height"
                                                value="<?php echo esc_attr( $height ); ?>"
                                                min="1"
                                        >

                                        <?php esc_html_e( 'px', 'trade-sphare-ads' ); ?>
                                </td>
                        </tr>

                        <tr>
                                <th>
                                        <label for="tsa-zone-pricing">
                                                <?php esc_html_e( 'Pricing Type', 'trade-sphare-ads' ); ?>
                                        </label>
                                </th>

                                <td>
                                        <select
                                                id="tsa-zone-pricing"
                                                name="pricing_type"
                                        >

                                                <option
                                                        value="monthly"
                                                        <?php selected( $pricing_type, 'monthly' ); ?>
                                                >
                                                        <?php esc_html_e( 'Monthly', 'trade-sphare-ads' ); ?>
                                                </option>

                                                <option
                                                        value="daily"
                                                        <?php selected( $pricing_type, 'daily' ); ?>
                                                >
                                                        <?php esc_html_e( 'Daily', 'trade-sphare-ads' ); ?>
                                                </option>

                                                <option
                                                        value="fixed"
                                                        <?php selected( $pricing_type, 'fixed' ); ?>
                                                >
                                                        <?php esc_html_e( 'Fixed Price', 'trade-sphare-ads' ); ?>
                                                </option>

                                        </select>
                                </td>
                        </tr>

                        <tr>
                                <th>
                                        <label for="tsa-zone-price">
                                                <?php esc_html_e( 'Price', 'trade-sphare-ads' ); ?>
                                        </label>
                                </th>

                                <td>
                                        <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                id="tsa-zone-price"
                                                name="price"
                                                value="<?php echo esc_attr( $price ); ?>"
                                        >
                                </td>
                        </tr>

                        <tr>
                                <th>
                                        <label for="tsa-zone-currency">
                                                <?php esc_html_e( 'Currency', 'trade-sphare-ads' ); ?>
                                        </label>
                                </th>

                                <td>
                                        <input
                                                type="text"
                                                id="tsa-zone-currency"
                                                name="currency"
                                                value="<?php echo esc_attr( $currency ); ?>"
                                                class="small-text"
                                        >
                                </td>
                        </tr>

                        <tr>
                                <th>
                                        <label for="tsa-zone-status">
                                                <?php esc_html_e( 'Status', 'trade-sphare-ads' ); ?>
                                        </label>
                                </th>

                                <td>
                                        <select
                                                id="tsa-zone-status"
                                                name="status"
                                        >

                                                <option
                                                        value="active"
                                                        <?php selected( $status, 'active' ); ?>
                                                >
                                                        <?php esc_html_e( 'Active', 'trade-sphare-ads' ); ?>
                                                </option>

                                                <option
                                                        value="inactive"
                                                        <?php selected( $status, 'inactive' ); ?>
                                                >
                                                        <?php esc_html_e( 'Inactive', 'trade-sphare-ads' ); ?>
                                                </option>

                                        </select>
                                </td>
                        </tr>

                        <tr>
                                <th>
                                        <label for="tsa-zone-sort">
                                                <?php esc_html_e( 'Sort Order', 'trade-sphare-ads' ); ?>
                                        </label>
                                </th>

                                <td>
                                        <input
                                                type="number"
                                                id="tsa-zone-sort"
                                                name="sort_order"
                                                value="<?php echo esc_attr( $sort_order ); ?>"
                                                class="small-text"
                                        >
                                </td>
                        </tr>

                </table>

                <?php
                submit_button(
                        $is_edit
                                ? __( 'Update Zone', 'trade-sphare-ads' )
                                : __( 'Create Zone', 'trade-sphare-ads' )
                );
                ?>

        </form>

</div>