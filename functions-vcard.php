<?php
function getStylePoint($title) {
  $title = strtolower($title);
  if ($title=="education" || $title=="diplomas") {
    return "timeline-education";
  }else if ($title=="work experience") {
    return "timeline-work";
  }else {
    return "timeline-point";
  }
}
function getStyleIcon($title) {
  $title = strtolower($title);
  if ($title=="education" || $title=="diplomas") {
    return "fa-university";
  }else if ($title=="work experience") {
    return "fa-flag";
  }else {
    return "";
  }
}


/**
 * Repeatable Custom Fields in a Metabox
 * Author: Helen Hou-Sandi
 *
 * From a bespoke system, so currently not modular - will fix soon
 * Note that this particular metadata is saved as one multidimensional array (serialized)
 */
 
function hhs_get_sample_options() {
  $options = array (
    'Option 1' => 'option1',
    'Option 2' => 'option2',
    'Option 3' => 'option3',
    'Option 4' => 'option4',
  );
  
  return $options;
}

add_action('admin_init', 'hhs_add_meta_boxes', 1);
function hhs_add_meta_boxes() {
  add_meta_box( 'contributors', 'Contributors', 'hhs_repeatable_meta_box_display', 'portfolio', 'normal', 'high');
}

function hhs_repeatable_meta_box_display() {
  global $post;

  $repeatable_fields = get_post_meta($post->ID, 'repeatable_fields', true);
  $options = hhs_get_sample_options();

  wp_nonce_field( 'hhs_repeatable_meta_box_nonce', 'hhs_repeatable_meta_box_nonce' );
  ?>
  <script type="text/javascript">
  jQuery(document).ready(function( $ ){
    $( '#add-row' ).on('click', function() {
      var row = $( '.empty-row.screen-reader-text' ).clone(true);
      row.removeClass( 'empty-row screen-reader-text' );
      row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
      return false;
    });
    
    $( '.remove-row' ).on('click', function() {
      $(this).parents('tr').remove();
      return false;
    });
  });
  </script>
  
  <table id="repeatable-fieldset-one" width="100%">
  <thead>
    <tr>
      <th width="46%">Name</th>
      <th width="46%">Job</th>
      <th width="8%"></th>
    </tr>
  </thead>
  <tbody>
  <?php
  
  if ( $repeatable_fields ) :
  
  foreach ( $repeatable_fields as $field ) {
  ?>
  <tr>
    <td><input type="text" class="widefat" name="name[]" value="<?php if($field['name'] != '') echo esc_attr( $field['name'] ); ?>" /></td>
   
    <td><input type="text" class="widefat" name="job[]" value="<?php if ($field['job'] != '') echo esc_attr( $field['job'] ); ?>" /></td>
  
    <td><a class="button remove-row" href="#">Remove</a></td>
  </tr>
  <?php
  }
  else :
  // show a blank one
  ?>
  <tr>
    <td><input type="text" class="widefat" name="name[]" /></td>
   
    <td><input type="text" class="widefat" name="job[]" /></td>
  
    <td><a class="button remove-row" href="#">Remove</a></td>
  </tr>
  <?php endif; ?>
  
  <!-- empty hidden one for jQuery -->
  <tr class="empty-row screen-reader-text">
    <td><input type="text" class="widefat" name="name[]" /></td>
  
    <td><input type="text" class="widefat" name="job[]" /></td>
      
    <td><a class="button remove-row" href="#">Remove</a></td>
  </tr>
  </tbody>
  </table>
  
  <p><a id="add-row" class="button" href="#">Add another</a></p>
  <?php
}

add_action('save_post', 'hhs_repeatable_meta_box_save');
function hhs_repeatable_meta_box_save($post_id) {
  if ( ! isset( $_POST['hhs_repeatable_meta_box_nonce'] ) ||
  ! wp_verify_nonce( $_POST['hhs_repeatable_meta_box_nonce'], 'hhs_repeatable_meta_box_nonce' ) )
    return;
  
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
    return;
  
  if (!current_user_can('edit_post', $post_id))
    return;
  
  $old = get_post_meta($post_id, 'repeatable_fields', true);
  $new = array();
  $options = hhs_get_sample_options();
  
  $names = $_POST['name'];
  $jobs = $_POST['job'];
  
  $count = count( $names );
  
  for ( $i = 0; $i < $count; $i++ ) {
    if ( $names[$i] != '' ) :
      $new[$i]['name'] = stripslashes( strip_tags( $names[$i] ) );
      
      if ( $jobs[$i] == 'http://' )
        $new[$i]['job'] = '';
      else
        $new[$i]['job'] = stripslashes( $jobs[$i] ); // and however you want to sanitize
    endif;
  }

  if ( !empty( $new ) && $new != $old )
    update_post_meta( $post_id, 'repeatable_fields', $new );
  elseif ( empty($new) && $old )
    delete_post_meta( $post_id, 'repeatable_fields', $old );
}
/**
 * Edit Template for Resume Builder
 * Author: Weblabor
 */
function getIntroduction($sections) {
  foreach ($sections as $section) {
    if($section["_type"]=="_introduction_block") {
      $array = array();
      $array["title"] = $section["sectiontitle"];
      $array["subtitle"] = $section["sectionsubtitle"];
      $array["image"] = wp_get_attachment_url($section['sectionimage'], 'rb-resume-thumbnail');
      $array["text"] = $section["sectiontext"];
      return $array;
    }
  }
}
function getBlocks($sections) {
  $array2 = array();
  foreach ($sections as $section) {
    if($section["_type"]!="_introduction_block") {
      $array = array();
      $array["title"] = $section["sectiontitle"];
      $array4 = array();
      foreach ($section["sectioncontent"] as $content) {
        //var_dump($content);
        $array3 = array();
        if ($content["_type"]=="_detailed_row") {
          $array3["title"] = $content["rowtitle"];
          $array3["subtitle"] = $content["rowsubtitle"];
          $array3["side"] = $content["rowsidetext"];
          $array3["text"] = $content["rowtext"];
        } else {
          $array3["text"] = $content["text"];
        }
        
        $array4[] = $array3;
      }
      $array["content"] = $array4;
      $array2[] = $array;
    }
  }
  return $array2;
}
function getSkills($id) {
  $array = array();
  $widget_title = carbon_get_post_meta($id, 'rb_resume_widget_skills_title');
  if ( !empty($widget_title) ) {
    $array["title"] = $widget_title;
  }
  
  # Widget Skills
  $skills = carbon_get_post_meta($id, 'rb_resume_widget_skills', 'complex');
  foreach ($skills as &$skill) {
    $rating = $skill["rating"];
    $rating = $rating*20;
    $skill["rating"] = $rating;
  }
  $array["skills"] = $skills; //187
  return $array;
}

function getContactInfo($id) {
  $array = array();
  $array["title"]   = carbon_get_post_meta($id, 'rb_resume_widget_contacts_title');
  $array["email"]   = carbon_get_post_meta($id, 'rb_resume_widget_contacts_email');
  $array["phone"]   = carbon_get_post_meta($id, 'rb_resume_widget_contacts_phone');
  $array["website"] = carbon_get_post_meta($id, 'rb_resume_widget_contacts_website');
  $array["address"] = carbon_get_post_meta($id, 'rb_resume_widget_contacts_address');
  return $array;
}
?>