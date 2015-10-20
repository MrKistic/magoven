
<h3>Purchases</h3>

<div class="container">

  <form class="form-horizontal">
    <fieldset class="well">

      <div class="control-group ">
        <label class="control-label">Id</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Purchase->getId() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Created at</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Purchase->getCreatedAt() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Updated at</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Purchase->getUpdatedAt() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Application</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Purchase->getApplicationId() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">User</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Purchase->getUserId() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Product</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Purchase->getProductId() ?>" />
        </div>
      </div>

      <div class="control-group">
        <div class="controls">

          <a class="btn" href="javascript:history.go(-1);">Back</a>

        </div>
      </div>

    </fieldset>
  </form>

</div>
