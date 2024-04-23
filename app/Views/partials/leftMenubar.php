<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav mb-2" id="sidebar-nav">	
        <li class="nav-item">
        <a class="nav-link " href="<?= base_url() ?>">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
        </a>
        </li><!-- End Dashboard Nav -->
    </ul>
      <?php
        $arr['leftMenus'] = menu_data();
        $arr['menuHead'] = selected_menu();
        display_menu($arr,0);
      ?>

  </aside>
