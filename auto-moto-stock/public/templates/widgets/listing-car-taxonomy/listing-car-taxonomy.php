<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $extra
 */

$color_scheme = $extra['color_scheme'] ?? 'scheme-dark';
$show_count = $extra['show_count'] ?? 0;
$columns = $extra['columns'] ?? 1;
$count = $extra['show_count'] ?? 0;
$hide_empty = $extra['hide_empty'] ?? 0;
$taxonomy = $extra['taxonomy'] ?? 'type';
$include = array();
$query_args          = array(
    'show_count'   => $count,
    'taxonomy'     => "car-{$taxonomy}",
    'hide_empty'   => $hide_empty,
);

switch ($taxonomy) {
    case 'type':
        $include = $extra['types'] ?? array();
        break;
    case 'status':
        $include = $extra['status'] ?? array();
        break;
    case 'city':
        $include = $extra['cities'] ?? array();
        break;
    case 'styling':
        $include = $extra['stylings'] ?? array();
        break;
    case 'neighborhood':
        $include = $extra['neighborhoods'] ?? array();
        break;
    case 'state':
        $include = $extra['states'] ?? array();
        break;
    case 'label':
        $include = $extra['labels'] ?? array();
        break;
}

if (!empty($include)) {
    $query_args['slug'] = $include;
    $query_args['orderby'] = 'slug__in';
}

$wrapper_classes = array(
  'amotos-widget-listing-car-taxonomy'
);

$wrapper_class = join(' ', $wrapper_classes);
$the_taxonomy = get_categories($query_args);

$list_classes = array(
   'amotos__car-taxonomy-list',
    $color_scheme
);

if ($columns == 2) {
    $list_classes[] = 'amotos__list-2-col';
}

$list_class = join(' ', $list_classes);
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <ul class="<?php echo esc_attr($list_class)?>">
        <?php foreach ($the_taxonomy as $term): ?>
            <li>
                <a title="<?php echo esc_attr($term->name)?>" href="<?php echo esc_url(get_term_link($term,"car-{$taxonomy}"))  ?>">
                    <i class="fa fa-caret-right"></i> <?php echo esc_html($term->name)?>
                    <?php if ($show_count): ?>
                        <span class="item-count">(<?php echo esc_html($term->count)?>)</span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
