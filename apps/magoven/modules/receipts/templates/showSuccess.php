
<h3>Receipts</h3>

<div class="container">

  <form class="form-horizontal">
    <fieldset class="well">

      <div class="control-group ">
        <label class="control-label">Id</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getId() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Created at</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getCreatedAt() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Updated at</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getUpdatedAt() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Application</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getApplicationId() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Quantity</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getQuantity() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Product</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getProductId() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Receipt type</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getReceiptType() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Transaction</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getTransactionId() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">User</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getUserId() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Purchase date</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getPurchaseDate() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Original transaction</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getOriginalTransactionId() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Original purchase date</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getOriginalPurchaseDate() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">App item</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getAppItemId() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Version external identifier</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getVersionExternalIdentifier() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Bid</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getBid() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Bvrs</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getBvrs() ?>" />
        </div>
      </div>
      <div class="control-group ">
        <label class="control-label">Base64 receipt</label>
        <div class="controls">
          <input type="text" readonly="readonly" value="<?php echo $Receipt->getBase64Receipt() ?>" />
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
