<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-brands.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-regular.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
    <a class="navbar-brand d-flex align-items-center fw-500" href="users.html"><img alt="logo"
                                                                                    class="d-inline-block align-top mr-2"
                                                                                    src="img/logo.png"> Training
        project</a>
    <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"
            data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/">Home<span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/login">Sign in</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/logout">Sign out</a>
            </li>
        </ul>
    </div>
</nav>
<main id="js-page-content" role="main" class="page-content mt-3">
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-plus-circle'></i> Add user
        </h1>


    </div>
    <form action="/create_user_handler" method="POST">
        <div class="row">
            <div class="col-xl-6">
                <div id="panel-1" class="panel">
                    <div class="panel-container">
                        <div class="panel-hdr">
                            <h2>General information</h2>
                        </div>
                        <div class="panel-content">
                            <!-- username -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Name</label>
                                <input type="text" id="simpleinput" name="user_name" class="form-control">
                            </div>

                            <!-- title -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Place of work</label>
                                <input type="text" id="simpleinput" name="work" class="form-control">
                            </div>

                            <!-- tel -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Phone number</label>
                                <input type="text" id="simpleinput" name="phone" class="form-control">
                            </div>

                            <!-- address -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">The address</label>
                                <input type="text" id="simpleinput" name="address" class="form-control">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-xl-6">
                <div id="panel-1" class="panel">
                    <div class="panel-container">
                        <div class="panel-hdr">
                            <h2>Security and Media</h2>
                        </div>
                        <div class="panel-content">
                            <!-- email -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Email</label>
                                <input type="text" id="simpleinput" name="email" class="form-control">
                            </div>

                            <!-- password -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Password</label>
                                <input type="password" id="simpleinput" name="password" class="form-control">
                            </div>


                            <!-- status -->
                            <div class="form-group">
                                <label class="form-label" for="example-select">Select status</label>
                                <select class="form-control" id="example-select" name="status_online">
                                    <option value="0">Online</option>
                                    <option value="1">Departed</option>
                                    <option value="2">Do not disturb</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="example-fileinput">Upload avatar</label>
                                <input type="file" id="example-fileinput" class="form-control-file">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-container">
                        <div class="panel-hdr">
                            <h2>Social media</h2>
                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- vk -->
                                    <div class="input-group input-group-lg bg-white shadow-inset-2 mb-2">
                                        <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0 py-1 px-3">
                                                    <span class="icon-stack fs-xxl">
                                                        <i class="base-7 icon-stack-3x" style="color:#4680C2"></i>
                                                        <i class="fab fa-vk icon-stack-1x text-white"></i>
                                                    </span>
                                                </span>
                                        </div>
                                        <input type="text" name="vk_social_media" class="form-control border-left-0 bg-transparent pl-0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- telegram -->
                                    <div class="input-group input-group-lg bg-white shadow-inset-2 mb-2">
                                        <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0 py-1 px-3">
                                                    <span class="icon-stack fs-xxl">
                                                        <i class="base-7 icon-stack-3x" style="color:#38A1F3"></i>
                                                        <i class="fab fa-telegram icon-stack-1x text-white"></i>
                                                    </span>
                                                </span>
                                        </div>
                                        <input type="text" name="telegram_social_media" class="form-control border-left-0 bg-transparent pl-0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- instagram -->
                                    <div class="input-group input-group-lg bg-white shadow-inset-2 mb-2">
                                        <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0 py-1 px-3">
                                                    <span class="icon-stack fs-xxl">
                                                        <i class="base-7 icon-stack-3x" style="color:#E1306C"></i>
                                                        <i class="fab fa-instagram icon-stack-1x text-white"></i>
                                                    </span>
                                                </span>
                                        </div>
                                        <input type="text" name="instagram_social_media" class="form-control border-left-0 bg-transparent pl-0">
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                    <button class="btn btn-success" type="submit" name="submit">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</main>

<script src="js/vendors.bundle.js"></script>
<script src="js/app.bundle.js"></script>
<script>

    $(document).ready(function () {


    });

</script>
</body>
</html>