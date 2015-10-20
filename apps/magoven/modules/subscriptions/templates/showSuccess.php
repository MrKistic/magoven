
<h3>Subscriptions</h3>

<div class="container">

  <form class="form-horizontal">
    <fieldset class="well">

      <div class="control-group ">
        <label class="control-label">Id</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Subscription->getId() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Created at</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Subscription->getCreatedAt() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Updated at</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Subscription->getUpdatedAt() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Application</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Subscription->getApplicationId() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">User</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Subscription->getUserId() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Effective date</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Subscription->getEffectiveDate() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Expiration date</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Subscription->getExpirationDate() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Last validated</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Subscription->getLastValidated() ?>" />
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
