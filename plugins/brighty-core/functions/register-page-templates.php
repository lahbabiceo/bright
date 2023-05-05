<?php
// Register Templates for user dashboard

/**
 * Add page templates.
 *
 * @param  array  $templates  The list of page templates
 *
 * @return array  $templates  The modified list of page templates
 */

add_filter('theme_page_templates', 'brighty_core_add_page_template_to_dropdown');
add_filter('template_include', 'brighty_core_change_page_template', 99);

    
function brighty_core_add_page_template_to_dropdown($templates){
    
    $templates[BRIGHTY_CORE_PLUGIN_DIR . 'templates/client-dashboard.php'] = __('Brighty - User Dashboard', 'brighty-core');
    $templates[BRIGHTY_CORE_PLUGIN_DIR . 'templates/full-width.php'] = __('Brighty - Full Page', 'brighty-core');
    $templates[BRIGHTY_CORE_PLUGIN_DIR . 'templates/full-width-centered.php'] = __('Brighty - Full Width Centered', 'brighty-core');

    return $templates;
}

/**
 * Change the page template to the selected template on the dropdown
 * 
 * @param $template
 *
 * @return mixed
 */

function brighty_core_change_page_template($template)
{
    if (is_page()) {
        $meta = get_post_meta(get_the_ID());

        if (!empty($meta['_wp_page_template'][0]) && $meta['_wp_page_template'][0] != $template) {
            $template = $meta['_wp_page_template'][0];
        }
    }

    return $template;
}