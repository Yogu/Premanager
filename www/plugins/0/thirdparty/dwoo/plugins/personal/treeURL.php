<?php

function Dwoo_Plugin_treeURL(Dwoo $dwoo, $pluginName, $treeClassKey)
{
  return Premanager\Execution\PageNode::getTreeURL($pluginName, $treeClassKey);
}
?>