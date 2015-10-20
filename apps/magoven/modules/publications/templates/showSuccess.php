<?php
/** @var Publication $Publication */
?>

<h3>Publication</h3>

<?php include_partial('flashes') ?>

<table class="table">

  <tr>
    <td style="background-color: #f5f5f5">
      <div class="container">
        <h3 style="float:left"><a class="text-success" href="<?php echo url_for('publications/show?id='.$Publication->getId()) ?>"> <?php echo $Publication->getName(); ?> </a></h3>
        <span style="float:right; margin-top: 20px">Installation count: <?php echo $Publication->getApnsDeviceCount(); ?></span>
      </div>

      <div class="pub-details well">
        <div>
          <label>Application ID:</label> <?php echo $Publication->getApplicationId(); ?>
        </div>
        <div>
          <label>Last Updated:</label> <?php echo $Publication->getUpdatedAt(); ?>
        </div>
        <div>
          <label>Development Mode:</label> <?php echo $Publication->getDevelopmentMode() ? 'Yes' : 'No'; ?>
        </div>
        <?php if ($Publication->getSandboxPemExpiryWarning()) { ?>
          <div>
            <label>Warning:</label> <span class="warning">Sandbox .pem certificate is set to expire in less than 60 days.</span>
          </div>
        <?php } ?>
        <?php if ($Publication->getProductionPemExpiryWarning()) { ?>
          <div>
            <label>Warning:</label> <span class="warning">Production .pem certificate is set to expire in less than 60 days.</span>
          </div>
        <?php } ?>

        <?php if ($Publication->canNotify()): ?>Want to send a notification for this publication? You can!<?php endif; ?>
        <?php
        if ($Publication->canNotify()):
          ?>
          <div class="btn-toolbar">
            <a class="btn btn-mini btn-success" onclick="bootbox.confirm('Are you sure you want to send a notification for this publication? <br/> This will be sent to <?php echo $Publication->getApnsDeviceCount(); ?> users!', function(result){ if(result){location.href='<?php echo url_for('publications/sendApns?id='.$Publication->getId()) ?>'} });" href="javascript:;">Send Publication Notification</a>
            <a class="btn btn-mini" onclick="bootbox.confirm('Are you sure you want to ignore notifications for all issues in this publication?', function(result){ if(result){location.href='<?php echo url_for('publications/ignoreApns?id='.$Publication->getId()) ?>'} });" href="javascript:;">Ignore All Notifications</a>
          </div>
        <?php
        endif;
        ?>

        <div class="controls">

          <a class="btn btn-info" style="margin-right: 10px" href="<?php echo url_for('publications/edit?id='.$Publication->getId()) ?>"> <i class="icon-edit icon-white"></i> Edit Publication</a>

        </div>

      </div>

      <div class="container">
        <h4 style="float:left">Issues</h4>
        <a style="margin-top: 7px; float:right" href="<?php echo url_for('issues/new'); ?>?pub=<?php echo $Publication->getId(); ?>" class="btn btn-small btn-info"><i class="icon-plus icon-white"></i> Create New Issue</a>
      </div>
      <?php
        if ($issues = $Publication->getIssuesByDate()):
      ?>
        <table class="table table-hover">
        <?php
          /** @var Issue $issue */
          foreach ($issues as $issue):
        ?>
            <tr>
              <td class="span1"> <img src="<?php echo $issue->getCoverImage(); ?>" style="width: 75px" /> </td>
              <td style="vertical-align: middle"><a href="<?php echo url_for('issues/show?id='.$issue->getId()) ?>"> <?php echo $issue->getTitle(); ?> </a></td>
              <td style="vertical-align: middle"><?php print $issue->getFree() ? 'Free' : 'Paid'; ?></td>
              <td style="vertical-align: middle"><?php print $issue->getPublished() ? 'Published' : ''; ?></td>
              <td style="vertical-align: middle; width: 200px">
                <?php if ($issue->canNotify()): ?>
                  <a class="btn btn-mini btn-success" onclick="bootbox.confirm('Are you sure you want to send a notification for this issue? <br/> This will be sent to <?php echo $Publication->getApnsDeviceCount(); ?> users!', function(result){ if(result){location.href='<?php echo url_for('issues/sendApns?id='.$issue->getId()) ?>'} });" href="javascript:;">Send Notification</a>
                  <a class="btn btn-mini" onclick="bootbox.confirm('Are you sure you want to ignore notifications for this issue?', function(result){ if(result){location.href='<?php echo 'issues/ignoreApns?id='.$issue->getId(); ?>'} });" href="javascript:;">Ignore Notification</a>
                <?php elseif ($issue->getNotified()): ?>
                  Notification sent.
                <?php endif; ?>
              </td>
              <td style="vertical-align: middle">
                <td style="vertical-align: middle; width: 120px" nowrap>
                  <a class="btn btn-mini btn-info" href="<?php echo url_for('issues/edit?id='.$issue->getId()) ?>"> <i class="icon-edit icon-white"></i> Edit</a> <?php echo link_to('<i class="icon-remove icon-white"></i> Delete', 'issues/delete?id='.$issue->getId(), array('class' => 'btn btn-mini btn-danger', 'method' => 'delete', 'confirm' => 'Are you sure you want to delete this issue?')) ?>
                </td>
              </td>
            </tr>
        <?php
          endforeach;
        ?>
        </table>
      <?php
        else:
      ?>
        <p>No issues! Try <a href="<?php echo url_for('issues/new'); ?>">adding</a> one to get started.</p>
      <?php
        endif;
      ?>
    </td>
  </tr>


</table>
