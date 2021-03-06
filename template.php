<?php
/**
 * @file
 * The primary PHP file for this theme.
 */


/**
 * Overrides theme_link().
 */
function gaea_link($variables) {
    if($variables['text'] == 'My account') {
        $variables['text'] = 'Hola, ' . $GLOBALS['user']->name;
    }

    return '<a href="' . check_plain(url($variables['path'], $variables['options'])) . '"' . drupal_attributes($variables['options']['attributes']) . '>' . ($variables['options']['html'] ? $variables['text'] : check_plain($variables['text'])) . '</a>';
}


/**
 * Overrides theme_menu_link().
 */
function gaea_menu_link(array $variables) {
    $element = $variables['element'];
    $sub_menu = '';

    if ($element['#below']) {
        // Prevent dropdown functions from being added to management menu so it
        // does not affect the navbar module.
        if (($element['#original_link']['menu_name'] == 'management') && (module_exists('navbar'))) {
            $sub_menu = drupal_render($element['#below']);
        }
        //Here we need to change from ==1 to >=1 to allow for multilevel submenus
        elseif ((!empty($element['#original_link']['depth'])) && ($element['#original_link']['depth'] >= 1)) {
            // Add our own wrapper.
            unset($element['#below']['#theme_wrappers']);
            $sub_menu = '<ul class="dropdown-menu">' . drupal_render($element['#below']) . '</ul>';
            // Generate as standard dropdown.
            $element['#title'] .= ' <span class="caret"></span>'; // Smartmenus plugin add's caret
            $element['#attributes']['class'][] = 'dropdown';
            $element['#localized_options']['html'] = TRUE;

            // Set dropdown trigger element to # to prevent inadvertant page loading
            // when a submenu link is clicked.
            $element['#localized_options']['attributes']['data-target'] = '#';
            $element['#localized_options']['attributes']['class'][] = 'dropdown-toggle';
            //comment element bellow if you want your parent menu links to be "clickable"
            $element['#localized_options']['attributes']['data-toggle'] = 'dropdown';
        }
    }
    // On primary navigation menu, class 'active' is not set on active menu item.
    // @see https://drupal.org/node/1896674
    if (($element['#href'] == $_GET['q'] || ($element['#href'] == '<front>' && drupal_is_front_page())) && (empty($element['#localized_options']['language']))) {
        $element['#attributes']['class'][] = 'active';
    }
    $output = l($element['#title'], $element['#href'], $element['#localized_options']);
    return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/**
 * Gaea theme wrapper function for the main menu links.
 */
function bootstrap_menu_tree__wv_main_menu(&$variables) {
    return '<ul class="menu nav navbar-nav">' . $variables['tree'] . '</ul>';
}

/**
 * Gaea theme wrapper function for the main menu links.
 */
function gaea_menu_tree__menu_wv_private_area(&$variables) {
    return '<ul class="menu nav nav-tabs">' . $variables['tree'] . '</ul>';
}

/**
 * Overrides theme_menu_link().
 */
function gaea_menu_link__menu_wv_private_area($variables) {
    $element = $variables['element'];
    $sub_menu = '';

    if ($element['#below']) {
        $sub_menu = drupal_render($element['#below']);
    }

    $image = file_load($element['#localized_options']['content']['image']);
    $image_markup = theme_image_style(array(
            'style_name' => 'bootstrap_circle_image',
            'path' => $image->uri,
            'width' => $image->image_dimensions['width'],
            'height' => $image->image_dimensions['height'],
            'attributes' => array(
                'class' => 'img-circle'
            ),
        )
    );

    $options = $element['#localized_options'];
    $options['html'] = true;

    $output = l($image_markup.$element['#title'], $element['#href'], $options);

    return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/**
 * Pre-processes variables for the "page" theme hook.
 *
 * See template for list of available variables.
 *
 * @see page.tpl.php
 *
 * @ingroup theme_preprocess
 */
function gaea_preprocess_page(&$variables) {

    $variables['navbar_classes_array'] = array('navbar');
    $variables['main_menu_navbar_classes_array'] = array('navbar');

    if (bootstrap_setting('navbar_position') !== '') {
        $variables['navbar_classes_array'][] = 'navbar-' . bootstrap_setting('navbar_position');
    }

    if (bootstrap_setting('navbar_inverse')) {
        $variables['navbar_classes_array'][] = 'navbar-inverse';
    }
    else {
        $variables['navbar_classes_array'][] = 'navbar-default';
    }

    if (bootstrap_setting('main_menu_navbar_position') !== '') {
        $variables['main_menu_navbar_classes_array'][] = 'navbar-' . bootstrap_setting('main_menu_navbar_position');
    }

    if (bootstrap_setting('main_menu_navbar_inverse')) {
        $variables['main_menu_navbar_classes_array'][] = 'navbar-inverse';
    }
    else {
        $variables['main_menu_navbar_classes_array'][] = 'navbar-default';
    }
}

function gaea_preprocess_image_style(&$vars) {
    if($vars['style_name'] == 'bootstrap_thumbnail_image') {
        $vars['attributes']['class'][] = 'img-thumbnail'; // can be 'img-rounded', 'img-circle', or 'img-thumbnail'
    } elseif ($vars['style_name'] == 'bootstrap_rounded_image') {
        $vars['attributes']['class'][] = 'img-rounded';
    } elseif ($vars['style_name'] == 'bootstrap_circle_image' || $vars['style_name'] == 'bootstrap_circle_image_black_white') {
        $vars['attributes']['class'][] = 'img-circle';
    }
}

/**
 * Processes variables for the "page" theme hook.
 *
 * See template for list of available variables.
 *
 * @see page.tpl.php
 *
 * @ingroup theme_process
 */
function gaea_process_page(&$variables) {
    $variables['main_menu_navbar_classes'] = implode(' ', $variables['main_menu_navbar_classes_array']);
}

/**
 * Alter the array used for colorizing text.
 *
 * @param array $texts
 *   An associative array containing the text and classes to be matched, passed
 *   by reference.
 *
 * @see _bootstrap_colorize_text()
 */

function gaea_bootstrap_colorize_text_alter(&$texts) {
    $texts['matches'][t('Add to cart')] = 'primary';
}

/**
 * Processes variables for node.tpl.php
 */
function gaea_preprocess_node(&$variables) {
    $node = $variables['node'];

    // Create preprocess functions per content type.
    $function = __FUNCTION__ . '_' . $node->type;
    if (function_exists($function)) {
        $function($variables);
    }
}

/**
 * Processes variables for node--worldvision-project.tpl.php
 */
function gaea_preprocess_node_worldvision_project(&$variables) {
    $node = $variables['node'];
    $project = entity_metadata_wrapper('node', $node);

    $variables['project'] = $project;
    $variables['capital'] = $project->field_country_capital->value();
    $variables['population'] = $project->field_country_population->value();
    $variables['life_expectancy'] = $project->field_life_expectancy->value();
    $variables['child_mortality_rate'] = $project->field_child_mortality_rate->value();
    $variables['hiv_rate'] = $project->field_hiv_rate->value();
    $variables['human_development_index'] = $project->field_human_development_index->value();
}

/**
 * Processes variables for node--child.tpl.php
 */
function gaea_preprocess_node_child(&$variables) {
    $node = $variables['node'];
    $child = entity_metadata_wrapper('node', $node);

    $variables['child'] = $child;
    $variables['child_name'] = $child->field_child_reference->field_first_name->value();
    $variables['child_birthday'] = format_date($child->field_child_reference->field_child_birthday->value(), 'birthday');
    $variables['child_favourite_play'] = $child->field_child_reference->field_play_desc->value();
    $variables['child_country'] = $child->field_child_reference->field_child_country->name->value();
    switch($child->field_child_reference->field_child_country->continent->value()) {
    	case('AF'):
		$continent = 'África';
		break;
	case('SA'):
		$continent = 'Sudamérica';
		break;
	default:
		$continent = '';
		break;
    }
    $variables['child_continent'] = $continent;
    $variables['child_gender'] = $child->field_child_reference->field_gender->value() == 'M' ? 'Chico' : 'Chica';
    $variables['child_brothers'] = $child->field_child_reference->field_brothers->value();
    $variables['child_sisters'] = $child->field_child_reference->field_sisters->value();
    $variables['product_id'] = $child->field_child_reference->product_id->value();
    $variables['child_age'] = $child->field_child_reference->field_wv_child_age->value();
}

function gaea_views_pre_render(&$view) {
    if($view->name == 'wv_child_slider' && $view->current_display == 'page_1') {
        //dpm($view, 'complete view');
    }
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function gaea_form_commerce_checkout_form_alter(&$form, &$form_state) {
    // If this checkout form contains the payment method radios...
    if (!empty($form['commerce_payment']['payment_method']['#options'])) {
        // Loop over its options array looking for a PayPal WPS option.
        foreach ($form['commerce_payment']['payment_method']['#options'] as $key => &$value) {
            list($method_id, $rule_name) = explode('|', $key);

            // If we find PayPal WPS...
            if ($method_id == 'paypal_wps') {
                // Prepare the replacement radio button text with icons.
                $icons = commerce_paypal_icons();
                $value = t('PayPal - pay securely without sharing your financial information', array('!logo' => $icons['paypal']));
                $value .= '<div class="commerce-paypal-icons"><span class="label">' . t('Includes:') . '</span>' . implode(' ', $icons) . '</div>';

                // Add the CSS.
                $form['commerce_payment']['payment_method']['#attached']['css'][] = drupal_get_path('module', 'commerce_paypal_wps') . '/theme/commerce_paypal_wps.theme.css';

            }

            // If we find Sermepa...
            if ($method_id == 'commerce_sermepa') {
                // Prepare the replacement radio button text with icons.
                $icons = commerce_paypal_icons();
                $value .= '<div class="commerce-sermepa-icons"><span class="label">' . t('Includes:') . '</span>' . implode(' ', $icons) . '</div>';

                // Add the CSS.
                $form['commerce_payment']['payment_method']['#attached']['css'][] = drupal_get_path('module', 'commerce_paypal_wps') . '/theme/commerce_paypal_wps.theme.css';

            }
        }
    }
}
