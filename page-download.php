<?php
$resource = intval( $_GET['resource'] );

//Check if the user can access to the resource
$nm = Nine3_Simple_Membership::getInstance();

if( $nm->user_can_access_to_resource( $resource ) ) {
  $file = get_field( 'pdf_pdf', $resource );

  // Store the download statistics in the database
  ba_download_statistics($file);

  //Try to hide the real filename
  $filename = sanitize_title( get_the_title( $resource ) );

  $file = str_replace( home_url(), untrailingslashit(ABSPATH), $file['url'] );
  $info = pathinfo( $file );
  $filename .= '.' . $info['extension'];
  if (file_exists($file)) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="'.$filename.'"');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($file));
      readfile($file);
      exit;
  }
}

header( 'Location: ' . add_query_arg( array( 'denied' => 1 ), get_the_permalink( $resource ) ) );


/**
 * Store the following information about the download
 *
 */
function ba_download_statistics($file) {
  global $wpdb, $resource;

  $charset_collate = $wpdb->get_charset_collate();
  $tablename = $wpdb->prefix . 'download_stats';

  $sql = "CREATE TABLE $tablename (
    id BIGINT(20) NOT NULL AUTO_INCREMENT,
    time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    file_id BIGINT(9) NOT NULL,
    file_title TEXT,
    file_url TEXT,
    user_id BIGINT(9) NOT NULL,
    user_email VARCHAR(255),
    post_id BIGINT(20) NOT NULL,
    post_title TEXT,
    post_author TEXT,
    UNIQUE KEY id (id)
  ) $charset_collate;";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );

  // Current user
  $current_user = wp_get_current_user();

  $post_author_id = get_post_field( 'post_author', $resource );
  $author = get_the_author_meta('display_name', $post_author_id);
  $wpdb->insert(
    $tablename,
    array(
      'time' => current_time('mysql', 1),
      'user_id' => get_current_user_id(),
      'user_email' => $current_user->user_email,
      'post_id' => $resource,
      'post_title' => get_the_title($resource),
      'post_author' => $author,
      'file_id' => $file['ID'],
      'file_title' => $file['title'],
      'file_url' => $file['url'],
    ),
    array(
      '%s',
      '%d',
      '%s',
      '%d',
      '%s',
      '%s',
      '%d',
      '%s',
      '%s',
    )
  );
}
