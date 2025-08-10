<?php
if (!defined('DOKU_INC')) die();
@require_once(dirname(__FILE__).'/tpl_functions.php');
header('X-UA-Compatible: IE=edge');

$versionarr = getVersionData();
$version = substr($versionarr['date'], 0, 10);


$showSidebar = false;
if ($ID != $conf['sidebar']):
    $showSidebar = page_findnearest($conf['sidebar']) && ($ACT == 'show');
endif;
?>
<!DOCTYPE html>
<html lang="<?php echo $conf['lang']; ?>" class="no-js">
<head>
    <meta charset="UTF-8" />
    <title><?php tpl_pagetitle(); ?> [<?php echo strip_tags($conf['title']); ?>]</title>
    <script>
      (function(H){
        H.className = H.className.replace(/\bno-js\b/, 'js');
      })(document.documentElement);
    </script>
    <?php
      tpl_metaheaders();
      echo '<meta name="viewport" content="width=device-width,initial-scale=1" />';
      echo _tpl_favicon();
      tpl_includeFile('meta.html');
      if (file_exists(DOKU_PLUGIN.'displaywikipage/code.php')) {
          include_once(DOKU_PLUGIN.'displaywikipage/code.php');
      }
    ?>
</head>
<body>
<!--[if lte IE 8 ]><div id="IE8"><![endif]-->

<div id="dokuwiki__site">
  <div id="dokuwiki__top"
       class="site dokuwiki <?php echo tpl_classes(); ?><?php if ($showSidebar) echo ' hasSidebar'; ?>">
    <?php html_msgarea(); ?>

    <!-- MAIN FLEX CONTAINER -->
    <div id="layout-container"><!-- 2-column layout wrapper -->

      <!-- SIDEBAR COLUMN -->
      <div id="layout-sidebar">
        <div id="dokuwiki__aside">
          <div class="sidebar-logo">
            <a href="<?php echo wl(); ?>" title="<?php echo $conf['title']; ?>">
              <img src="<?php echo ml('wiki:logo.png'); ?>" width="130" alt="Home logo" />
            </a>
          </div>
          <?php
            tpl_includeFile('sidebarheader.html');
            tpl_include_page($conf['sidebar'], 1, 1);
            tpl_includeFile('sidebarfooter.html');
          ?>
        </div><!-- /dokuwiki__aside -->
      </div><!-- /layout-sidebar -->

      <!-- MAIN COLUMN (HEADER + CONTENT + PAGE TOOLS + FOOTER) -->
      <div id="layout-maincolumn">
        <!-- HEADER BAR -->
        <div id="dokuwiki__header">
		
		
		<div class="breadcrumbs">
            <?php
            if ($conf['breadcrumbs']) {
              tpl_breadcrumbs();
            } elseif ($conf['youarehere']) {
              tpl_youarehere();
            }
		  ?>
		</div>

          <?php if ($ACT != 'denied'): ?>
            <div id="dokuwiki__sitetools">
              <?php tpl_searchform(); ?>
            </div>
			
			
          <?php endif; ?>
        </div><!-- /dokuwiki__header -->

        <!-- CONTENT -->
        <div id="dokuwiki__content">
          <div class="pad">
            <?php
              tpl_flush();
              tpl_includeFile('pageheader.html');
            ?>
            <div class="page">
              <!-- wikipage start -->
              <?php tpl_content(); ?>
              <!-- wikipage stop -->
              <div class="clearer"></div>
            </div>
            <?php
              tpl_flush();
              tpl_includeFile('pagefooter.html');
            ?>
          </div>
        </div><!-- /dokuwiki__content -->

        <!-- PAGE TOOLS -->
        <?php if ($ACT != 'denied'): ?>
        <div id="dokuwiki__pagetools">
          <?php if (isset($conf['useacl']) && $conf['useacl']): ?>
            <h3 class="a11y"><?php echo $lang['user_tools']; ?></h3>
            <ul>
              <?php echo (new \dokuwiki\Menu\UserMenu())->getListItems(); ?>
            </ul>
          <?php endif; ?>

          <?php if (auth_quickaclcheck($ID) > AUTH_READ): ?>
            <h3 class="a11y"><?php echo $lang['page_tools']; ?></h3>
            <ul class="pagetools"><?php echo (new \dokuwiki\Menu\PageMenu())->getListItems(); ?></ul>
            <h3 class="a11y"><?php echo $lang['site_tools']; ?></h3>
            <ul class="sitetools"><?php echo (new \dokuwiki\Menu\SiteMenu())->getListItems(); ?></ul>
          <?php endif; ?>
        </div>
        <?php endif; ?>


		<?php $tpl_config = parse_ini_file(__DIR__ . '/style.ini', true)['replacements']; ?>

		<?php if (!$tpl_config['hide__footer']): ?>
		  <!-- FOOTER -->
		  <div id="dokuwiki__footer">
			<div class="pad footer-flex">

			  <div class="footer-left">
				<?php
				  $parts = [];
				  if ($tpl_config['show__file_name']) {
					$parts[] = basename($INFO['filepath']);
				  }
				  if ($tpl_config['show__last_modified']) {
					$parts[] = $lang['lastmod'] . ': ' . dformat($INFO['lastmod']);
				  }
				  echo implode('; ', $parts);
				?>
			  </div>

			  <?php if ($tpl_config['show__user_info'] && !empty($_SERVER['REMOTE_USER'])): ?>
				<div class="footer-user">
				  <?php tpl_userinfo(); ?>
				</div>
			  <?php endif; ?>
			</div>

			<!-- License button (optional) -->
			<div class="pad license-block">
			  <?php tpl_license('button'); ?>
			</div>
		  </div><!-- /dokuwiki__footer -->
		<?php endif; ?>

        <?php
          tpl_includeFile('footer.html');
          if (function_exists('dwp_display_wiki_page')) {
            dwp_display_wiki_page(":wiki:copyright");
          }
        ?>
      </div><!-- /layout-maincolumn -->

    </div><!-- /layout-container -->

  </div><!-- /dokuwiki__top -->
</div><!-- /dokuwiki__site -->

<div class="no"><?php tpl_indexerWebBug(); ?></div>
<!--[if lte IE 8 ]></div><![endif]-->
</body>
</html>

