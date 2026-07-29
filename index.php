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

          <?php // home carousel
          $carousel = get_field('carousel'); 

          if ($carousel): ?>
            <div class="exhibition-carousel-row spacing-t-2 spacing-b-3">
              <div class="embla-slider">
                <div class="embla-track">
                  <?php foreach( $carousel as $img ): 
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

</main>

<?php get_footer(); ?>