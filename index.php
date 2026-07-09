<?php get_header(); ?>

<main class="content" id="content-generic">
  <div class="container">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <h2 class="entry-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
        <?php if ( has_post_thumbnail() ) : ?>  
          <a  href="<?php the_permalink() ?>">          
            <?php the_post_thumbnail(); ?>
          </a>
        <?php endif; ?>
      </article>
    
    <?php endwhile; else: ?>

      <h2>Woops...</h2>
      <p>Sorry, no posts found.</p>

    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>