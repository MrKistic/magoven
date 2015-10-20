<h3>Issues List</h3>

<table>
	<thead>
		<tr>
			<th>Id</th>
			<th>Created at</th>
			<th>Updated at</th>
			<th>Publication</th>
			<th>Name</th>
			<th>Free</th>
			<th>Product</th>
			<th>Title</th>
			<th>Info</th>
			<th>Date</th>
			<th>Published</th>
			<th>Cover</th>
			<th>Url</th>
			<th>Itunes summary</th>
			<th>Itunes coverart url</th>
			<th>Itunes published</th>
			<th>Itunes updated</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($pager->getResults() as $Issue): ?>
		<tr>
			<td>
				<a href="<?php echo url_for('issues/edit?id='.$Issue->getId()) ?>">Edit</a>
				<?php echo link_to('Delete', 'issues/delete?id='.$Issue->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
			</td>
			<td><?php echo $Issue->getCreatedAt() ?></td>
			<td><?php echo $Issue->getUpdatedAt() ?></td>
			<td><?php echo $Issue->getPublicationId() ?></td>
			<td><?php echo $Issue->getName() ?></td>
			<td><?php echo $Issue->getFree() ?></td>
			<td><?php echo $Issue->getProductId() ?></td>
			<td><?php echo $Issue->getTitle() ?></td>
			<td><?php echo $Issue->getInfo() ?></td>
			<td><?php echo $Issue->getDate() ?></td>
			<td><?php echo $Issue->getPublished() ?></td>
			<td><?php echo $Issue->getCover() ?></td>
			<td><?php echo $Issue->getUrl() ?></td>
			<td><?php echo $Issue->getItunesSummary() ?></td>
			<td><?php echo $Issue->getItunesCoverartUrl() ?></td>
			<td><?php echo $Issue->getItunesPublished() ?></td>
			<td><?php echo $Issue->getItunesUpdated() ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
	<?php if ($pager->haveToPaginate()): ?>
	<tfoot>
		<tr>
			<td colspan="17">
        <div class="pagination pagination-centered">
          <ul>
            <li><a href="<?php echo url_for('issues/list?page='.$pager->getFirstPage()) ?>" title="First page">&laquo;</a></li>
            <li><a href="<?php echo url_for('issues/list?page='.$pager->getPreviousPage()) ?>" title="Previous page">&lt;</a></li>
            <?php $links = $pager->getLinks(); foreach ($links as $page): ?>
              <?php if($page == $pager->getPage()): ?>
                <li class="active"><a href="javascript:;"><?php echo $page ?></a></li>
              <?php else: ?>
                <li><a href="<?php echo url_for('issues/list?page='.$page) ?>" title="Page "><?php echo $page ?></a></li>
              <?php endif; ?>
            <?php endforeach ?>
            <li><a href="<?php echo url_for('issues/list?page='.$pager->getNextPage()) ?>" title="Next page">&gt;</a></li>
            <li><a href="<?php echo url_for('issues/list?page='.$pager->getLastPage()) ?>" title="Last page">&raquo;</a></li>
          </ul>
        </div>
			</td>
		</tr>
	</tfoot>
	<?php endif ?>
</table>

	<a href="<?php echo url_for('issues/new') ?>">New</a>
