<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<div class="container">

<?php include_partial('flashes') ?>


  <form class="form-horizontal" action="/<?php echo $form_action; ?>" method="post" enctype="multipart/form-data">
	  <fieldset class="well">

      <?php if (!$form->getObject()->isNew()): ?>
		  <input type="hidden" name="sf_method" value="put" />
      <?php endif; ?>

      <?php echo $form->renderHiddenFields() ?>
      <?php echo $form->renderGlobalErrors() ?>
      <?php echo $form['publication_id']->renderRow() ?>
      <?php echo $form['name']->renderRow() ?>
      <?php echo $form['free']->renderRow() ?>
      <?php echo $form['product_id']->renderRow() ?>
      <?php echo $form['title']->renderRow() ?>
      <?php echo $form['info']->renderRow() ?>
      <?php echo $form['date']->renderRow() ?>
      <?php echo $form['published']->renderRow() ?>

      <div id="content-type">
      <?php echo $form['upload_type']->renderRow() ?>
      </div>

      <!-- Content :: Text intros -->
      <div class="control-group content-option content-type-u">
        <div class="controls">
          <p class="help-block text-info">Use the URLs of already hosted files.</p>
        </div>
      </div>
      <div class="control-group content-option content-type-h">
        <div class="help-block controls">
          <p>Upload a compiled .hpub (or .zip) file and a cover image.</p>
        </div>
      </div>

      <!-- Content :: Upload Cover -->
      <span class="content-option content-type-h content-type-z">
      <?php echo $form['uploaded_cover']->renderRow() ?>
      </span>
      <span class="content-option content-type-h">
      <?php echo $form['uploaded_hpub']->renderRow() ?>
      </span>

      <!-- Content :: URLs -->
      <span class="content-option content-type-u">
      <?php echo $form['cover']->renderRow() ?>
      <?php echo $form['url']->renderRow() ?>
      </span>

      <?php echo $form['allow_notification']->renderRow() ?>

      <?php echo $form['itunes_include']->renderRow() ?>

      <?php echo $form['itunes_summary']->renderRow() ?>
      <?php // echo $form['itunes_coverart_url']->renderRow() ?>
      <?php echo $form['itunes_published']->renderRow() ?>
      <?php // echo $form['itunes_updated']->renderRow() ?>

      <div class="control-group">
        <div class="controls">

          <a class="btn"href="<?php echo $referer; ?>">Cancel</a>
	  		  <button class="btn btn-info" type="submit">Save</button>

        </div>
      </div>

	  </fieldset>
  </form>

</div>

<script type="text/javascript">

  $(document).ready(function() {

    $('div.controls').has('input[type="checkbox"]').find('div.hint-small').addClass('checkbox');

    if ($('#issue_free').val() == '1') {
      $($('#issue_product_id').parents('div.control-group')[0]).hide();
    }
    $('#issue_free').change(function(e) {
      if ($('#issue_free').val() == '1') {
        $($('#issue_product_id').parents('div.control-group')[0]).slideUp();
      } else {
        $($('#issue_product_id').parents('div.control-group')[0]).slideDown();
      }
    });
    $('#issue_free').change();

    $('#issue_uploaded_cover').fileupload();

    var pub = '<?php echo isset($pubId) ? $pubId : ''; ?>';
    if (pub) {
      $('#issue_publication_id').val(pub);
    }

    $('#content-type .btn').click(function(evt) {
      var val = $("input[name='issue[upload_type]']:checked").val();
      $('.content-option').hide();
      $('.content-type-' + val).show();
      $('#content-type .btn').removeClass('active');
      $(this).addClass('active');
    });

    $('#content-type .btn input[type=radio]:checked').parent('.btn').click();

  });

</script>
