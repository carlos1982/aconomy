<?php
/**
 * Erwartet $file_path
 * Erwartet $file_type
 */
header('Content-Description: File Transfer');
header('Content-Type: $file_type');
header('Content-Disposition: attachment; filename='.basename($file_path));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_path));
//ob_clean();
//flush();
readfile($file_path);
exit;
?>