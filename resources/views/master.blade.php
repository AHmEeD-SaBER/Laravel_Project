<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form</title>
</head>
<body>
    @section('header')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
        <header class="bg-black text-white w-100 py-3 px-5 d-flex justify-content-between
        align-items-center">
            <div class="logo flex-grow-1 ">
                <h1 class=" h3 m-0">@yield('title', 'Registration Form')</h1>
            </div>
            <nav class="">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
        </header>
    @show

    @yield('content')

    @section('name')
        <footer class="bg-body-tertiary text-center">
            <!-- Grid container -->
            <div class="container p-4 pb-0">
                <!-- Section: Social media -->
                <section class="mb-4">
                    <!-- Facebook -->
                    <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #3b5998;"
                        href="#!" role="button"><i class="fab fa-facebook-f"></i></a>

                    <!-- Twitter -->
                    <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #55acee;"
                        href="#!" role="button"><i class="fab fa-twitter"></i></a>

                    <!-- Google -->
                    <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #dd4b39;"
                        href="#!" role="button"><i class="fab fa-google"></i></a>

                    <!-- Instagram -->
                    <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #ac2bac;"
                        href="#!" role="button"><i class="fab fa-instagram"></i></a>

                    <!-- Linkedin -->
                    <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #0082ca;"
                        href="#!" role="button"><i class="fab fa-linkedin-in"></i></a>
                    <!-- Github -->
                    <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #333333;"
                        href="#!" role="button"><i class="fab fa-github"></i></a>
                </section>
                <!-- Section: Social media -->
            </div>
            <!-- Grid container -->

            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
                © 2020 Copyright:
                <a class="text-body" href="#">Made with Love</a>
            </div>
            <!-- Copyright -->
        </footer>
        
        <style>
            /* Header Styles */
            header {
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                position: sticky;
                top: 0;
                z-index: 1000;
            }

            h1 {

                background: linear-gradient(to right, rgb(250, 80, 10), rgb(247, 12, 204), rgb(0, 106, 255), rgb(48, 248, 255), rgb(250, 80, 10));
                color: transparent !important;
                background-clip: text !important;
                background-size: 200% auto;
                animation: gradiant 5s linear infinite;
            }


            @keyframes gradiant {
                0% {
                    background-position: 200% 50%;
                }

                100% {
                    background-position: 0% 50%;
                }
            }

            nav ul {
                list-style: none;
                margin: 0;
                padding: 0;
                display: flex;
                gap: 1rem;
            }

            nav ul li a {
                color: white;
                text-decoration: none;
                font-weight: 500;
                font-size: 1.1rem;
                padding: 0.5rem 1rem;
                border-radius: 10px;
                transition: all 0.3s ease;
            }

            nav ul li a:hover {
                background: #808080;
            }

            /* Clear float after header */
            header::after {
                content: '';
                display: table;
                clear: both;
            }

            /* Main Content Container */
            main {
                min-height: calc(100vh - 200px);
                padding: 2rem;
                max-width: 1200px;
                margin: 0 auto;
            }
        </style>
    @show
</body>
</html>