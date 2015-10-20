  <h2>Subscription List</h2>

  <p class="lead">
    Your description of this page goes here.
  </p>

<?php include_partial('flashes') ?>

  <div class="btn-toolbar text-right">

  <a href="<?php echo url_for('subscription/new'); ?>" class="btn btn-small btn-info"><i class="icon-plus icon-white"></i> Add New Subscription</a>


  </div>

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
        <th>Effective date</th>
        <th>Expiration date</th>
        <th>Last validated</th>
        <th>&nbsp;</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($pager->getResults() as $Subscription): ?>
      <tr>
        <td><?php echo $Subscription->getId() ?></td>
        <td><?php echo $Subscription->getCreatedAt() ?></td>
        <td><?php echo $Subscription->getUpdatedAt() ?></td>
        <td><?php echo $Subscription->getApplicationId() ?></td>
        <td><?php echo $Subscription->getUserId() ?></td>
        <td><?php echo $Subscription->getEffectiveDate() ?></td>
        <td><?php echo $Subscription->getExpirationDate() ?></td>
        <td><?php echo $Subscription->getLastValidated() ?></td>
        <td nowrap><a class="btn btn-mini" href="<?php echo url_for('subscription/show?id='.$Subscription->getId()) ?>"> <i class="icon-eye-open"></i> View</a> <a class="btn btn-mini" href="<?php echo url_for('subscription/edit?id='.$Subscription->getId()) ?>"> <i class="icon-edit"></i> Edit</a> <?php echo link_to('<i class="icon-remove"></i> Delete', 'subscription/delete?id='.$Subscription->getId(), array('class' => 'btn btn-mini', 'method' => 'delete', 'confirm' => 'Are you sure?')) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>

    <?php if ($pager->haveToPaginate()): ?>
    <tfoot>
      <tr>
        <td colspan="8">
          <div class="pagination pagination-centered">
            <ul>
              <li><a href="<?php echo url_for('subscription/index?page='.$pager->getFirstPage()) ?>" title="First page">&laquo;</a></li>
              <li><a href="<?php echo url_for('subscription/index?page='.$pager->getPreviousPage()) ?>" title="Previous page">&lt;</a></li>
              <?php $links = $pager->getLinks(); foreach ($links as $page): ?>
                <?php if($page == $pager->getPage()): ?>
                  <li class="active"><a href="javascript:;"><?php echo $page ?></a></li>
                <?php else: ?>
                  <li><a href="<?php echo url_for('subscription/index?page='.$page) ?>" title="Page "><?php echo $page ?></a></li>
                <?php endif; ?>
              <?php endforeach ?>
              <li><a href="<?php echo url_for('subscription/index?page='.$pager->getNextPage()) ?>" title="Next page">&gt;</a></li>
              <li><a href="<?php echo url_for('subscription/index?page='.$pager->getLastPage()) ?>" title="Last page">&raquo;</a></li>
            </ul>
          </div>
        </td>
      </tr>
    </tfoot>
    <?php endif ?>

  </table>

<?php endif; ?>
