      <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">

          </div>

          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li <?php echo (basename($_SERVER["SCRIPT_NAME"])=='main.php')?'class="active"':''; ?>>
                 <a href="main.php">Main</a>
              </li>
              <li <?php echo (basename($_SERVER["SCRIPT_NAME"])=='procedures.php')?'class="active"':''; ?>>
                 <a href="procedures.php">Current Procedures</a>
              </li>
              <li <?php echo (basename($_SERVER["SCRIPT_NAME"])=='modalities.php')?'class="active"':''; ?>>
                 <a href="modalities.php">Modalities</a>
              </li>
              <li <?php echo (basename($_SERVER["SCRIPT_NAME"])=='addprocedures.php')?'class="active"':''; ?>>
                 <a href="addprocedures.php">Add Procedures</a>
              </li>
              <li <?php echo (basename($_SERVER["SCRIPT_NAME"])=='useractivity.php')?'class="active"':''; ?>>
                 <a href="useractivity.php">My Activity</a>
              </li>
              <?php
              # Adin only
              if($_SESSION['admin']){ ?>
                 <li <?php echo (basename($_SERVER["SCRIPT_NAME"])=='adminsarea.php')?'class="active"':''; ?>>
                    <a href="adminsarea.php">Admin Area</a>
                 </li>
              <?php } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li>
                 <a class="list-group-item" href="logout.php">Logout: <?php echo "{$_SESSION['firstname']} {$_SESSION['lastname']}"; ?></a>
              </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
