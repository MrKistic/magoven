  <h2>Devices List</h2>

  <p class="lead">
    Your description of this page goes here.
  </p>

<?php include_partial('flashes') ?>

  <div class="btn-toolbar text-right">

  <a href="<?php echo url_for('devices/new'); ?>" class="btn btn-small btn-info"><i class="icon-plus icon-white"></i> Add New ApnsDevice</a>


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
        <th>Pid</th>
        <th>Clientid</th>
        <th>Appname</th>
        <th>Appversion</th>
        <th>Deviceuid</th>
        <th>Devicetoken</th>
        <th>Devicename</th>
        <th>Devicemodel</th>
        <th>Deviceversion</th>
        <th>Pushbadge</th>
        <th>Pushalert</th>
        <th>Pushsound</th>
        <th>Development</th>
        <th>Status</th>
        <th>Created</th>
        <th>Modified</th>
        <th>&nbsp;</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($pager->getResults() as $ApnsDevice): ?>
      <tr>
        <td><?php echo $ApnsDevice->getPid() ?></td>
        <td><?php echo $ApnsDevice->getClientid() ?></td>
        <td><?php echo $ApnsDevice->getAppname() ?></td>
        <td><?php echo $ApnsDevice->getAppversion() ?></td>
        <td><?php echo $ApnsDevice->getDeviceuid() ?></td>
        <td><?php echo $ApnsDevice->getDevicetoken() ?></td>
        <td><?php echo $ApnsDevice->getDevicename() ?></td>
        <td><?php echo $ApnsDevice->getDevicemodel() ?></td>
        <td><?php echo $ApnsDevice->getDeviceversion() ?></td>
        <td><?php echo $ApnsDevice->getPushbadge() ?></td>
        <td><?php echo $ApnsDevice->getPushalert() ?></td>
        <td><?php echo $ApnsDevice->getPushsound() ?></td>
        <td><?php echo $ApnsDevice->getDevelopment() ?></td>
        <td><?php echo $ApnsDevice->getStatus() ?></td>
        <td><?php echo $ApnsDevice->getCreated() ?></td>
        <td><?php echo $ApnsDevice->getModified() ?></td>
        <td nowrap><a class="btn btn-mini" href="<?php echo url_for('devices/show?pid='.$ApnsDevice->getPid()) ?>"> <i class="icon-eye-open"></i> View</a> <a class="btn btn-mini" href="<?php echo url_for('devices/edit?pid='.$ApnsDevice->getPid()) ?>"> <i class="icon-edit"></i> Edit</a> <?php echo link_to('<i class="icon-remove"></i> Delete', 'devices/delete?pid='.$ApnsDevice->getPid(), array('class' => 'btn btn-mini', 'method' => 'delete', 'confirm' => 'Are you sure?')) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>

    <?php if ($pager->haveToPaginate()): ?>
    <tfoot>
      <tr>
        <td colspan="16">
          <div class="pagination pagination-centered">
            <ul>
              <li><a href="<?php echo url_for('devices/index?page='.$pager->getFirstPage()) ?>" title="First page">&laquo;</a></li>
              <li><a href="<?php echo url_for('devices/index?page='.$pager->getPreviousPage()) ?>" title="Previous page">&lt;</a></li>
              <?php $links = $pager->getLinks(); foreach ($links as $page): ?>
                  <?php if($page == $pager->getPage()): ?>
                    <li class="active"><a href="javascript:;"><?php echo $page ?></a></li>
                  <?php else: ?>
                    <li><a href="<?php echo url_for('devices/index?page='.$page) ?>" title="Page "><?php echo $page ?></a></li>
                  <?php endif; ?>
                </li>
              <?php endforeach ?>
              <li><a href="<?php echo url_for('devices/index?page='.$pager->getNextPage()) ?>" title="Next page">&gt;</a></li>
              <li><a href="<?php echo url_for('devices/index?page='.$pager->getLastPage()) ?>" title="Last page">&raquo;</a></li>
            </ul>
          </div>
        </td>
      </tr>
    </tfoot>
    <?php endif ?>

  </table>

<?php endif; ?>
