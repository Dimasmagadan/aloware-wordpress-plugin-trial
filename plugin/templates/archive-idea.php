<?php
/*
 * Template Name: Idea Archive
 * Template Post Type: idea
 *
 * @package Aloware
 */

get_header();
?>
<div class="flex">
    <div class="w-3/4 p-4">
        <?php
        if (have_posts()) {
            echo '<ul class="list-reset posts-container">';
            while (have_posts()) {
                the_post();
                ?>
                <li class="mb-4 p-2 border border-grey-light hover:bg-grey-light">
                    <a href="<?php the_permalink(); ?>">
                        <h3 class="text-xl font-medium mb-2"><?php the_title(); ?></h3>
                        <?php
                        if ( has_post_thumbnail() ) {
                            the_post_thumbnail( 'medium' );
                        }
                        ?>
                        <div class="text-sm text-grey-dark"><?php the_excerpt(); ?></div>
                    </a>
                </li>
                <?php
            }
            echo '</ul>';

            // TODO: show if no js only
            the_posts_pagination();
            ?>
            <button data-page="<?php
            echo ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
            ?>" class="aloware-load block p-2 bg-black text-white hover:bg-grey-darker"><?php _e('Load More', 'aloware' ); ?></button>
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
            </select>
            <div class="mb-2" id="filter-ideas-by-category">
                <h4 class="text-md font-medium mb-2"><?php _e('Choose category', 'aloware' ); ?></h4>
                <ul class="list-reset">
                    <li class="mb-2">
                        <label class="inline-block mr-2 text-sm text-grey-dark">
                            <input type="radio" name="idea_category" value="-1">
                            <?php _e( 'All categories', 'alloware' ); ?>
                        </label>
                    </li>
                    <?php
                    $categories = get_terms([
                        'taxonomy' => 'idea_category',
                        'hide_empty' => false,
                    ]);
                    foreach ($categories as $category) {
                        echo '<li class="mb-2">';
                        echo '<label class="inline-block mr-2 text-sm text-grey-dark">';
                        echo '<input type="radio" name="idea_category" value="' . esc_attr( $category->term_id ) . '"> ';
                        echo esc_attr( $category->name );
                        echo '</label>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
            <button type="submit" id="filterIdeas" class="block p-2 bg-black text-white hover:bg-grey-darker"><?php _e('Filter', 'aloware' ); ?></button>
        </form>

        <a class="inline-block py-2 px-4 text-white bg-blue-500 hover:bg-blue-600 mt-2" href="<?php echo esc_url( site_url( '/idea/add') ); ?>"><?php _e('Add New Idea', 'aloware' ); ?></a>
    </div>
</div>

<?php

get_footer();
