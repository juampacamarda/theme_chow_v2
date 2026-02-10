<?php
  $args = array(
      'numberposts' => 5, // number of posts to return
      'post_type' => 'post' // change this to the post type you want to retrieve
  );

  $posts = get_posts( $args );

  if ( $posts ) : 
  foreach ( $posts as $post ) : setup_postdata( $post );  ?>

  <li class="d-block text-center">
    <a href="<?php the_permalink(); ?>">
      <?php the_title(); ?>
    </a>
  </li>

<?php endforeach; ?>
<?php wp_reset_postdata(); ?>

<?php endif; ?>