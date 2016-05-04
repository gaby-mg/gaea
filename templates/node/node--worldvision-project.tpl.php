<?php
/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct URL of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type; for example, "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type; for example, story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode; for example, "full", "teaser".
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined; for example, $node->body becomes $body. When needing to
 * access a field's raw values, developers/themers are strongly encouraged to
 * use these variables. Otherwise they will have to explicitly specify the
 * desired field language; for example, $node->body['en'], thus overriding any
 * language negotiation rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 *
 * @ingroup templates
 */
?>
<?php
$capital = field_get_items('node', $node, 'field_country_capital');
$capital_output = field_view_value( 'node', $node, 'field_country_capital', $capital[0]);

$population = field_get_items('node', $node, 'field_country_population');
$population_output = field_view_value( 'node', $node, 'field_country_population', $population[0]);

$life_expectancy = field_get_items('node', $node, 'field_life_expectancy');
$life_expectancy_output = field_view_value( 'node', $node, 'field_life_expectancy', $life_expectancy[0]);

$child_mortality_rate = field_get_items('node', $node, 'field_child_mortality_rate');
$child_mortality_rate_output = field_view_value( 'node', $node, 'field_child_mortality_rate', $child_mortality_rate[0]);

$hiv_rate = field_get_items('node', $node, 'field_hiv_rate');
$hiv_rate_output = field_view_value( 'node', $node, 'field_hiv_rate', $hiv_rate[0]);

$human_development_index = field_get_items('node', $node, 'field_human_development_index');
$human_development_index_output = field_view_value( 'node', $node, 'field_human_development_index', $human_development_index[0]);

dpm($content);
?>
<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <?php if ((!$page && !empty($title)) || !empty($title_prefix) || !empty($title_suffix) || $display_submitted): ?>
  <header>
      <?php print render($content['field_image']); ?>
      <?php print render($title_prefix); ?>
    <?php if (!$page && !empty($title)): ?>
    <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
    <?php endif; ?>
    <?php print render($title_suffix); ?>
    <?php if ($display_submitted): ?>
    <span class="submitted">
      <?php print $user_picture; ?>
      <?php print $submitted; ?>
    </span>
    <?php endif; ?>
  </header>
  <?php endif; ?>
  <?php
    // Hide comments, tags, links and custom fields now so that we can render them later.
    $hidden_fields = [
        'comments',
        'links',
        'field_tags',
        'field_country_flag',
        'field_country_capital',
        'field_country_population',
        'field_life_expectancy',
        'field_child_mortality_rate',
        'field_hiv_rate',
        'field_human_development_index',
        'field_country_context',
        'field_child_day',
        'field_country_wv_work',
    ];

    foreach ($hidden_fields as $hidden_field) {
        hide($content[$hidden_field]);
    }
  ?>
    <div class="row">
        <div class="col-md-9">
            <?php print render($content); ?>

            <h2>Contexto</h2>

            <?php print render($content['field_country_context']); ?>

            <h2>Cómo vive un niño en <?php print $title; ?>?</h2>

            <?php print render($content['field_child_day']); ?>

            <h2>Nuestro trabajo</h2>

            <?php print render($content['field_country_wv_work']); ?>

        </div>
        <div class="col-md-3">
            <aside>
                <table class="table">
                    <tr>
                        <td colspan="2"><?php print render($content['field_country_flag']); ?></td>
                    </tr>
                    <tr>
                        <td>Capital</td>
                        <td><?php print render($capital_output); ?></td>
                    </tr>
                    <tr>
                        <td>Población</td>
                        <td><?php print render($population_output); ?></td>
                    </tr>
                    <tr>
                        <td>Esperanza de vida</td>
                        <td><?php print render($life_expectancy_output); ?></td>
                    </tr>
                    <tr>
                        <td>Tasa de mortalidad infantil</td>
                        <td><?php print render($child_mortality_rate_output); ?></td>
                    </tr>
                    <tr>
                        <td>Tasa VIH</td>
                        <td><?php print render($hiv_rate_output); ?></td>
                    </tr>
                    <tr>
                        <td>Índice de desarrollo humano</td>
                        <td><?php print render($human_development_index_output); ?></td>
                    </tr>
                </table>
            </aside>
        </div>
    </div>

  <?php if (!empty($content['field_tags']) || !empty($content['links'])): ?>
  <footer>
    <?php print render($content['field_tags']); ?>
    <?php print render($content['links']); ?>
  </footer>
  <?php endif; ?>
</article>

