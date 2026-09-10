<?php get_header(); ?>

<main>
  <div class="container-fluid">
    <?php
      $artists = get_posts([
        'post_type'      => 'artist',
        'posts_per_page' => -1,
        'meta_key'       => 'artist_surname',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'suppress_filters' => false, // wpml
      ]);

      foreach ( $artists as $artist ) :
        setup_postdata( $artist );
        $artist->slug     = $artist->post_name;
        $artist->carousel = get_field( 'artist_carousel', $artist->ID );
        $artist->file     = get_field( 'download_cv', $artist->ID );
      endforeach;

      wp_reset_postdata();
    ?>

      <div id="content-artists" class="content spacing-m-b-1">
        <div class="m-hidden"></div>
        <div id="artists-list" class="m-hidden p-relative">
          <ul class="p-relative overlay">
            <?php foreach ($artists as $artist): ?>
              <li class="artist-list-btn" data-title="<?= $artist->slug ?>"><?= $artist->post_title ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div id="artists-contents">
          <?php foreach ($artists as $artist): ?>
            <div id="<?= $artist->slug ?>">
              <h2 class="artist-title row-btn m-visible"><?= $artist->post_title ?></h2>

              <div class="artist-el">
                <?php if ($artist->carousel): ?>
                  <div class="artist-carousel-row spacing-b-4 spacing-m-b-2 spacing-m-t-1">
                    <div class="embla-slider spacing-p-b-4">
                      <div class="embla-track">
                        <?php foreach( $artist->carousel as $i => $img ):
                          $orientation = $img['width'] > $img['height'] ? 'landscape' : 'portrait'; 
                          $fullImg = wp_get_original_image_url( $img['ID'], 'full');
                          $meta = wp_get_attachment_metadata($img['ID']);
                          $width = $meta['width'] ?? null;
                          $height = $meta['height'] ?? null;
                        ?>
                          <div class="embla-slide <?= $orientation ?>">
                            <a href="<?= $fullImg; ?>" data-pswp-width="<?= $width; ?>" data-pswp-height="<?= $height; ?>" data-caption=<?= $img['caption']; ?>>
                              <?= wp_get_attachment_image($img['ID'], 'medium-large', false, [
                                'sizes' => '(max-width: 640px) 100vw, (max-width: 768px) 66vw, 42vw'
                              ]) ?>
                            </a>
                          </div>
                        <?php endforeach ?>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>

                <div class="artist-texts spacing-b-6">
                  <h2 class="artist-title m-hidden"><?= $artist->post_title ?></h2>
                  <?php if ($artist->file): ?>
                    <a href="<?= $artist->file['url'] ?>" class="artist-download underlined" target="_blank">Download PDF</a>
                  <?php endif; ?>
                  <div class="artist-bio spacing-t-2">
                    <?= $artist->post_content ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach ?>
        </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>