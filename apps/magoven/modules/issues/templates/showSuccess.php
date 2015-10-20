<?php
/** @var Issue $Issue */
?>

<h3>Issue</h3>

<?php include_partial('flashes') ?>

<div class="container">

  <form class="form-horizontal">
    <fieldset class="well">

      <div class="container">
        <h3 class="text-success" style="float:left"> <img src="<?php echo $Issue->getCoverImage(); ?>" style="width: 100px" /> &nbsp; <?php echo $Issue->getTitle(); ?></h3>
      </div>

      <div class="pub-details well">
        <div>
          <label>Publication:</label> <?php echo $Issue->getPublication()->getName(); ?>
        </div>
        <div>
          <label>Info:</label> <?php echo $Issue->getInfo(); ?>
        </div>
        <div>
          <label>Date:</label> <?php echo $Issue->getDate(); ?>
        </div>
        <div>
          <label>Availability:</label> <?php echo $Issue->getPublished() ? 'Published' : 'Pending'; ?>
        </div>
        <div>
          <label>Pricing:</label> <?php echo $Issue->getFree() ? 'Free' : 'Paid'; ?>
        </div>
        <div>
          <label>Last Updated:</label> <?php echo $Issue->getUpdatedAt(); ?>
        </div>

        <div class="controls">

          <a class="btn btn-info" style="margin-right: 10px" href="<?php echo url_for('issues/edit?id='.$Issue->getId()) ?>"> <i class="icon-edit icon-white"></i> Edit Issue</a>

        </div>

      </div>

    </fieldset>
  </form>

</div>
