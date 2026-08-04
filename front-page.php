<?php get_header(); ?>

<main>
  <div class="container">
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

            foreach ($exhibitions as $exhibition): 
              if ($startD <= $today && $today <= $endD): ?>

                <div class="current-exhibition">
                  <span>Ongoing</span>
                  <h3><?= $cleanStartD . '–' . $cleanEndD; ?></h3>
                  <h2 class="latest-ex-title"><?php the_title(); ?></h2>
                  <h3 class="latest-ex-artists"><?= get_field('artist'); ?></h3>
                </div>
                
              <?php endif;
            endforeach; 
          endwhile;
          wp_reset_postdata(); ?>


          <?php // home carousel
          $carousel = get_field('home_carousel'); ?>

          <div id="home-slider" class="p-fixed full-width"> 
            <div class="d-flex flex-row">
              <div class="d-5-twelfth t-two-thirds m-whole">

                <div class="embla-slider-home">
                  <div class="embla-track">
                    <?php foreach( $carousel as $img ): 
                      $orientation = $img['width'] > $img['height'] ? 'landscape' : 'portrait'; ?>
                      <div class="embla-slide <?= $orientation ?>">
                        <?= wp_get_attachment_image($img['ID'], 'full', false, [
                          'sizes' => '(max-width: 640px) 100vw, (max-width: 768px) 66vw, 42vw'
                        ]) ?>
                      </div>
                    <?php endforeach ?>
                  </div>
                </div>
              </div>
            </div>
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