<?php get_header(); ?>

<main>
  <div class="container d-flex d-column end">
    <?php if ( have_posts() ) : ?>
      <div id="content-page" class="content">
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
  </div>

  <img id="site_logo" src="<?= home_url();?>/wp-content/themes/lappalto-theme/assets/img/monogram_LA.svg" alt="L'appalto logo" />

</main>

<?php get_footer(); ?>