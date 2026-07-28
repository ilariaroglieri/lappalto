<?php get_header(); ?>

<main>
  <?php if ( have_posts() ) : ?>
    <div id="content-page" class="content full-height d-flex d-column end">
      <?php  while ( have_posts() ) : the_post(); ?>

        <div class="contact-text">
          <?php the_content(); ?>
        </div>
      
      <?php endwhile; ?>
    </div>
  <?php else: ?>

    <h2>Woops...</h2>
    <p>Sorry, no posts found.</p>

  <?php endif; ?>

</main>

<?php get_footer(); ?>