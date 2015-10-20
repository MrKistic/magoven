  <h2>Issues List</h2>

  <p class="lead">
    Your description of this page goes here.
  </p>

<?php include_partial('flashes') ?>

  <div class="btn-toolbar text-right">

  <a href="<?php echo url_for('issues/new'); ?>" class="btn btn-small btn-info"><i class="icon-plus icon-white"></i> Add New Issue</a>


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
        <th>Cover</th>
<!--        <th>Id</th>-->
<!--        <th>Created at</th>-->
        <th>Last Updated</th>
        <th>Publication</th>
        <th>Title</th>
<!--        <th>Name</th>-->
        <th>Pricing</th>
<!--        <th>Product</th>-->
<!--        <th>Info</th>-->
        <th>Published</th>
        <th>Published Date</th>
<!--        <th>Url</th>-->
<!--        <th>Itunes summary</th>-->
<!--        <th>Itunes coverart url</th>-->
<!--        <th>Itunes published</th>-->
<!--        <th>Itunes updated</th>-->
        <th>&nbsp;</th>
      </tr>
    </thead>

    <tbody>
      <?php
      /** @var Issue $Issue */
      foreach ($pager->getResults() as $Issue):
      ?>
      <tr>
        <td>
          <div class="fileupload-preview thumbnail" style="width: 75px;"><img src="<?php print $Issue->getCover(); ?>"></div>
        </td>
<!--        <td>--><?php //echo $Issue->getId() ?><!--</td>-->
<!--        <td>--><?php //echo $Issue->getCreatedAt() ?><!--</td>-->
        <td><?php echo $Issue->getUpdatedAt() ?></td>
        <td><?php echo $Issue->getPublication()->getName() ?></td>
        <td><?php echo $Issue->getTitle() ?></td>
<!--        <td>--><?php //echo $Issue->getName() ?><!--</td>-->
        <td><?php echo $Issue->getFree() ? 'Free' : 'Paid' ?></td>
<!--        <td>--><?php //echo $Issue->getProductId() ?><!--</td>-->
<!--        <td>--><?php //echo $Issue->getInfo() ?><!--</td>-->
        <td><?php echo $Issue->getPublished() ? 'Yes' : '' ?></td>
        <td><?php echo $Issue->getDate() ?></td>
<!--        <td>--><?php //echo $Issue->getUrl() ?><!--</td>-->
<!--        <td>--><?php //echo $Issue->getItunesSummary() ?><!--</td>-->
<!--        <td>--><?php //echo $Issue->getItunesCoverartUrl() ?><!--</td>-->
<!--        <td>--><?php //echo $Issue->getItunesPublished() ?><!--</td>-->
<!--        <td>--><?php //echo $Issue->getItunesUpdated() ?><!--</td>-->
        <td nowrap style="width: 180px">
          <a class="btn btn-mini" href="<?php echo url_for('issues/show?id='.$Issue->getId()) ?>"> <i class="icon-eye-open"></i> View</a> <a class="btn btn-mini" href="<?php echo url_for('issues/edit?id='.$Issue->getId()) ?>"> <i class="icon-edit"></i> Edit</a> <?php echo link_to('<i class="icon-remove"></i> Delete', 'issues/delete?id='.$Issue->getId(), array('class' => 'btn btn-mini', 'method' => 'delete', 'confirm' => 'Are you sure?')) ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>

    <?php if ($pager->haveToPaginate()): ?>
    <tfoot>
      <tr>
        <td colspan="17">
          <div class="pagination pagination-centered">
            <ul>
              <li><a href="<?php echo url_for('issues/index?page='.$pager->getFirstPage()) ?>" title="First page">&laquo;</a></li>
              <li><a href="<?php echo url_for('issues/index?page='.$pager->getPreviousPage()) ?>" title="Previous page">&lt;</a></li>
              <?php $links = $pager->getLinks(); foreach ($links as $page): ?>
                <?php if($page == $pager->getPage()): ?>
                  <li class="active"><a href="javascript:;"><?php echo $page ?></a></li>
                <?php else: ?>
                  <li><a href="<?php echo url_for('issues/index?page='.$page) ?>" title="Page "><?php echo $page ?></a></li>
                <?php endif; ?>
              <?php endforeach ?>
              <li><a href="<?php echo url_for('issues/index?page='.$pager->getNextPage()) ?>" title="Next page">&gt;</a></li>
              <li><a href="<?php echo url_for('issues/index?page='.$pager->getLastPage()) ?>" title="Last page">&raquo;</a></li>
            </ul>
          </div>
        </td>
      </tr>
    </tfoot>
    <?php endif ?>

  </table>

<?php endif; ?>
