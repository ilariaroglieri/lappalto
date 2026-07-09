<?php get_header(); ?>

<main id="content-home" class="content d-flex d-column space-between grow">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <div class="intro-text">
      <?php the_content(); ?>
    </div>

    <div class="current-exhibition">
      Ongoing<br/>
      September 12 -- December 18, 2026 <br/>
      Personalissime Sculture 
  
    </div>
  
  <?php endwhile; else: ?>

    <h2>Woops...</h2>
    <p>Sorry, no posts found.</p>

  <?php endif; ?>
</main>

<?php get_footer(); ?>