<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * @var $car_id
 * @var $data
 */
$wrapper_classes = array(
    'single-car-element',
    'car-walkscore',
    'walkscore-wrap',
    'amotos__single-car-element',
    'amotos__single-car-walk-score'
);
$wrapper_class = join(' ', apply_filters('amotos_single_car_walkscore_wrapper_classes',$wrapper_classes));
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-heading-style2">
        <h2><?php esc_html_e('Walk Score', 'auto-moto-stock'); ?></h2>
    </div>
    <div class="amotos-car-element">
         <?php if ($data): ?>
            <?php if (!empty($data['logo'])): ?>
                 <div class="amotos__logo">
                     <a href="https://www.walkscore.com" target="_blank">
                         <img src="<?php echo esc_url($data['logo'])?>" alt="<?php esc_attr_e('Walk Score', 'auto-moto-stock'); ?>">
                     </a>
                 </div>
            <?php endif; ?>
            <div class="amotos__walk-score-list">
                <?php foreach ($data['items'] as $k => $v): ?>
                    <div class="amotos__walk-score-item">
                        <div class="amotos__score">
                            <?php echo esc_html($v['score'])?>
                        </div>
                        <div class="amotos__info">
                            <div class="amotos__info-inner">
                                <h4 class="amotos__title">
                                    <a target="_blank" title="<?php echo esc_attr($v['title'])?>" href="<?php echo esc_url($v['url'])?>"><?php echo esc_html($v['title'])?></a>
                                </h4>
                                <p class="amotos__desc m-0"><?php echo wp_kses_post($v['desc'])?></p>
                            </div>
                            <a target="_blank" class="amotos__link" href="<?php echo esc_url($v['url'])?>"><?php echo esc_html__('View more', 'auto-moto-stock'); ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
             <?php echo esc_html__('An error occurred while fetching walk scores.', 'auto-moto-stock'); ?>
        <?php endif; ?>
    </div>
</div>
