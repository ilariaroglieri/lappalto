<?php get_header(); ?>

<main>
  <div class="container d-flex">
    <?php if ( have_posts() ) : ?>
      <div id="content-home" class="content d-flex d-column space-between grow">
        <?php  while ( have_posts() ) : the_post(); ?>

          <div class="intro-text">
            <?php the_content(); ?>
          </div>

          <?php
            $toShow = exhibitionToShow();
          ?>

          <div class="current-exhibition">
            <span><?= $toShow['state'] ?></span>
            <h3><?= $toShow['currExhibition']['cleanStartD'] . '–' . $toShow['currExhibition']['cleanEndD']; ?></h3>
            <h2 class="latest-ex-title"><?= $toShow['currExhibition']['title'] ?></h2>
            <h3 class="latest-ex-artists"><?= $toShow['currExhibition']['artist'] ?></h3>
          </div>

          <?php // home carousel
          $carousel = get_field('home_carousel'); ?>

          <div id="home-slider" class="p-absolute"> 
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