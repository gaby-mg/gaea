<?php
/**
 * @file
 * The primary PHP file for this theme.
 */

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
 *
 */
function gaea_preprocess_node(&$variables) {
    $node = $variables['node'];

    // Create preprocess functions per content type.
    $function = __FUNCTION__ . '_' . $node->type;
    if (function_exists($function)) {
        $function($variables);
    }
}

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

function gaea_preprocess_node_child(&$variables) {
    $node = $variables['node'];

    $child = entity_metadata_wrapper('node', $node);

    $variables['child'] = $child;
    $birthday = field_get_items('node', $node, 'field_child_birthday');
    $variables['child_birthday'] = field_view_value('node', $node, 'field_child_birthday', $birthday[0]);
    $variables['child_country'] = $child->field_child_country->value()->name;
    $variables['child_gender'] = $child->field_child_gender->value() == 'masculino' ? 'Chico' : 'Chica';
    $variables['child_brothers'] = $child->field_child_brothers->value();
    $variables['child_sisters'] = $child->field_child_sisters->value();
    $variables['this_is_my_world_text'] = $child->field_this_is_my_world->value();
}
