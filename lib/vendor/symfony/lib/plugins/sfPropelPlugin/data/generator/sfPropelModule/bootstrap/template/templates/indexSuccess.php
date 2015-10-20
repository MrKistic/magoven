<?php $form = $this->getFormObject() ?>
  <h2><?php echo sfInflector::humanize($this->getModuleName()) ?> List</h2>

  <p class="lead">
    Your description of this page goes here.
  </p>

[?php include_partial('flashes') ?]

  <div class="btn-toolbar text-right">

<?php if (isset($this->params['route_prefix']) && $this->params['route_prefix']): ?>
  <a href="[?php echo url_for('<?php echo $this->getUrlForAction('new'); ?>'); ?]" class="btn btn-small btn-info"><i class="icon-plus icon-white"></i> Add New <?php echo sfInflector::humanize($this->getSingularName()) ?></a>
<?php else: ?>
  <a href="[?php echo url_for('<?php echo $this->getModuleName(); ?>/new'); ?]" class="btn btn-small btn-info"><i class="icon-plus icon-white"></i> Add New <?php echo sfInflector::humanize($this->getSingularName()) ?></a>
<?php endif; ?>


  </div>

[?php if (!$pager->getNbResults()): ?]

  <table class="table table-bordered table-hover list">
    <tr>
      <td><h3>No results</h3></td>
    </tr>
  </table>

[?php else: ?]

  <table class="table table-bordered table-hover list">

    <thead>
      <tr>
<?php foreach ($this->getTableMap()->getColumns() as $column): ?>
        <th><?php echo sfInflector::humanize(sfInflector::underscore($column->getPhpName())) ?></th>
<?php endforeach; ?>
        <th>&nbsp;</th>
      </tr>
    </thead>

    <tbody>
      [?php foreach ($pager->getResults() as $<?php echo $this->getSingularName() ?>): ?]
      <tr>
<?php

  /** @var ColumnMap $column */
  foreach ($this->getTableMap()->getColumns() as $column) {

    if ($column->isPrimaryKey() && !isset($actions)) {

      if (isset($this->params['route_prefix']) && $this->params['route_prefix']):

        link_to();

        $actions = "<a href=\"[?php echo url_for('". $this->getUrlForAction(isset($this->params['with_show']) && $this->params['with_show'] ? 'show' : 'edit') ."', $". $this->getSingularName() .") ?]\">[?php echo $". $this->getSingularName() ."->get". $column->getPhpName() ."() ?]</a>";

      else:

        $actions = "<a class=\"btn btn-mini\" href=\"[?php echo url_for('". $this->getModuleName() ."/show?". $this->getPrimaryKeyUrlParams() .") ?]\"> <i class=\"icon-eye-open\"></i> View</a> ";
        $actions .= "<a class=\"btn btn-mini\" href=\"[?php echo url_for('". $this->getModuleName() ."/edit?". $this->getPrimaryKeyUrlParams() .") ?]\"> <i class=\"icon-edit\"></i> Edit</a> ";
        $actions .= "[?php echo link_to('<i class=\"icon-remove\"></i> Delete', '". $this->getModuleName() ."/delete?". $this->getPrimaryKeyUrlParams() .", array('class' => 'btn btn-mini', 'method' => 'delete', 'confirm' => 'Are you sure?')) ?]";

      endif;

    } // endif
?>
        <td>[?php echo $<?php echo $this->getSingularName() ?>->get<?php echo $column->getPhpName() ?>() ?]</td>
<?php
  } // endforeach
?>
        <td nowrap><?php echo $actions; ?></td>
      </tr>
      [?php endforeach; ?]
    </tbody>

    [?php if ($pager->haveToPaginate()): ?]
    <tfoot>
      <tr>
        <td colspan="<?php echo count($this->getTableMap()->getColumns())?>">
          <div class="pagination pagination-centered">
            <ul>
              <li><a href="[?php echo url_for('<?php echo $this->getModuleName() ?>/index?page='.$pager->getFirstPage()) ?]" title="First page">&laquo;</a></li>
              <li><a href="[?php echo url_for('<?php echo $this->getModuleName() ?>/index?page='.$pager->getPreviousPage()) ?]" title="Previous page">&lt;</a></li>
              [?php $links = $pager->getLinks(); foreach ($links as $page): ?]
                  [?php if($page == $pager->getPage()): ?]
                    <li class="active"><a href="javascript:;">[?php echo $page ?]</a></li>
                  [?php else: ?]
                    <li><a href="[?php echo url_for('<?php echo $this->getModuleName() ?>/index?page='.$page) ?]" title="Page ">[?php echo $page ?]</a></li>
                  [?php endif; ?]
                </li>
              [?php endforeach ?]
              <li><a href="[?php echo url_for('<?php echo $this->getModuleName() ?>/index?page='.$pager->getNextPage()) ?]" title="Next page">&gt;</a></li>
              <li><a href="[?php echo url_for('<?php echo $this->getModuleName() ?>/index?page='.$pager->getLastPage()) ?]" title="Last page">&raquo;</a></li>
            </ul>
          </div>
        </td>
      </tr>
    </tfoot>
    [?php endif ?]

  </table>

[?php endif; ?]
