<?php
    $activePage = $activePage ?? '';
    $basePath = $basePath ?? '';
?>

<header>
        <div class="header-container">
            <h1>Portfolio Project</h1>
            <nav>
            <a href="<?php echo $basePath; ?>index.php" 
               class="nav-btn<?php echo $activePage === 'home' ? ' active' : ''; ?>">Home</a>
            <a href="<?php echo $basePath; ?>pages/about.php" 
               class="nav-btn<?php echo $activePage === 'about' ? ' active' : ''; ?>">About</a>
            <a href="<?php echo $basePath; ?>pages/api-tester.php" 
               class="nav-btn<?php echo $activePage === 'apis' ? ' active' : ''; ?>">APIs</a>
        </nav>
        </div>
    </header>