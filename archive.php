<?php get_header(); ?>

<main>
  <div class="container-fluid">
    <?php
      $artists = get_posts([
        'post_type'      => 'artist',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
      ]);

      foreach ( $artists as $artist ) :
        setup_postdata( $artist );
        $artist->slug     = $artist->post_name;
        $artist->carousel = get_field( 'artist_carousel', $artist->ID );
        $artist->file     = get_field( 'download_cv', $artist->ID );
      endforeach;

      wp_reset_postdata();
    ?>

      <div id="content-artists" class="content">
        <div class="d-flex m-column end">
          <div id="artists-list" class="d-3-twelfth t-half m-hidden">
            <ul>
              <?php foreach ($artists as $artist): ?>
                <li class="artist-list-btn" data-title="<?= $artist->slug ?>"><?= $artist->post_title ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div id="artists-contents" class="d-half m-whole">
            <?php foreach ($artists as $artist): ?>
              <div id="<?= $artist->slug ?>" class="artist-el spacing-b-6">
                <h2 class="artist-title row-btn m-visible"><?= $artist->post_title ?></h2>

                <?php if ($artist->carousel): ?>
                  <div class="artist-carousel-row spacing-b-2">
                    <div class="embla-slider spacing-p-b-4">
                      <div class="embla-track">
                        <?php foreach( $artist->carousel as $i => $img ):
                          $orientation = $img['width'] > $img['height'] ? 'landscape' : 'portrait'; 
                          $caption = $img['caption']; ?>
                          <div class="embla-slide <?= $orientation ?>">
                            <?= wp_get_attachment_image($img['ID'], 'medium-large', false, [
                              'sizes' => '(max-width: 640px) 100vw, (max-width: 768px) 66vw, 42vw'
                            ]) ?>
                            <?php if ($caption):?>
                              <p><?= $caption ?></p>
                            <?php endif; ?>
                          </div>
                        <?php endforeach ?>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>

                <div class="artist-texts">
                  <h2 class="artist-title m-hidden"><?= $artist->post_title ?></h2>
                  <?php if ($artist->file): ?>
                    <a href="<?= $artist->file['url'] ?>" class="artist-download">Download PDF</a>
                  <?php endif; ?>
                  <div class="artist-bio spacing-t-2">
                    <?= $artist->post_content ?>
                  </div>
                </div>
              </div>
            <?php endforeach ?>
          </div>
        </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>