<?php
/**
 * @file
 * The primary PHP file for this theme.
 */

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
            //$element['#localized_options']['attributes']['data-toggle'] = 'dropdown';
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
    $variables['child_name'] = $child->field_first_name->value();
    $variables['child_birthday'] = $child->field_birthday->value();
    $variables['child_favourite_play'] = $child->field_play_desc->value();
    $variables['child_country'] = $child->field_country->value();
    $variables['child_gender'] = $child->field_gender->value() == 'M' ? 'Chico' : 'Chica';
    $variables['child_brothers'] = $child->field_brothers->value();
    $variables['child_sisters'] = $child->field_sisters->value();
}
