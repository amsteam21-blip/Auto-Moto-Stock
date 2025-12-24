<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Select_Ajax')) {
    class SQUICK_Field_Select_Ajax extends SQUICK_Field
    {
        function enqueue() {
            wp_enqueue_style('selectize');
            wp_enqueue_script('selectize');

            wp_enqueue_script(SQUICK()->assetsHandle('field_select_ajax'), SQUICK()->helper()->getAssetUrl('fields/select_ajax/assets/select-ajax.min.js'), array(), SQUICK()->pluginVer(), true);
        }

        function renderContent()
        {
            $field_value = $this->getFieldValue();
            $place_holder = isset($this->_setting['placeholder']) ? $this->_setting['placeholder'] : '';
            $multiple = isset($this->_setting['multiple']) ? $this->_setting['multiple'] : false;
            $post_type = isset($this->_setting['data']) ? $this->_setting['data'] : 'post';
            $options = array();
            $args = array(
                'post__in' => (array)$field_value,
                'post_type' => $post_type,
                'orderby' => 'post__in'
            );
            $posts = get_posts($args);
            foreach ($posts as $post) {
                $options[$post->ID] = $post->post_title;
            }
            ?>
            <div class="squick-field-select_ajax-inner">
                <select data-field-control=""
                        data-field-no-change="true"
                        data-field-set-value="true"
                        data-drag="true"
                        class="squick-select-ajax repositories"
                        name="<?php $this->theInputName(); ?><?php echo esc_attr($multiple ? '[]' : ''); ?>"
                        data-value="<?php echo esc_attr(is_array($field_value) ? wp_json_encode($field_value) : $field_value) ?>"
                        placeholder="<?php echo esc_attr($place_holder); ?>"
                        data-source="<?php echo esc_attr($post_type); ?>"
                        <?php SQUICK()->helper()->render_attr_iff($multiple, 'multiple', 'multiple'); ?>>
                    <option class="empty-select" value="" <?php SQUICK()->helper()->theSelected('', $field_value); ?>></option>
                    <?php foreach ($options as $opt_key => $opt_val): ?>
                        <option value="<?php echo esc_attr($opt_key); ?>" <?php SQUICK()->helper()->theSelected($opt_key, $field_value); ?>><?php echo esc_html($opt_val); ?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <?php
        }

        /**
         * Get default value
         *
         * @return array | string
         */
        function getDefault() {
            $default = '';
            if (isset($this->_setting['multiple']) && $this->_setting['multiple']) {
                $default = array();
            }
            $field_default = isset($this->_setting['default']) ? $this->_setting['default'] : $default;
            return $field_default;
        }
    }
}