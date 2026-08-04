<?php get_header(); ?>

<main>
  <div class="container">
    <?php if ( have_posts() ) : ?>
      <?php 
        $today = new DateTime(date('Y-m-d'), new DateTimeZone('UTC'));
      ?>
      <div id="content-exhibitions" class="content">
        <?php while ( have_posts() ) : the_post(); ?>

          <?php 
            $i = $wp_query->current_post + 1;
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

          <?php // home carousel
            $carousel = get_field('carousel'); 
          ?>

          <article id="post-<?php the_ID(); ?>" <?php post_class('exhibition-element d-flex d-column m-column-reverse'); ?> data-status="<?= $status ?>">
            <div class="exhibition-row-header">
              <div class="entry-cat-el">
                <h2 class="entry-cat <?= $carousel ? 'row-btn' : ''?>"><?= $status == 'ongoing' ? 'Ongoing / ' : sprintf("%02d", $i) .'. ' ?><?= implode( ', ', array_column( $cats, 'name' ) ); ?></h2>
              </div>
              <div class="entry-title-el">
                <h3 class="entry-title <?= $carousel ? 'row-btn' : ''?>"><?php the_title(); ?></h3>
              </div>
              <div class="entry-artists-el">
                <h3 class="entry-artists <?= $carousel ? 'row-btn' : ''?>"><?= get_field('artist'); ?></h3>
              </div>
              <div class="entry-download-el">
                <?php if ($file): ?>
                  <a href="<?= $file['url'] ?>" class="entry-download underlined">Download PDF</a>
                <?php endif; ?>
              </div>
            </div>


            <?php if ($carousel): ?>
              <div class="exhibition-carousel-row <?= $status == 'ongoing' ? 'open' : ''; ?>">
                <div class="embla-slider">
                  <div class="embla-track">
                    <?php foreach( $carousel as $i => $img ):
                      $orientation = $img['width'] > $img['height'] ? 'landscape' : 'portrait'; ?>
                      <div class="embla-slide <?= $orientation ?>">
                        <?= wp_get_attachment_image($img['ID'], 'medium-large', false, [
                          'sizes' => '(max-width: 640px) 100vw, (max-width: 768px) 66vw, 42vw'
                        ]) ?>
                      </div>
                    <?php endforeach ?>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          </article>
        <?php endwhile; ?>
      </div>
    <?php else: ?>

      <h2>Woops...</h2>
      <p>Sorry, no posts found.</p>

    <?php endif; ?>
  </div>

</main>

<?php get_footer(); ?>