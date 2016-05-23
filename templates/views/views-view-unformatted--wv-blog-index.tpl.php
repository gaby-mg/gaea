<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<?php if (!empty($title)): ?>
    <h3><?php print $title; ?></h3>
<?php endif; ?>
<div class="row">
    <?php foreach ($rows as $id => $row): ?>
        <div<?php if ($classes_array[$id]) { print ' class="col-xs-12 col-md-6 col-lg-4 ' . $classes_array[$id] .'"';  } ?>>
            <?php print $row; ?>
        </div>
    <?php endforeach; ?>
</div>
