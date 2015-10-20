<style type="text/css">
  label[for=signin_remember] {
    float: left;
    margin-right: 15px;
  }
</style>
<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post" class="form-inline">

  <table>
    <?php echo $form ?>
  </table>

  <input type="submit" value="Log In" style="margin-top: 10px;" />
</form>
