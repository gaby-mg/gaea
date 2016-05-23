<?php
/**
 * Created by PhpStorm.
 * User: gabymg
 * Date: 5/13/16
 * Time: 8:48 PM
 */

/**
 * Implements hook_form_FORM_ID_alter().
 */
function gaea_form_system_theme_settings_alter(&$form, $form_state, $form_id = NULL)
{
    // Do not add Bootstrap specific settings to non-bootstrap based themes,
    // including a work-around for a core bug affecting admin themes.
    // @see https://drupal.org/node/943212
    $theme = !empty($form_state['build_info']['args'][0]) ? $form_state['build_info']['args'][0] : FALSE;
    if (isset($form_id) || $theme === FALSE || !in_array('bootstrap', _bootstrap_get_base_themes($theme, TRUE))) {
        return;
    }

    // Navbar.
    $form['components']['navbar'] = array(
        '#type' => 'fieldset',
        '#title' => t('Navbars'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
    );
    $form['components']['navbar']['bootstrap_navbar_position'] = array(
        '#type' => 'select',
        '#title' => t('Navbar Position'),
        '#description' => t('Select your Navbar position.'),
        '#default_value' => bootstrap_setting('navbar_position', $theme),
        '#options' => array(
            'static-top' => t('Static Top'),
            'fixed-top' => t('Fixed Top'),
            'fixed-bottom' => t('Fixed Bottom'),
        ),
        '#empty_option' => t('Normal'),
    );
    $form['components']['navbar']['bootstrap_navbar_inverse'] = array(
        '#type' => 'checkbox',
        '#title' => t('Inverse navbar style'),
        '#description' => t('Select if you want the inverse navbar style.'),
        '#default_value' => bootstrap_setting('navbar_inverse', $theme),
    );

    $form['components']['navbar']['bootstrap_main_menu_navbar_position'] = array(
        '#type' => 'select',
        '#title' => t('Main Menu Navbar Position'),
        '#description' => t('Select your Navbar position.'),
        '#options' => array(
            'static-top' => t('Static Top'),
            'fixed-top' => t('Fixed Top'),
            'fixed-bottom' => t('Fixed Bottom'),
        ),
        '#empty_option' => t('Normal'),
    );
    $form['components']['navbar']['bootstrap_main_menu_navbar_inverse'] = array(
        '#type' => 'checkbox',
        '#title' => t('Main Menu Inverse navbar style'),
        '#description' => t('Select if you want the inverse navbar style.'),
    );
}