<?php

# Not a valid entry point, skip unless MEDIAWIKI is defined
if (!defined('MEDIAWIKI')) {
  echo <<<EOT
    To install my extension, put the following line in LocalSettings.php:
    require_once( "$IP/extensions/CSSCPrize/csscprize.php" );
  EOT;
  exit( 1 );
}

$wgExtensionCredits['specialpage'][]=array(
  'path' => __FILE__,
  'name' => 'CSSCPrize',
  'version' => '1.0',
  'author' => 'Patrick West',
  'url' => 'http://cedarweb.hao.ucar.edu',
  'descriptionmsg' => 'csscprize-desc'
);

$dir = dirname(__FILE__) . '/';

# Location of the SpecialCSSCPrize class (Tell MediaWiki to load this
# file)
$wgAutoloadClasses['CSSCPrize'] = $dir . 'CSSCPrize_body.php';

# Location of a messages file (Tell MediaWiki to load this file)
$wgExtensionMessagesFiles['CSSCPrize'] = $dir . 'CSSCPrize.i18n.php';

# Location of an aliases file (Tell MediaWiki to load this file)
$wgExtensionMessagesFiles['CSSCPrizeAlias'] = $dir .  'CSSCPrize.alias.php';

# Tell MediaWiki about the new special page and its class name
$wgSpecialPages['CSSCPrize'] = 'CSSCPrize';

?>
