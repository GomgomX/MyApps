<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
  <div class="container">
      <a class="navbar-brand" href="<?php echo URLROOT; ?>"><?php echo SITENAME; ?></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/pages/home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/pages/about">About</a>
          </li>
        </ul>

        <ul class="navbar-nav ml-auto">
          <?php
          if(!isset($_SESSION['user_id']))
          echo '<li class="nav-item">
            <a class="nav-link" href="'.URLROOT.'/users/register">Register</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="'.URLROOT.'/users/login">Login</a>
          </li>';
          else
            echo '<li class="nav-item">
              <a class="nav-link" href="#">Welcome '.$_SESSION['user_name'].'</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="'.URLROOT.'/users/logout">Logout</a>
            </li>';
          ?>
          <!-- We can also get the logged in user name through this method even if it's not static since class Users extends from Controller Controller::model('user')->getUserById($_SESSION['user_id'])->name " -->
        </ul>
      </div>
  </div>
</nav>