<?php //$Id: node-og.tpl.php,v 1.4.2.2 2006/11/19 13:14:01 weitzman Exp $ 
?>
<div class="node<?php if ($sticky) { print " sticky"; } ?><?php if (!$status) { print " node-unpublished"; } ?>">
  <?php if ($page == 0) { ?><h2 class="title"><a href="<?php print $node_url?>"><?php print $title?></a></h2><?php }; ?>
  <span class="taxonomy"><?php print $terms?></span>
  <div class="content"><?php print $content?></div>
</div>
