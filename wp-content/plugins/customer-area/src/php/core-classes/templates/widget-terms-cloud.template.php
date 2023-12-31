<?php
/** Template version: 3.1.0
 *
 * -= 3.1.0 =-
 * - Use new format for term data
 *
 * -= 3.0.0 =-
 * - Initial template
 *
 */ ?>

<?php
/** @var $hide_empty */
/** @var $terms */ ?>

<div class="cuar-cloud panel-body">

    <?php
    foreach ($terms as $termDesc) {
        $term = $termDesc['term'];
        $children = $termDesc['children'];

        // Get term link
        $link = $this->get_link($term);

        // Print the current term
        printf('<a href="%1$s" title="%3$s" class="label label-default label-sm">%2$s</a>',
            $link,
            $term->name,
            sprintf(esc_attr__('Show all content categorized under %s', 'cuar'), $term->name)
        );
    }
    ?>

</div>
