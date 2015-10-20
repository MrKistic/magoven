  <h2>Publications List</h2>

  <p class="lead">
    Your description of this page goes here.
  </p>

<?php include_partial('flashes') ?>

  <div class="btn-toolbar text-right">

    <a href="<?php echo url_for('publications/new'); ?>" class="btn btn-small btn-info"><i class="icon-plus icon-white"></i> Add New Publication</a>

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
<!--        <th>Id</th>-->
<!--        <th>Created at</th>-->
        <th>Last Updated</th>
<!--        <th>Application</th>-->
        <th>Name</th>
        <th>Mode</th>
        <th>Security</th>
<!--        <th>Itunes revalidation duration</th>-->
<!--        <th>Itunes production level</th>-->
<!--        <th>Itunes shared secret</th>-->
<!--        <th>Itunes updated</th>-->
        <th>&nbsp;</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($pager->getResults() as $Publication): ?>
      <tr>
<!--        <td>--><?php //echo $Publication->getId() ?><!--</td>-->
<!--        <td>--><?php //echo $Publication->getCreatedAt() ?><!--</td>-->
        <td><?php echo $Publication->getUpdatedAt() ?></td>
<!--        <td>--><?php //echo $Publication->getApplicationId() ?><!--</td>-->
        <td><?php echo $Publication->getName() ?></td>
        <td><?php echo $Publication->getDevelopmentMode() ? 'Dev' : 'Live' ?></td>
        <td><?php echo $Publication->getIssueDownloadSecurity() ? 'Yes' : 'No' ?></td>
<!--        <td>--><?php //echo $Publication->getItunesRevalidationDuration() ?><!--</td>-->
<!--        <td>--><?php //echo $Publication->getItunesProductionLevel() ?><!--</td>-->
<!--        <td>--><?php //echo $Publication->getItunesSharedSecret() ?><!--</td>-->
<!--        <td>--><?php //echo $Publication->getItunesUpdated() ?><!--</td>-->
        <td nowrap style="width: 180px">
          <a class="btn btn-mini" href="<?php echo url_for('publications/show?id='.$Publication->getId()) ?>"> <i class="icon-eye-open"></i> View</a> <a class="btn btn-mini" href="<?php echo url_for('publications/edit?id='.$Publication->getId()) ?>"> <i class="icon-edit"></i> Edit</a> <?php echo link_to('<i class="icon-remove"></i> Delete', 'publications/delete?id='.$Publication->getId(), array('class' => 'btn btn-mini', 'method' => 'delete', 'confirm' => 'Are you sure?')) ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>

    <?php if ($pager->haveToPaginate()): ?>
    <tfoot>
      <tr>
        <td colspan="11">
          <div class="pagination pagination-centered">
            <ul>
              <li><a href="<?php echo url_for('publications/index?page='.$pager->getFirstPage()) ?>" title="First page">&laquo;</a></li>
              <li><a href="<?php echo url_for('publications/index?page='.$pager->getPreviousPage()) ?>" title="Previous page">&lt;</a></li>
              <?php $links = $pager->getLinks(); foreach ($links as $page): ?>
                <?php if($page == $pager->getPage()): ?>
                  <li class="active"><a href="javascript:;"><?php echo $page ?></a></li>
                <?php else: ?>
                  <li><a href="<?php echo url_for('publications/index?page='.$page) ?>" title="Page "><?php echo $page ?></a></li>
                <?php endif; ?>
              <?php endforeach ?>
              <li><a href="<?php echo url_for('publications/index?page='.$pager->getNextPage()) ?>" title="Next page">&gt;</a></li>
              <li><a href="<?php echo url_for('publications/index?page='.$pager->getLastPage()) ?>" title="Last page">&raquo;</a></li>
            </ul>
          </div>
        </td>
      </tr>
    </tfoot>
    <?php endif ?>

  </table>

<?php endif; ?>
