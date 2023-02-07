<?php
/*
 * Template Name: Idea Archive
 * Template Post Type: idea
 */

get_header();
?>

<div class="flex">
    <div class="w-3/4 p-4">
        <?php
        if (have_posts()) {
            echo '<ul class="list-reset">';
            while (have_posts()) {
                the_post();
                ?>
                <li class="mb-4 p-2 border border-grey-light hover:bg-grey-light">
                    <a href="<?php the_permalink(); ?>">
                        <h3 class="text-xl font-medium mb-2"><?php the_title(); ?></h3>
                    </a>
                    <div class="text-sm text-grey-dark"><?php the_content(); ?></div>
                </li>
                <?php
            }
            echo '</ul>';

            // TODO: show if no js only
            the_posts_pagination();

            ?>
            <button type="submit" class="block p-2 bg-black text-white hover:bg-grey-darker"><?php _e('Load More', 'aloware' ); ?></button>
            <?php
        } else {
            echo '<p>' . __('No ideas found', 'aloware' ) . '</p>';
        }
        ?>

    </div>
    <div class="w-1/4 p-4 bg-grey-light">
        <h3 class="text-lg font-medium mb-2"><?php _e('Filter Ideas', 'aloware' ); ?></h3>
        <form id="filter-ideas-form">
            <select class="block mb-2 p-2 border border-grey-light" id="filter-ideas-by">
                <option value="most_recent"><?php _e('Most Recent', 'aloware' ); ?></option>
                <option value="most_popular"><?php _e( 'Most Popular', 'aloware' ); ?></option>
                <option value="category"><?php _e( 'Category', 'aloware' ); ?></option>
            </select>
            <div class="mb-2" id="filter-ideas-by-category" style="display: none;">
                <h4 class="text-md font-medium mb-2"><?php _e('Choose category', 'aloware' ); ?></h4>
                <ul class="list-reset">
                    <?php
                    $categories = get_terms([
                        'taxonomy' => 'idea_category',
                        'hide_empty' => false,
                    ]);
                    foreach ($categories as $category) {
                        echo '<li class="mb-2">';
                        echo '<input type="radio" name="idea_category" value="' . esc_attr( $category->slug ) . '">';
                        echo '<label class="inline-block mr-2 text-sm text-grey-dark">' . esc_attr( $category->name ) . '</label>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
            <button type="submit" class="block p-2 bg-black text-white hover:bg-grey-darker"><?php _e('Filter', 'aloware' ); ?></button>
        </form>
    </div>
</div>

<?php

get_footer();
