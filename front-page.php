<?php get_header(); ?>

<main>
  <?php if ( have_posts() ) : ?>
    <div id="content-home" class="content d-flex d-column space-between grow">
      <?php  while ( have_posts() ) : the_post(); ?>

        <div class="intro-text">
          <?php the_content(); ?>
        </div>

        <?php // current exhibition 
        $args = [
          'post_type' => 'post',
          'posts_per_page' => -1,
        ];

        $exhibitions = new WP_Query($args);
        $ongoing = null;
        $today = new DateTime(date('Y-m-d'), new DateTimeZone('UTC')); 

        while ($exhibitions->have_posts()): $exhibitions->the_post();
          $startD = DateTime::createFromFormat('Ymd', get_field('starting_date', false, false));
          $endD = DateTime::createFromFormat('Ymd', get_field('ending_date', false, false));

          $startY = $startD->format('Y'); 
          $endY = $endD->format('Y');

          $startM = $startD->format('m');
          $endM = $endD->format('m');

          $cleanStartD = null;
          if ($startY == $endY) {
            if ($startM == $endM) {
              $cleanStartD = $startD->format('j');
            } else {
              $cleanStartD = date_i18n('j F', $startD->getTimestamp());
            }
          } else {
            $cleanStartD = date_i18n('j F Y', $startD->getTimestamp());
          }

          $cleanEndD = date_i18n('j F Y', $endD->getTimestamp());


          if ($startD <= $today && $today <= $endD): ?>

            <div class="current-exhibition">
              <p>Ongoing</p>
              <p><?= $cleanStartD . '–' . $cleanEndD; ?></p>
              <h2 class="latest-ex-title"><?php the_title(); ?></h2>
              <h3 class="latest-ex-artists"><?= get_field('artist'); ?></h3>
            </div>
            
          <?php break; // takes the first
          endif;
        endwhile;
        wp_reset_postdata(); ?>

      
      <?php endwhile; ?>
    </div>
  <?php else: ?>

    <h2>Woops...</h2>
    <p>Sorry, no posts found.</p>

  <?php endif; ?>

</main>

<?php get_footer(); ?>