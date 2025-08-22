<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Kindergarten Website</title>
    <link rel="stylesheet" href="css/dropdown.css">
    <link rel="stylesheet" href="/edufun/style.css">
    
</head>
<body>
    <!-- header section starts -->
<header class="header">
<a href="#" class="logo"> <i class="fas fa-school"></i><marquee>"EDU-FUN - An E-Learning Platform For KG Students"</marquee></a>
   
    <nav class="navbar">
        <a href="#home">home</a>
        <a href="#about">about</a>
        <a href="#education">subjects</a>
        <a href="#activities">activities</a>
        <a href="#gallery">gallery</a>
        <a href="#contact">contact</a>

        <!-- Dropdown for Register -->
        <div class="dropdown">
            <a href="register.php" class="dropbtn">register</a>
            <!-- <div class="dropdown-content">
                <a href="register_student.php">Student</a>
                <a href="register_parent.php">Parent</a>
                <a href="register_admin.php">Admin</a>
            </div> -->
        </div>
    </nav>

</header>


    <!-- header section ends -->

    <!-- home section starts -->

    <section class="home" id="home">

        <div class="content">
            <h3><pre>"EDU-FUN" </pre><span>An E-Learning Platform For Kindergarten Student</span></h3>
            <p>to enhance learning for preprimary students,typically aged 3 to 6 years </p>
            <a href="login.php" class="btn"><b>Login</b></a>
        </div>

        <div class="image">
            <img src="images/home.png" alt="">
        </div>

        <div class="custom-shape-divider-bottom-1684324473">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
            </svg>
        </div>

    </section>

    <!-- home section ends -->

    <!-- about us section starts -->

    <section class="about" id="about">

        <h1 class="heading"> <span>about</span> us</h1>

        <div class="row">

            <div class="image">
                <img src="images/about us.png" alt="">
            </div>

            <div class="content">
                <h3>With "EDU-FUN", you can learn at your own peace, anytime, anywhere</h3>
                <p>In the evolving landscape of early childhood education, technology offers innovative solutions to enhance learning for preprimary students, typically aged 3 to 6 years. Educational applications that incorporate quizzes, videos, games, and performance tracking are designed to create a comprehensive and engaging learning experience.</p>
                
                <a href="#" class="btn">read more</a>
            </div>

        </div>

    </section>

    <!-- about us section ends -->

    <!-- education section start -->

    <section class="education" id="education">

        <h1 class="heading">our <span> subjects</span></h1>

        <div class="box-container">

            <div class="box">
                <h3>english</h3>
                <p>The main aim is to help children recognize letters, understand simple words, and start forming sentences.</p>
                <img src="images/education1.png" alt="">
            </div>

            <div class="box">
                <h3>maths</h3>
                <p> By the end, they should be able to count to 10 or more, identify basic shapes, and start to understand addition and subtraction.</p>
                <img src="images/education2.png" alt="">
            </div>

            <div class="box">
                <h3>arts</h3>
                <p> The main aim is to help kids build fine motor skills and let their imagination grow by creating art that makes them happy.</p>
                <img src="images/education3.png" alt="">
            </div>

        </div>

    </section>

    <!-- education section ends -->
    <!-- activities section starts -->

    <section class="activities" id="activities">

        <h1 class="heading">our <span>activities</span></h1>

        <div class="box-container">

            <div class="box">
                <img src="images/activities3.png" alt="">
                <h3>learning content</h3>
            </div>

            <div class="box">
                <img src="images/activities5.png" alt="">
                <h3>games</h3>
            </div>

            <div class="box">
                <img src="images/activities1.png" alt="">
                <h3>quizzes</h3>
            </div>

            <div class="box">
                <img src="images/activities10.png" alt="">
                <h3>learning videos</h3>
            </div>

            <div class="box">
                <img src="images/activities9.png" alt="">
                <h3>stories</h3>
            </div>

            <div class="box">
                <img src="images/activities7.png" alt="">
                <h3> Drawing and Coloring</h3>
            </div>
        </div>

    </section>

    <!-- activities section ends -->

    <!-- gallery section starts -->

    <section class="gallery" id="gallery">

        <h1 class="heading">our <span>gallery</span></h1>

        <div class="gallery-container">

            <a href="images/gallery-1.jpg" class="box">
                <img src="images/gallery-1.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

            <a href="images/gallery-2.jpg" class="box">
                <img src="images/gallery-2.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

            <a href="images/gallery-3.jpg" class="box">
                <img src="images/gallery-3.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

            <a href="images/gallery-4.jpg" class="box">
                <img src="images/gallery-4.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

            <a href="images/gallery-5.jpg" class="box">
                <img src="images/gallery-5.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

            <a href="images/gallery-6.jpg" class="box">
                <img src="images/gallery-6.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

        </div>

    </section>

    <!-- gallery section ends -->

    <!-- contact section starts -->

    <section class="contact" id="contact">

        <h1 class="heading"> <span>contact</span> us</h1>

        <div class="icons-container">

            <div class="icons">
                <i class="fas fa-clock"></i>
                <h3>opening hours :</h3>
                <p>mon - thurs: 08:00 am to 12:30 pm</p>
                <p>friday: 09:00 am to 12:00 pm</p>
            </div>

            <div class="icons">
                <i class="fas fa-envelope"></i>
                <h3>email</h3>
                <p>edufuneducation@gmail.com</p>
                <p>edufuneducation27@gmail.com</p>
            </div>

            <div class="icons">
                <i class="fas fa-phone"></i>
                <h3>phone number</h3>
                <p>+123-456-7890</p>
                <p>+123-456-7788</p>
            </div>

        </div>

    </section>

    <script src="script.js"></script>

    <script>
        lightGallery(document.querySelector('.gallery .gallery-container'));
    </script>

</body>
</html>