<?php
/*
Author: Louai Munajim
Source: http://elouai.com/force-download.php
Contributors: Jorg Weske, Rajkumar Singh
*/

$filename = $_GET['file'];

if ( $filename == "" || !file_exists( $filename ) )
  exit;

// required for IE, otherwise Content-disposition is ignored
if ( ini_get( 'zlib.output_compression' ) )
  ini_set( 'zlib.output_compression', 'Off' );

// addition by Jorg Weske
$file_extension = strtolower( substr( strrchr( $filename, "." ), 1 ) );

switch( $file_extension ) {
  case "pdf": $ctype="application/pdf"; break;
  case "mp4": $ctype="application/octet-stream"; break;
  case "mp3": $ctype="application/octet-stream"; break;
  case "gif": $ctype="image/gif"; break;
  case "png": $ctype="image/png"; break;
  case "jpeg":
  case "jpg": $ctype="image/jpg"; break;
  default: $ctype="application/force-download";
}

header( "Pragma: public" ); // required
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
header( "Cache-Control: private", false ); // required for certain browsers 
header( "Content-Type: $ctype" );
// change, added quotes to allow spaces in filenames, by Rajkumar Singh
header( "Content-Disposition: attachment; filename=\"" . basename($filename) . "\";" );
header( "Content-Transfer-Encoding: binary" );
header( "Content-Length: ". filesize($filename) );
readfile( "$filename" );
	exit();

?>