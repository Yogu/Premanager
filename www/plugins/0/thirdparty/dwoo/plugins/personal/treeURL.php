<?php

function Dwoo_Plugin_treeURL(Dwoo $dwoo, $plugin, $tree)
{
  return Node::getTreeNodeURL($plugin, $tree);
}
?>