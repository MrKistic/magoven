  <h2>Purchases List</h2>

  <p class="lead">
    Your description of this page goes here.
  </p>

<?php include_partial('flashes') ?>

<?php if (!$pager->getNbResults()): ?>

  <table class="table table-bordered table-hover list">
    <tr>
      <td><h3>No results</h3></td>
    </tr>
  </table>

<?php else: ?>

  <table class="table table-bordered table-hover list">

    <thead>
      <tr>
        <th>Id</th>
        <th>Created at</th>
        <th>Updated at</th>
        <th>Application</th>
        <th>User</th>
        <th>Product</th>
        <th>&nbsp;</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($pager->getResults() as $Purchase): ?>
      <tr>
        <td><?php echo $Purchase->getId() ?></td>
        <td><?php echo $Purchase->getCreatedAt() ?></td>
        <td><?php echo $Purchase->getUpdatedAt() ?></td>
        <td><?php echo $Purchase->getApplicationId() ?></td>
        <td><?php echo $Purchase->getUserId() ?></td>
        <td><?php echo $Purchase->getProductId() ?></td>
        <td nowrap> <a class="btn btn-mini" href="<?php echo url_for('purchases/show?id='.$Purchase->getId()) ?>"> <i class="icon-eye-open"></i> View</a> </td>
      </tr>
      <?php endforeach; ?>
    </tbody>

    <?php if ($pager->haveToPaginate()): ?>
    <tfoot>
      <tr>
        <td colspan="6">
          <div class="pagination pagination-centered">
            <ul>
              <li><a href="<?php echo url_for('purchases/index?page='.$pager->getFirstPage()) ?>" title="First page">&laquo;</a></li>
              <li><a href="<?php echo url_for('purchases/index?page='.$pager->getPreviousPage()) ?>" title="Previous page">&lt;</a></li>
              <?php $links = $pager->getLinks(); foreach ($links as $page): ?>
                <?php if($page == $pager->getPage()): ?>
                  <li class="active"><a href="javascript:;"><?php echo $page ?></a></li>
                <?php else: ?>
                  <li><a href="<?php echo url_for('purchases/index?page='.$page) ?>" title="Page "><?php echo $page ?></a></li>
                <?php endif; ?>
              <?php endforeach ?>
              <li><a href="<?php echo url_for('purchases/index?page='.$pager->getNextPage()) ?>" title="Next page">&gt;</a></li>
              <li><a href="<?php echo url_for('purchases/index?page='.$pager->getLastPage()) ?>" title="Last page">&raquo;</a></li>
            </ul>
          </div>
        </td>
      </tr>
    </tfoot>
    <?php endif ?>

  </table>

<?php endif; ?>
