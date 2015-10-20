<!DOCTYPE html lang="en">
<html>
<head>
  <?php include_http_metas() ?>
  <?php include_metas() ?>
  <?php include_title() ?>
  <link rel="shortcut icon" href="/favicon.ico" />
  <?php include_stylesheets() ?>
  <?php include_javascripts() ?>
</head>
<body>

<!-- Navbar -->
<div class="navbar navbar-inverse navbar-fixed-top">

  <div class="navbar-inner">

    <div class="container">

      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <a class="brand" href="/">MagOven</a>

      <div class="nav-collapse collapse">

        <ul class="nav">
          <li class="">
            <a href="/">Overview</a>
          </li>
          <li class="">
            <a href="/publications">Publications</a>
          </li>
          <li class="">
            <a href="/issues">Issues</a>
          </li>
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Other <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li class="">
                <a href="/purchases">Purchases</a>
              </li>
              <li class="">
                <a href="/receipts">Receipts</a>
              </li>
              <li class="">
                <a href="/subscriptions">Subscriptions</a>
              </li>
            </ul>
          </li>
        </ul>

        <ul class="nav pull-right">
          <?php if ($sf_user->isAuthenticated()): ?>
          <li class="">
            <a href="/logout">Logout</a>
          </li>
          <?php endif; ?>
        </ul>

      </div>

    </div>

  </div>

</div>


<div class="container">

  <?php echo $sf_content ?>

</div>

</body>
</html>
