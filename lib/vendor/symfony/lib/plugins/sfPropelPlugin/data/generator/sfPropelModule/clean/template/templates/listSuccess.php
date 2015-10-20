<?php $form = $this->getFormObject() ?>
<h3><?php echo sfInflector::humanize($this->getModuleName()) ?> List</h3>

<table>
	<thead>
		<tr>
<?php foreach ($this->getTableMap()->getColumns() as $column): ?>
			<th><?php echo sfInflector::humanize(sfInflector::underscore($column->getPhpName())) ?></th>
<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		[?php foreach ($pager->getResults() as $<?php echo $this->getSingularName() ?>): ?]
		<tr>
<?php foreach ($this->getTableMap()->getColumns() as $column): ?>
<?php if ($column->isPrimaryKey()): ?>
<?php if (isset($this->params['route_prefix']) && $this->params['route_prefix']): ?>
			<td><a href="[?php echo url_for('<?php echo $this->getUrlForAction(isset($this->params['with_show']) && $this->params['with_show'] ? 'show' : 'edit') ?>', $<?php echo $this->getSingularName() ?>) ?]">[?php echo $<?php echo $this->getSingularName() ?>->get<?php echo $column->getPhpName() ?>() ?]</a></td>
<?php else: ?>
			<td>
				<a href="[?php echo url_for('<?php echo $this->getModuleName() ?>/edit?<?php echo $this->getPrimaryKeyUrlParams() ?>) ?]">Edit</a>
				[?php echo link_to('Delete', '<?php echo $this->getModuleName() ?>/delete?<?php echo $this->getPrimaryKeyUrlParams('$'.$this->getSingularName(), true) ?>, array('method' => 'delete', 'confirm' => 'Are you sure?')) ?]
			</td>
<?php endif; ?>
<?php else: ?>
			<td>[?php echo $<?php echo $this->getSingularName() ?>->get<?php echo $column->getPhpName() ?>() ?]</td>
<?php endif; ?>
<?php endforeach; ?>
		</tr>
		[?php endforeach; ?]
	</tbody>
	[?php if ($pager->haveToPaginate()): ?]
	<tfoot>
		<tr>
			<td colspan="<?php echo count($this->getTableMap()->getColumns())?>">
        <div class="pagination pagination-centered">
          <ul>
            <li><a href="[?php echo url_for('<?php echo $this->getModuleName() ?>/list?page='.$pager->getFirstPage()) ?]" title="First page">&laquo;</a></li>
            <li><a href="[?php echo url_for('<?php echo $this->getModuleName() ?>/list?page='.$pager->getPreviousPage()) ?]" title="Previouse page">&lt;</a></li>
            [?php $links = $pager->getLinks(); foreach ($links as $page): ?]
              [?php if($page == $pager->getPage()): ?]
                <li class="active"><a href="javascript:;">[?php echo $page ?]</a></li>
              [?php else: ?]
                <li><a href="[?php echo url_for('<?php echo $this->getModuleName() ?>/list?page='.$page) ?]" title="Page ">[?php echo $page ?]</a></li>
              [?php endif; ?]
              </li>
            [?php endforeach ?]
            <li><a href="[?php echo url_for('<?php echo $this->getModuleName() ?>/list?page='.$pager->getNextPage()) ?]" title="Next page">&gt;</a></li>
            <li><a href="[?php echo url_for('<?php echo $this->getModuleName() ?>/list?page='.$pager->getLastPage()) ?]" title="Last page">&raquo;</a></li>
          </ul>
        </div>
			</td>
		</tr>
	</tfoot>
	[?php endif ?]
</table>

<?php if (isset($this->params['route_prefix']) && $this->params['route_prefix']): ?>
	<a href="[?php echo url_for('<?php echo $this->getUrlForAction('new') ?>') ?]">New</a>
<?php else: ?>
	<a href="[?php echo url_for('<?php echo $this->getModuleName() ?>/new') ?]">New</a>
<?php endif; ?>
