
<h3><?php echo sfInflector::humanize($this->getModuleName()) ?></h3>

<div class="container">

  <form class="form-horizontal">
    <fieldset class="well">

<?php foreach ($this->getTableMap()->getColumns() as $column): ?>
      <div class="control-group ">
        <label class="control-label"><?php echo sfInflector::humanize(sfInflector::underscore($column->getPhpName())) ?></label>
        <div class="controls">
          <input type="text" readonly="readonly" value="[?= $<?php echo $this->getSingularName() ?>->get<?php echo $column->getPhpName() ?>() ?]" />
        </div>
      </div>
<?php endforeach; ?>

      <div class="control-group">
        <div class="controls">

<?php if (isset($this->params['route_prefix']) && $this->params['route_prefix']): ?>
          <a class="btn" href="[?php echo url_for('<?php echo $this->getUrlForAction('index') ?>') ?]">Cancel</a>
          <a class="btn btn-info" href="[?php echo url_for('<?php echo $this->getUrlForAction('edit') ?>', $<?php echo $this->getSingularName() ?>) ?]">Edit</a>
<?php else: ?>
          <a class="btn"href="[?php echo url_for('<?php echo $this->getModuleName() ?>/index') ?]">Cancel</a>
          <a class="btn btn-info" href="[?php echo url_for('<?php echo $this->getModuleName() ?>/edit?<?php echo $this->getPrimaryKeyUrlParams() ?>) ?]">Edit</a>
<?php endif; ?>

        </div>
      </div>

    </fieldset>
  </form>

</div>
