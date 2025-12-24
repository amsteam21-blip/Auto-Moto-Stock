<?php
/**
 * @var $keyword
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="archive-manager-action">
	<div class="archive-manager-action-item manager-filter">
		<form method="get" action="<?php echo esc_url(get_post_type_archive_link( 'manager' )) ; ?>">
			<div class="form-group input-group search-box"><input type="search" name="manager_name"
			                                                      value="<?php echo esc_attr( $keyword ); ?>"
			                                                      class="form-control"
			                                                      placeholder="<?php esc_attr_e( 'Search...', 'auto-moto-stock' ); ?>">
				<span
						class="input-group-btn"><button type="submit" class="button"><i
								class="fa fa-search"></i></button> </span>
			</div>
		</form>
	</div>
	<div class="archive-manager-action-item sort-view-manager">
		<div class="sort-manager">
			<span class="sort-by"><?php esc_html_e( 'Sort By', 'auto-moto-stock' ); ?></span>
			<ul>
				<li><a data-sortby="a_name" href="<?php
					$pot_link_sortby = add_query_arg( array( 'sortby' => 'a_name' ) );
					echo esc_url( $pot_link_sortby ) ?>"
				       title="<?php esc_attr_e( 'Name (A to Z)', 'auto-moto-stock' ); ?>"><?php esc_html_e( 'Name (A to Z)', 'auto-moto-stock' ); ?></a>
				</li>
				<li><a data-sortby="d_name" href="<?php
					$pot_link_sortby = add_query_arg( array( 'sortby' => 'd_name' ) );
					echo esc_url( $pot_link_sortby ) ?>"
				       title="<?php esc_attr_e( 'Name (Z to A)', 'auto-moto-stock' ); ?>"><?php esc_html_e( 'Name (Z to A)', 'auto-moto-stock' ); ?></a>
				</li>
				<li><a data-sortby="a_date" href="<?php
					$pot_link_sortby = add_query_arg( array( 'sortby' => 'a_date' ) );
					echo esc_url( $pot_link_sortby ) ?>"
				       title="<?php esc_attr_e( 'Date (Old to New)', 'auto-moto-stock' ); ?>"><?php esc_html_e( 'Date (Old to New)', 'auto-moto-stock' ); ?></a>
				</li>
				<li><a data-sortby="d_date" href="<?php
					$pot_link_sortby = add_query_arg( array( 'sortby' => 'd_date' ) );
					echo esc_url( $pot_link_sortby ) ?>"
				       title="<?php esc_attr_e( 'Date (New to Old)', 'auto-moto-stock' ); ?>"><?php esc_html_e( 'Date (New to Old)', 'auto-moto-stock' ); ?></a>
				</li>
			</ul>
		</div>
		<div class="view-as" data-admin-url="<?php echo esc_url( AMOTOS_AJAX_URL ); ?>">
                            <span data-view-as="manager-list" class="view-as-list"
                                  title="<?php esc_attr_e( 'View as List', 'auto-moto-stock' ) ?>">
                                <i class="fa fa-list-ul"></i>
                            </span>
			<span data-view-as="manager-grid" class="view-as-grid"
			      title="<?php esc_attr_e( 'View as Grid', 'auto-moto-stock' ) ?>">
                                <i class="fa fa-th-large"></i>
                            </span>
		</div>
	</div>
</div>
