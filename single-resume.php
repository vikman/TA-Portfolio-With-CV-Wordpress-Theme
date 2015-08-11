<?php
/**
 * The template for displaying all single resume items.
 *
 * @package TA Portfolio
 */

get_header();  
the_post(); 
//var_dump($post);
$sections = carbon_get_post_meta($post->ID, 'rb_resume_sections', 'complex');
$introduction = getIntroduction($sections);
$blocks = getBlocks($sections);
$skills = getSkills($post->ID);
$title = get_the_title();
$contact = getContactInfo($post->ID);
?>
<!-- /SIDE MENU
========================================================= -->
<section id="content-body" class="vt_animate container" style="padding:0;">
  <div class="row introclass" id="intro">
   
    <!-- Beginning of Content -->
    <div class="col-md-10 col-sm-10 col-md-offset-2 col-sm-offset-1 resume-container">
      
      <!-- =============== PROFILE INTRO ====================-->
      <div class="profile-intro row" style="padding:0;">
        <!-- Left Collum with Avatar pic -->
        <div class="col-md-4 profile-col">
          <!-- Avatar pic -->
          <div class="profile-pic">
            <div class="profile-border">
              <!-- Put your picture here ( 308px by 308px for retina display)-->
              <img src="<?php echo $introduction['image']; ?>" alt="">
              <!-- /Put your picture here -->
            </div>          
          </div>
           <!-- /Avatar pic -->
        </div>
        <!-- /Left columm with avatar pic -->
  
        <!-- Right Columm -->
        <div class="col-md-7">
          <!-- Welcome Title-->
          <h1 class="intro-title1">Hi, i'm <span class="color1 bold"><?php echo $introduction['title']; ?>!</span></h1>
          <!-- /Welcome Title -->
          <!-- Job - -->
          <h2 class="intro-title2"><?php echo $introduction['subtitle']; ?></h2>
          <!-- /job -->
          <!-- Description -->
          <p><?php echo $introduction['text']; ?></p>
          <!-- /Description -->
        </div>
        <!-- /Right Collum -->
      </div>
      <!-- ============  /PROFILE INTRO ================= -->
      
      <!-- ============  timeline-2 ================= -->
      <div class="timeline-2-wrap">
        <div class="timeline-2-bg">
          <?php foreach ($blocks as $block) { ?>        
  					<section class="timeline-2 intro" id="intro">
              <div class="line row">
                <div class="content-wrap bg1">
                  <h2 class="section-title"><?php echo $block['title']; ?></h2>
                </div>
              </div>
              <?php foreach ($block["content"] as $row) { ?>
                <div class="line row">
                  <div class="content-wrap bg1">
                    <div class="line-content <?php if(strlen($row["subtitle"])>0 && strlen($row["side"])>0) { echo "line-content-education"; } ?>">
                      <?php if(strlen($row["title"])>0) { ?>
                      <h3 class="section-item-title-1"><?php echo $row['title']; ?></h3>
                      <?php } if(strlen($row["subtitle"])>0 && strlen($row["side"])>0) { ?>
                        <h4 class="graduation-time"><i class="fa <?php echo getStyleIcon($block['title']); ?>"></i> 
                          <?php if(strlen($row["subtitle"])>0){ ?>
                            <?php echo $row['subtitle']; ?> - 
                          <?php } ?>
                          <?php if(strlen($row["side"])>0){ ?>
                            <span class="graduation-date"><?php echo $row['side']; ?></span>
                          <?php } ?>
                        </h4>
                        <div class="graduation-description">
                          <p><?php echo $row['text']; ?></p>
                        </div>
                      <?php } else { ?>
                        <p><?php echo $row['text']; ?></p>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </section>
				  <?php }?>
			 
					<section class="timeline-2 skills" id="skills">
          <div class="line row">
            <div class="content-wrap bg1">
              <h2 class="section-title"><?php echo $skills["title"]; ?></h2>
            </div>
          </div><div class="line row">
            <div class="content-wrap bg1">
              <div class="line-content">
                <h3 class="section-item-title-1">Professional Skills</h3>
                <ul class="skills-list">
                  <?php $cont = 0;
                  foreach ($skills["skills"] as $skill) { 
                    $cont++; 
                    if ($cont==5) {
                      $cont=1;
                    }
                    $style = "";
                    if ($cont>1) {
                      $style="progress-bar-$cont";
                    }
                    ?>   
                  <li>
                    <div class="progress">
                      <div class="progress-bar <?php echo $style; ?>" role="progressbar" data-percent="<?php echo $skill["rating"]; ?>%" style="width: <?php echo $skill["rating"]; ?>%;">
                          <span class="sr-only"><?php echo $skill["rating"]; ?>% Complete</span>
                      </div>
                      <span class="progress-type"><?php echo $skill["title"]; ?></span>
                      <span class="progress-completed"><?php echo $skill["rating"]; ?>%</span>
                    </div>
                  </li>
                  <?php } ?>   
                  </ul>
              </div>
            </div>
          </div>
				
			  </section>
       
        <section class="timeline-2 skills breakpage" id="portfolioprint">
           <div class="line row">
              <div class=" content-wrap bg1">
                <h2 class="section-title">PORTFOLIO</h2>
                <p>Know more about this projects in the website <?php echo get_permalink(); ?></p>
              </div>
            </div>
            <?php
            if ( ta_option( 'filter_switch' ) == '1' ) {
              $terms = get_terms( "portfolio_tags" );
              $count = count( $terms );
                if ( $count > 0 ) { 
                  foreach ( $terms as $term ) { 
                    $termname = strtolower( $term->name );
                    $termname = str_replace( ' ', '-', $termname );
                    $args = array( 
                      'post_type' => 'portfolio', 
                      'posts_per_page' => -1,
                      'post_status' => 'publish',
                      'tax_query' => array(
                        array(
                          'taxonomy' => 'portfolio_tags',
                          'field'    => 'slug',
                          'terms'    => $termname,
                        ),
                      ),
                    );
                    $the_query = new WP_Query( $args );
                    if ( $the_query->have_posts() ) :
                      
                      $firstresult = true;
                      while ( $the_query->have_posts() ) : $the_query->the_post();
                        
                        $Contributors = get_post_meta($post->ID, 'repeatable_fields', true);
                        $entra = false;
                        $job = "";
                        if (is_array($Contributors)) {
                          foreach ( $Contributors as $contributor ) {
                            $name = esc_attr( $contributor['name']);
                            $name = strtolower($name);
                            $name = str_replace(" ", "-", $name);

                            $name2 = $title;
                            $name2 = esc_attr( $name2 );
                            $name2 = strtolower($name2);
                            $name2 = str_replace(" ", "-", $name2);

                            
                            if ($name == $name2) {
                              $entra = true;
                              $job = $contributor['job'];
                            }
                          }
                        }
                        if ($entra==false) {
                          continue;
                        } 
                        if ($firstresult) {
                          $firstresult = false;
                          echo '<div class="line row">';
                          echo '<div class=" content-wrap bg1">';
                          echo '<div class="line-content">';
                          echo '<h3 class="section-item-title-1">'.$term->name.'</h3>';
                          echo '<ul class="skills-list">';
                        }
                        echo '<li><span class="colW-lg-6">';
                        echo the_title();
                        echo '</span><span class="release-date colW-lg-3">'.$job.'</span>';
                        echo '<span class="release-date colW-lg-3">'.get_the_date('Y/m/d').'</span></li>';
                      endwhile; 
                      if (!$firstresult) {
                        echo '</ul>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                      }
                    endif;
                  }
                }
            } ?>
            <?php wp_reset_postdata(); ?>
       </section>
       <section class="timeline-2 skills" id="portfolio">
           <div class="line row">
              <div class=" content-wrap bg1">
                <h2 class="section-title">PORTFOLIO</h2>
              </div>
            </div>
            <div class="line row">  
              <div class=" content-wrap bg1">
                <div class="line-content">
                  <h3 class="section-item-title-1">Some works</h3>
                  <?php
                  if ( ta_option( 'filter_switch' ) == '1' ) {
                    $terms = get_terms( "portfolio_tags" );
                    $count = count( $terms );
                    echo '<div id="filters" class="filters">';
                    echo '<ul>';
                    echo '<li class="filter active" data-filter="*">'. __('All', 'ta-portfolio') .'</li>';
                      if ( $count > 0 ) {   
                        foreach ( $terms as $term ) {
                          $termname = strtolower( $term->name );
                          $termname = str_replace( ' ', '-', $termname );
                          echo '<li class="filter" data-filter=".'.$termname.'">'.$term->name.'</li>';
                        }
                      }
                    echo '</ul>';
                    echo '</div>';
                  } ?>

                  <?php 
                  // the query
                  $the_query = new WP_Query( array( 'post_type' => 'portfolio', 'posts_per_page' => -1 ) ); ?>

                  <?php if ( $the_query->have_posts() ) : ?>
             
                    <div class="row">
                      <div id="portfolio-items">

                        <!-- the loop -->
                        <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

                        <?php
                          $Contributors = get_post_meta($post->ID, 'repeatable_fields', true);
                          $entra = false;
                          if (is_array($Contributors)) {
                            foreach ( $Contributors as $contributor ) {
                              $name = esc_attr( $contributor['name']);
                              $name = strtolower($name);
                              $name = str_replace(" ", "-", $name);

                              $name2 = $title;
                              $name2 = esc_attr( $name2 );
                              $name2 = strtolower($name2);
                              $name2 = str_replace(" ", "-", $name2);

                              
                              if ($name == $name2) {
                                $entra = true;
                              }
                            }
                          }
                          if ($entra==false) {
                            continue;
                          } 
                        ?>
                        <?php 
                          $terms = get_the_terms( $post->ID, 'portfolio_tags' );

                          if ( $terms && ! is_wp_error( $terms ) ) :
                            $links = array();

                          foreach ( $terms as $term ) {
                            $links[] = $term->name;
                          }

                          $links = str_replace(' ', '-', $links);
                          $tax = join( " ", $links );

                          else :
                            $tax = '';
                          endif;
                        ?>

                        <?php 
                        // Portfolio columns variable from Theme Options
                        $pcount = ta_option('portfolio_column', '3');
                        ?>

                        <div class="col-xs-12 col-sm-6 col-md-<?php echo $pcount; ?> item <?php echo strtolower($tax); ?>">
                          <div class="portfolio-item">
                            <a href="#portfolio-<?php echo get_the_ID() ?>" class="portfolio-link" data-toggle="modal">
                              <div class="caption">
                                <div class="caption-content">
                                  <i class="fa fa-search-plus fa-3x"></i>
                                </div>
                              </div>
                              <img src="<?php echo wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>" class="img-responsive">
                            </a>
                            <h3><a href="#portfolio-<?php echo get_the_ID() ?>" data-toggle="modal"><?php the_title(); ?></a></h3>
                          </div>
                        </div>
                        <?php endwhile; ?>
                        <!-- end of the loop -->

                      </div> <!-- .#portfolio-items -->
                    </div> <!-- .row -->

                    <?php wp_reset_postdata(); ?>

                  <?php else : ?>
                    <p><?php _e( 'Sorry, no posts matched your criteria.', 'ta-portfolio' ); ?></p>
                  <?php endif; ?>
                  <!-- Here-->
                </div>
              </div>
            </div>
       </section>
       
       
		    <section class="timeline-2 contact" id="contact">
            <div class="line row breakpage">
              <div class=" content-wrap bg1">
                <h2 class="section-title">Contact</h2>
              </div>
            </div>
            <div class="line row">
              <div class=" content-wrap bg1">
                <div class="line-content contact-content">
                  <h3 class="section-item-title-1 hidden-print">SEND ME A MESSAGE</h3>

                    <div class="col-md-8 contact-form-wrapper hidden-print">
                      <form name="sentMessage" id="contactForm" novalidate>
                        <input type="hidden" id="emailto" value="<?php echo $contact["email"]; ?>">
                        <label><?php _e( 'Name', 'ta-portfolio' ); ?></label>
                        <input type="text" class="form-control" placeholder="Name" id="name" required data-validation-required-message="<?php _e( 'Please enter your name.', 'ta-portfolio' ); ?>">
                        <p class="help-block text-danger"></p>
                        <label><?php _e( 'Email Address', 'ta-portfolio' ); ?></label>
                        <input type="email" class="form-control" placeholder="Email Address" id="email" required data-validation-required-message="<?php _e( 'Please enter your email address.', 'ta-portfolio' ); ?>">
                        <p class="help-block text-danger"></p>
                        <label><?php _e( 'Subject', 'ta-portfolio' ); ?></label>
                        <input type="text" class="form-control" placeholder="Subject" id="subject" required data-validation-required-message="<?php _e( 'Please enter a subject.', 'ta-portfolio' ); ?>">
                        <p class="help-block text-danger"></p>
                        <label><?php _e( 'Message', 'ta-portfolio' ); ?></label>
                        <textarea rows="5" class="form-control" placeholder="Message" id="message" required data-validation-required-message="<?php _e( 'Please enter a message.', 'ta-portfolio' ); ?>"></textarea>
                        <p class="help-block text-danger"></p>
                        <div id="success"></div>
                        <button type="submit" class="btn btn-success btn-lg"><?php _e( 'Send', 'ta-portfolio' ); ?></button>
                      </form>
                    </div>
                    <div class="col-md-4 contact-infos">
                      <h4 class="contact-subtitle-1">ADDRESS</h4>
                      <p><?php echo $contact["address"]; ?></p>
                      <h4 class="contact-subtitle-1">PHONE</h4>
                      <p><?php echo $contact["phone"]; ?></p>
                      <h4 class="contact-subtitle-1">MAIL</h4>
                      <p><?php echo $contact["email"]; ?></p>
                    </div>

                </div>
              </div>
            </div>
			 </section>
		</div>
	</div>
    </div> 
  </div> 
</section>
<!-- /CONTENT
========================================================= -->
<!-- Portfolio Modals -->
  <?php 
  // the query
  $the_query = new WP_Query( array( 'post_type' => 'portfolio', 'posts_per_page' => -1 ) ); ?>

  <?php if ( $the_query->have_posts() ) : ?>

    <!-- the loop -->
    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
    <?php $Contributors = get_post_meta($post->ID, 'repeatable_fields', true);
      $entra = false;
      if (is_array($Contributors)) {
        foreach ( $Contributors as $contributor ) {
          $name = esc_attr( $contributor['name']);
          $name = strtolower($name);
          $name = str_replace(" ", "-", $name);

          $name2 = $title;
          $name2 = esc_attr( $name2 );
          $name2 = strtolower($name2);
          $name2 = str_replace(" ", "-", $name2);

          
          if ($name == $name2) {
            $entra = true;
          }
        }
      }
      if ($entra==false) {
        continue;
      } ?>
    <div class="portfolio-modal modal fade" id="portfolio-<?php echo get_the_ID() ?>" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-content">
        <div class="close-modal" data-dismiss="modal">
          <div class="lr">
            <div class="rl">
            </div>
          </div>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
              <div class="modal-body">
                <h2><?php the_title(); ?></h2>
                <hr class="star-primary portfolio">
                <img src="<?php echo wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>" class="img-responsive img-centered">
                <p>
                  <?php if( has_excerpt() ) {
                    the_excerpt();
                  } else {
                    $content = strip_shortcodes( get_the_content() );
                    echo wp_trim_words( $content, 50 );
                  } ?>
                </p>
                <ul class="list-inline item-details">
                  <li><?php _e( 'Client:', 'ta-portfolio' ); ?>
                    <strong><a href="<?php echo get_post_meta( $post->ID, '_cmb_clienturl', true); ?>" target="_blank"><?php echo get_post_meta( $post->ID, '_cmb_clientname', true); ?></a></strong>
                  </li>
                  <li><?php _e( 'Date:', 'ta-portfolio' ); ?>
                    <strong><?php echo get_the_date(); ?></strong>
                  </li>
                  <li><?php _e( 'Categories:', 'ta-portfolio' ); ?>
                    <?php $terms = wp_get_post_terms( $post->ID, 'portfolio_tags', array( "fields" => "names" ) ); ?>
                    <strong><?php echo implode( ' / ',$terms ); ?></strong>
                  </li>
                </ul>
                <a class="btn btn-default" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php _e( 'Learn More', 'ta-portfolio' ); ?></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php endwhile; ?>
    <!-- end of the loop -->

    <?php wp_reset_postdata(); ?>

  <?php else : ?>
    <p><?php _e( 'Sorry, no posts matched your criteria.', 'ta-portfolio' ); ?></p>
  <?php endif; ?>
<?php get_footer(); ?>