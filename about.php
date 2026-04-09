<?php
$team = [
    [
        "name" => "Sean Blythe",
        "bio"  => "Fourth-year CS student at CSU. Focuses on hardware systems and backend logic. Enjoys tinkering with networking protocols in his home lab.",
        "pic"  => "images/blythese.png"
    ],
    [
        "name" => "Paul Gaudin",
        "bio"  => "Computer Science Senior at CSU. Enjoys coding, video games, music and spending time with his girlfriend dogs and other loved ones.",
        "pic"  => "images/paulgg.jpg"
    ],
    [
        "name" => "Jack Pustinger",
        "bio"  => "Junior computer science student at CSU, majoring in Software Engineering and Computational Mathematics. Enjoys video games, basketball, and snowboaring.",
        "pic"  => "images/jpusting.jpg"
    ],
    [
        "name" => "Mayur Bhat",
        "bio"  => "Third year computer science student with an interest in embedded and system programming. Loves to play acoustic guitar and multiplayer games.",
        "pic"  => "images/mayurb.jpg"
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
                <img src="images/teamlogo.png" alt="404 Logo" class="header-logo" width="400">
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
                    <img src="<?php echo $member['pic']; ?>" alt="<?php echo $member['name']; ?>" class="member-img" width="300">
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