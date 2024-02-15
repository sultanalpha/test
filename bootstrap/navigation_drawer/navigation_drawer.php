<script src="/test/libraries/jquery_3.7.1.js"></script>
<script src="/test/check_langs.js"></script>
<script src="/test/core/get_user_info.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="/test/bootstrap/navigation_drawer/navigaion_drawer.css" />
<script src="/test/bootstrap/navigation_drawer/navigation_drawer.js"></script>
<input type="hidden" value="<?php echo $_SESSION['csrf_token'] ?? null; ?>" id="csrf-token">
<div class="navigation-drawer" id="navigation-drawer">
  <div class="navigation-menu">
    <div class="user-details">
      <div class="user-image">
        <a href="/test/user/portal/" id="user-icon-btn" style="color: white; text-decoration: none">
          <img src="/test/icons/user-96.png" alt="" height="96" width="96" style="margin-left: 10px; color: white" />
        </a>
      </div>
      <div class="user-info" id="user-info" style="color: white">
        <a href="/test/user/dashboard/" style="color: white; text-decoration: none">
          <div>
            <p id="username_txt" style="display: inline"></p>
            <p id="username" style="display: inline"></p>
          </div>
          <div>
            <p id="email_txt" style="display: inline"></p>
            <p id="email" style="display: inline"></p>
          </div>
          <div>
            <p id="created_txt" style="display: inline"></p>
            <p id="created" style="display: inline"></p>
          </div>
        </a>
      </div>
      <a href="/test/user/portal/" style="text-decoration: none; color: white">
        <div class="not-logged" id="not-logged" style="margin-left: 10px">
          <h3 id="notlogged_txt"></h3>
        </div>
      </a>
    </div>
    <div class="navigation-items">
      <a href="/test/">
        <div class="item">
          <p id="home_txt"></p>
        </div>
      </a>
      <a href="/test/try-a-test">
        <div class="item">
          <p id="try-a-test_txt"></p>
        </div>
      </a>
      <a href="/test/about-us/">
        <div class="item">
          <p id="about-us_txt"></p>
        </div>
      </a>
      <?php
      if ($_SESSION['isLoggedin'] ?? false) {
      ?>
        <a href="/test/user/my-account/">
          <div class="item">
            <p id="my-account_txt"></p>
          </div>
        </a>
      <?php
      }
      ?>
      <!-- <a href="user/portal/" id="login_button">
            <div class="item">
              <p id="login_txt"></p>
            </div>
          </a> -->
    </div>
    <p id="copyright-txt">Copyrights &copy; reversed to SultanKingGD</p>
  </div>
  <div class="navigation-empty" id="navigation-empty"></div>
</div>