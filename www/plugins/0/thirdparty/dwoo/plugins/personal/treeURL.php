<?php

function Dwoo_Plugin_treeURL(Dwoo $dwoo, $pluginName, $treeClassKey)
{
  return htmlspecialchars(Premanager\Execution\PageNode::getTreeURL($pluginName, $treeClassKey));
}
?>