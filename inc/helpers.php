<?php
  function get_post_terms( $post_id = null, $taxonomy = 'post_tag', $separator = ' ' ) {
    $post_id = $post_id ?: get_the_ID();

    $terms = get_the_terms( $post_id, $taxonomy );

    if ( empty( $terms ) || is_wp_error( $terms ) ) {
      return '';
    }

    $names = wp_list_pluck( $terms, 'name' );
    return implode( $separator, $names );
  }

  function exhibitionToShow() {
    $args = [
      'post_type' => 'post',
      'posts_per_page' => -1,
    ];

    $exhibitions = new WP_Query($args);
    $ongoing = null;
    $incoming = null;
    $ended = null;
    $today = new DateTime(date('Y-m-d'), new DateTimeZone('UTC')); 

    while ($exhibitions->have_posts()): $exhibitions->the_post();
      // logice del display della data
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

      $exhibition = [
        'title' => get_the_title(),
        'artist' => get_field('artist'),
        'cleanStartD' => $cleanStartD,
        'cleanEndD' => $cleanEndD,
        'startD' => $startD,
        'endD' => $endD,
      ];

      // logica per dividere le mostre
      if ($startD <= $today && $today <= $endD):
        if ($ongoing === null):
          $ongoing = $exhibition;
        endif;
      elseif ($startD > $today):
        if ($incoming === null || $startD < $incoming['startD']):
          $incoming = $exhibition;
        endif;
      elseif ($endD < $today):
        if ($ended === null || $endD > $ended['endD']):
          $ended = $exhibition;
        endif;
      endif;
        
    endwhile;
    wp_reset_postdata();

    $toShow = $ongoing ?? $incoming ?? $ended;
    $state = null;

    if ($ongoing !== null): 
      $state = 'Ongoing';
    elseif ($incoming !== null): 
      $state = 'Incoming';
    else: 
      $state = 'Latest'; 
    endif;

    $output = array(
      'currExhibition' => $toShow,
      'state' => $state
    );

    return $output;
  }
?>