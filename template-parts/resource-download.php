<?php

$link = get_field( 'resource_external_link' );

if( $link ) {
  echo '<a href="' . esc_url( $link ) . '" class="button">DOWNLOAD</a>';
} else {
  $link = add_query_arg( 'resource', get_the_ID(), home_url('/download/') );

  $file = get_field( 'pdf_pdf' );
  if( $file ) {
    echo '<a href="' . esc_url( $link ) . '" class="button">DOWNLOAD</a>';
  }
}
?>
