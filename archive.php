<?php get_header(); ?>

<main class="content" id="content-archive">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <?php 
      $today = new DateTime(date('Y-m-d'));
      $startD = new DateTime(get_field('starting_date', false, false));
      $endD = new DateTime(get_field('ending_date', false, false));
      $status = '';
      $file = get_field('pdf_file');

      if ($startD <= $today && $today <= $endD):
        $status = 'ongoing';
      elseif ($startD > $today):
        $status = '';
      elseif ($endD < $today):
        $status = '';
      endif;
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-status="<?= $status ?>">
      <div class="exhibition-row" class="d-flex flex-row">
        <div class="d-3-twelfth">
          <h2 class="entry-cat"><?= $status ?> / Categoria</h2>
        </div>
        <div class="d-3-twelfth">
          <h3 class="entry-title"><?php the_title(); ?></h3>
        </div>
        <div class="d-3-twelfth">
          <h3 class="entry-artists"><?= get_field('artist'); ?></h3>
        </div>
        <div class="d-3-twelfth">
          <a href="<?= $file['url'] ?>" class="entry-download">Download PDF</h3>
        </div>
    </article>
  
  <?php endwhile; else: ?>

    <h2>Woops...</h2>
    <p>Sorry, no posts found.</p>

  <?php endif; ?>

</main>

<?php get_footer(); ?>