<?php

/**
 * Linkolo_Widget Class
 */
class Linkolo_widget extends WP_Widget {

    function __construct() {
        parent::__construct(/* Base ID */'linkolo_widget', /* Name */ 'Linkolo', array('description' => 'Box links in system Linkolo.pl'));
    }

    function widget($args, $instance) {

        $spwr_file = get_option(SPWR_FILE_NAME_LABEL);
        require_once $spwr_file;

        if (strlen($links = spwrPrintLink($instance['separator'])) > 0) {

            extract($args);
            $title = apply_filters('widget_title', $instance['title']);
            echo $before_widget;
            if ($title)
                echo $before_title . $title . $after_title;

            echo '<div class="'.$instance['classname'].'">'.$links.'</div>';
            
            echo $after_widget;
        }
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['separator'] = $new_instance['separator'];
        $instance['classname'] = $new_instance['classname'];
        return $instance;
    }

    function form($instance) {
        if ($instance) {
            $title = esc_attr($instance['title']);
            $separator = esc_attr($instance['separator']);
            $classname = esc_attr($instance['classname']);
        } else {
            $title = __('Good links', 'text_domain');
            $separator = __('<br />', 'text_domain');
            $classname = __('links', 'text_domain');
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('separator'); ?>"><?php _e('Links separator:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('separator'); ?>" name="<?php echo $this->get_field_name('separator'); ?>" type="text" value="<?php echo $separator; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('classname'); ?>"><?php _e('Css classname of box with links:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('classname'); ?>" name="<?php echo $this->get_field_name('classname'); ?>" type="text" value="<?php echo $classname; ?>" />
        </p>
        <?php
    }

}