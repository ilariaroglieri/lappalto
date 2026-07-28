<?php get_header(); ?>

<main>
  <?php if ( have_posts() ) : ?>
    <?php 
      $today = new DateTime(date('Y-m-d'), new DateTimeZone('UTC'));
    ?>
    <div id="content-exhibitions" class="content">
      <?php while ( have_posts() ) : the_post(); ?>

        <?php 
          $startD = DateTime::createFromFormat('Ymd', get_field('starting_date', false, false));
          $endD = DateTime::createFromFormat('Ymd', get_field('ending_date', false, false));

          $status = '';
          $file = get_field('pdf_file');
          $cats = get_the_category();

          if ($startD <= $today && $today <= $endD):
            $status = 'ongoing';
          elseif ($startD > $today):
            $status = 'incoming';
          elseif ($endD < $today):
            $status = 'ended';
          endif;
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-status="<?= $status ?>">
          <div class="exhibition-row d-flex flex-row">
            <div class="d-3-twelfth">
              <h2 class="entry-cat"><?= $status == 'ongoing' ? 'Ongoing / ' : '' ?><?= implode( ', ', array_column( $cats, 'name' ) ); ?></h2>
            </div>
            <div class="d-3-twelfth">
              <h3 class="entry-title"><?php the_title(); ?></h3>
            </div>
            <div class="d-3-twelfth">
              <h3 class="entry-artists"><?= get_field('artist'); ?></h3>
            </div>
            <div class="d-3-twelfth">
              <?php if ($file): ?>
                <a href="<?= $file['url'] ?>" class="entry-download">Download PDF</a>
              <?php endif; ?>
            </div>
          </div>
        </article>
      <?php endwhile; ?>
    </div>
  <?php else: ?>

    <h2>Woops...</h2>
    <p>Sorry, no posts found.</p>

  <?php endif; ?>

</main>

<?php get_footer(); ?>