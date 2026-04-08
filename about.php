<?php
$team = [
    [
        "name" => "Sean Blythe",
        "bio"  => "Fourth-year CS student at CSU. Focuses on hardware systems and backend logic. Enjoys tinkering with networking protocols in his home lab.",
        "pic"  => "images/blythese.png"
    ],
    [
        "name" => "Member 2",
        "bio"  => "Add a real bio here to secure the 15 points for this section.",
        "pic"  => "images/m2.png"
    ],
    [
        "name" => "Member 3",
        "bio"  => "Add a real bio here. Placeholders like 'Hello' will lose points.",
        "pic"  => "images/m3.png"
    ],
    [
        "name" => "Member 4",
        "bio"  => "Add a real bio here. Ensure it's unique to the student.",
        "pic"  => "images/m4.png"
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us | 404: Team Not Found</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="top-bar">
            <div class="logo-area">
                <img src="images/teamlogo.png" alt="404 Logo" class="header-logo">
                <span class="logo-main">404:</span><span class="logo-sub"> Team Not Found</span>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li class="active"><a href="about.php">About</a></li>
                <li><a href="color.php">Color Coordinator</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <section class="about-hero">
            <h1>The "Found" Team</h1>
            <p>Meet the engineers behind the 404 Project.</p>
        </section>

        <div class="team-grid">
            <?php foreach ($team as $member): ?>
                <div class="member-card">
                    <img src="<?php echo $member['pic']; ?>" alt="<?php echo $member['name']; ?>" class="member-img">
                    <div class="member-info">
                        <h3><?php echo $member['name']; ?></h3>
                        <p><?php echo $member['bio']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 404: Team Not Found — CSU CT312 Project</p>
    </footer>
</body>
</html>