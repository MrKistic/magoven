  <h2>MagOven Overview</h2>

  <p class="lead">
    Here's what's cooking in your MagOven today.
  </p>

  <?php include_partial('flashes') ?>

<?php if (ApnsMessagePeer::getMessageQueueCount()): ?>
  <div class="container">
    <h3 style="float: left">Notification Queue</h3>
  </div>
  <table class="table table-bordered list">
    <tr>
      <td>Current notification queue count: <?php echo ApnsMessagePeer::getMessageQueueCount(); ?></td>
    </tr>
  </table>
<?php endif; ?>

  <div class="container">
    <h3 style="float: left">Publications</h3>
    <a style="margin-top: 20px; float: right" href="<?php echo url_for('publications/new'); ?>" class="btn btn-small btn-info"><i class="icon-plus icon-white"></i> Create New Publication</a>
  </div>

  <div class="container">
<?php if (empty($pubs)): ?>

  <table class="table table-bordered list">
    <tr>
      <td><h3>None! Try <a href="<?php echo url_for('publications/new'); ?>">adding</a> one to get started.</h3></td>
    </tr>
  </table>

<?php else: ?>

  <table class="table">
<?php
  /** @var Publication $pub */
  foreach ($pubs as $pub):
?>
    <tr>
      <td style="background-color: #f5f5f5">
        <div class="container">
          <h3 style="float:left"><a class="text-success" href="<?php echo url_for('publications/show?id='.$pub->getId()) ?>"> <?php echo $pub->getName(); ?> </a></h3>
          <span style="float:right; margin-top: 20px">Installation count: <?php echo $pub->getApnsDeviceCount(); ?></span>
        </div>
        <?php if ($pub->canNotify()): ?>Want to send a notification for this publication? You can!<?php endif; ?>
  <?php
    if ($pub->canNotify()):
  ?>
        <div class="btn-toolbar">
          <a class="btn btn-mini btn-success" onclick="bootbox.confirm('Are you sure you want to send a notification for this publication? <br/> This will be sent to <?php echo $pub->getApnsDeviceCount(); ?> users!', function(result){ if(result){location.href='<?php echo url_for('publications/sendApns?id='.$pub->getId()) ?>'} });" href="javascript:;">Send Publication Notification</a>
          <a class="btn btn-mini" onclick="bootbox.confirm('Are you sure you want to ignore notifications for all issues in this publication?', function(result){ if(result){location.href='<?php echo url_for('publications/ignoreApns?id='.$pub->getId()) ?>'} });" href="javascript:;">Ignore All Notifications</a>
        </div>
  <?php
    endif;
  ?>
        <div class="container">
          <h4 style="float:left">Issues</h4>
          <a style="margin-top: 7px; float:right" href="<?php echo url_for('issues/new'); ?>?pub=<?php echo $pub->getId(); ?>" class="btn btn-small btn-info"><i class="icon-plus icon-white"></i> Create New Issue</a>
        </div>
  <?php
    if ($issues = $pub->getIssuesByDate()):
  ?>
        <table class="table table-hover ">
  <?php
      /** @var Issue $issue */
      foreach ($issues as $issue):
  ?>
          <tr>
            <td class="span1"> <img src="<?php echo $issue->getCoverImage(); ?>" style="width: 75px" /> </td>
            <td style="vertical-align: middle"><a href="<?php echo url_for('issues/show?id='.$issue->getId()) ?>"> <?php echo $issue->getTitle(); ?> </a></td>
            <td style="text-align: center; vertical-align: middle; width: 1; white-space: nowrap;">
              <?php if ($issue->canNotify()): ?>
                <a class="btn btn-mini btn-success" onclick="bootbox.confirm('Are you sure you want to send a notification for this issue? <br/> This will be sent to <?php echo $pub->getApnsDeviceCount(); ?> users!', function(result){ if(result){location.href='<?php echo url_for('issues/sendApns?id='.$issue->getId()) ?>'} });" href="javascript:;">Send Notification</a>
                <a class="btn btn-mini" onclick="bootbox.confirm('Are you sure you want to ignore notifications for this issue?', function(result){ if(result){location.href='<?php echo 'issues/ignoreApns?id='.$issue->getId(); ?>'} });" href="javascript:;">Ignore Notification</a>
              <?php elseif ($issue->getNotified()): ?>
                Notification sent.
              <?php endif; ?>
            </td>
            <td style="vertical-align: middle; width: 1; white-space: nowrap; padding-right: 20px"><?php print $issue->getPublished() ? '<i class="icon-download"></i> Published' : '<i class="icon-pencil"></i> Editing'; ?></td>
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
<?php
  endforeach;
?>
  </table>

  </div>

<?php endif; ?>
