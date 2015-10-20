  <h2>Receipts List</h2>

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
        <th>Last Updated</th>
        <th>Publication</th>
        <th>Quantity</th>
        <th>Product</th>
        <th>Receipt type</th>
<!--        <th>Transaction</th>-->
<!--        <th>User</th>-->
        <th>Purchase date</th>
<!--        <th>Original transaction</th>-->
<!--        <th>Original purchase date</th>-->
<!--        <th>App item</th>-->
<!--        <th>Version external identifier</th>-->
<!--        <th>Bid</th>-->
<!--        <th>Bvrs</th>-->
<!--        <th>Base64 receipt</th>-->
        <th>&nbsp;</th>
      </tr>
    </thead>

    <tbody>
      <?php
        /** @var Receipt $Receipt */
        foreach ($pager->getResults() as $Receipt):
      ?>
      <tr>
        <td><?php echo $Receipt->getUpdatedAt() ?></td>
        <td><?php echo $Receipt->getPublicationName() ?></td>
        <td><?php echo $Receipt->getQuantity() ?></td>
        <td><?php echo $Receipt->getProductId() ?></td>
        <td><?php echo $Receipt->getReceiptType() ?></td>
<!--        <td>--><?php //echo $Receipt->getTransactionId() ?><!--</td>-->
<!--        <td>--><?php //echo $Receipt->getUserId() ?><!--</td>-->
        <td><?php echo $Receipt->getPurchaseDate() ?></td>
<!--        <td>--><?php //echo $Receipt->getOriginalTransactionId() ?><!--</td>-->
<!--        <td>--><?php //echo $Receipt->getOriginalPurchaseDate() ?><!--</td>-->
<!--        <td>--><?php //echo $Receipt->getAppItemId() ?><!--</td>-->
<!--        <td>--><?php //echo $Receipt->getVersionExternalIdentifier() ?><!--</td>-->
<!--        <td>--><?php //echo $Receipt->getBid() ?><!--</td>-->
<!--        <td>--><?php //echo $Receipt->getBvrs() ?><!--</td>-->
<!--        <td>--><?php //echo $Receipt->getBase64Receipt() ?><!--</td>-->
        <td nowrap> <a class="btn btn-mini" href="<?php echo url_for('receipts/show?id='.$Receipt->getId()) ?>"> <i class="icon-eye-open"></i> View</a> </td>
      </tr>
      <?php endforeach; ?>
    </tbody>

    <?php if ($pager->haveToPaginate()): ?>
    <tfoot>
      <tr>
        <td colspan="17">
          <div class="pagination pagination-centered">
            <ul>
              <li><a href="<?php echo url_for('receipts/index?page='.$pager->getFirstPage()) ?>" title="First page">&laquo;</a></li>
              <li><a href="<?php echo url_for('receipts/index?page='.$pager->getPreviousPage()) ?>" title="Previous page">&lt;</a></li>
              <?php $links = $pager->getLinks(); foreach ($links as $page): ?>
                <?php if($page == $pager->getPage()): ?>
                  <li class="active"><a href="javascript:;"><?php echo $page ?></a></li>
                <?php else: ?>
                  <li><a href="<?php echo url_for('receipts/index?page='.$page) ?>" title="Page "><?php echo $page ?></a></li>
                <?php endif; ?>
              <?php endforeach ?>
              <li><a href="<?php echo url_for('receipts/index?page='.$pager->getNextPage()) ?>" title="Next page">&gt;</a></li>
              <li><a href="<?php echo url_for('receipts/index?page='.$pager->getLastPage()) ?>" title="Last page">&raquo;</a></li>
            </ul>
          </div>
        </td>
      </tr>
    </tfoot>
    <?php endif ?>

  </table>

<?php endif; ?>
