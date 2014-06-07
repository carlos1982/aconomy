<?php
$menu_str = '';				// Menu gemäß Login-Status
$menu_items = array();		// Sammelt Menu-Punkte
if (hSession::IsLoggedIn()) {
	hDebug::Add(__('Rolle').': '.__('Studierender'));
	$menu_items['Startseite'] = _Link('user','index');
	$menu_items['Locations'] = _Link('location','list');
    $menu_items['Inventory'] = _Link('user','inventory');
	$menu_items['Logout'] = _Link('user','logout');
}

foreach ($menu_items as $item_label => $menu_Link) {
	$menu_str .= '<li><a href="'.$menu_Link.'">'.__($item_label).'</a></li>';
}

?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8" />
    <title><?=__(hMeta::getTitle())?></title>
    
    <?php
    echo hCss::getCssLink();
    ?>
    
<?php
	if(hRouter::getRedirect() != '') {
		echo '<meta http-equiv="refresh" content="30; url='.hRouter::getRedirect().'">';
	} 
?>
  </head>
<body>
  <header>
    <h1><span id="project_url">aconomy.org</span><span id="module_path">/share</span></h1>
    <h2><?=__(CLAIM)?></h2>
  </header>


<nav>
<?php if(hSession::IsLoggedIn()) :?>
<ul>
		<?=$menu_str?>
</ul>
<?php endif; ?>
</nav>

<?=hError::showMessages()?>
<?=hSuccess::showMessages()?>

<article>
