<?php
/*
 * Template Name: Idea Archive
 * Template Post Type: idea
 */

get_header();
?>

<div class="flex flex-col items-center m-4">
  <h2 class="text-3xl font-medium mb-4"><?php _e( 'Add New Idea', 'aloware' ); ?></h2>
  <form id="add-idea-form" class="bg-white rounded p-4" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" enctype="multipart/form-data">
    <input type="hidden" name="action" value="aloware_add_idea">
    <?php wp_nonce_field( 'aloware_idea_form_nonce', 'form_nonce' ); ?>
    <div class="mb-4">
      <label class="block text-gray-700 font-medium mb-2" for="idea-title">
        <?php _e( 'Idea Title', 'aloware' ); ?>
      </label>
      <input
        type="text"
        id="idea-title"
        name="idea-title"
        class="border border-gray-400 p-2 w-full"
        required
      />
    </div>

    <div class="mb-4">
      <label class="block text-gray-700 font-medium mb-2" for="idea-thumbnail">
        <?php _e( 'Idea Thumbnail', 'aloware' ); ?>
      </label>
      <input
        type="file"
        id="idea-thumbnail"
        name="idea-thumbnail"
        class="border border-gray-400 p-2 w-full"
        accept="image/*"
        required
      />
    </div>

    <div class="mb-4">
      <label class="block text-gray-700 font-medium mb-2" for="idea-category">
        <?php _e( 'Idea Category', 'aloware' ); ?>
      </label>
      <select
        id="idea-category"
        name="idea-category"
        class="border border-gray-400 p-2 w-full"
        required
      >
        <?php
        // Get all categories for idea post type
        $categories = get_terms([
          'taxonomy' => 'idea_category',
          'hide_empty' => false,
        ]);

        // Loop through categories and add options to select element
        foreach ($categories as $category) {
          echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
        }
        ?>
      </select>
    </div>

    <div class="mb-4">
      <label class="block text-gray-700 font-medium mb-2" for="idea-description">
        <?php _e( 'Idea Description', 'aloware' ); ?>
      </label>
      <textarea
        id="idea-description"
        name="idea-description"
        class="border border-gray-400 p-2 w-full"
        required
      ></textarea>
    </div>

    <div class="text-center">
      <input
        type="submit"
        value="<?php _e( 'Add Idea', 'aloware' ); ?>"
        class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600"
      />
    </div>
  </form>
</div>

<?php

get_footer();
