<?php include('includes/connect.php'); ?>
<?php include('includes/session.php'); ?>

<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SkyAdmin</title>
    <link rel="shortcut icon" href="assets/image/logo.svg" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="assets/fonts/stylesheet.css" />
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/navbar-static/" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3" />
    <link rel="stylesheet" href="assets/style/settings.css" />
    <link rel="stylesheet" href="assets/style/admin.css" />

</head>

<body>

    <nav class="navbar bg-body-tertiary fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Переключить навигацию">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="d-flex gap-5 ">
                <form class="d-flex d-none d-sm-block" role="search">
                    <input class="form-control" type="search" placeholder="Поиск" aria-label="Поиск">
                </form>
                <div class="flex-shrink-0 dropdown">
                    <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser2"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="image/admin/avatar.jpg" alt="user" width="32" height="32"
                            class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser2">
                        <li><a class="dropdown-item" href="#">Выйти</a></li>
                    </ul>
                </div>
            </div>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">ADMIN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item d-flex align-items-center gap-2">
                            <i class="bi bi-house fs-5"></i>
                            <a class="nav-link active" aria-current="page" href="account.html">Главная</a>
                        </li>
                        <li class="nav-item d-flex align-items-center gap-2">
                            <i class="bi bi-people fs-5"></i>
                            <a class="nav-link" href="user.html">Пользователи</a>
                        </li>
                    </ul>
                </div>
                <div class="d-flex justify-content-center mb-5 site">
                    <li class="nav-item d-flex">
                        <a class="nav-link" href="index.html">Новости</a>
                    </li>
                </div>
            </div>
        </div>
    </nav>


    <section class="container section mt-5 pt-5">
        <div class="d-none d-xl-block">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Главная</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Пользователи</li>
                </ol>
            </nav>
        </div>

        <div class="m-4 d-flex flex-column align-items-center">
            <form class="d-flex d-block d-sm-none mb-3" role="search">
                <input class="form-control" type="search" placeholder="Поиск" aria-label="Поиск">
            </form>
            <table class="table table-striped table-hover">
                <thead class="text-center">
                    <tr>
                        <th scope="col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            </div>
                        </th>
                        <th scope="col" data-label="uID">uID</th>
                        <th scope="col" data-label="Имя">Имя</th>
                        <th scope="col" data-label="E-mail">E-mail</th>
                        <th scope="col" data-label="Пароль">Пароль</th>
                        <th scope="col" data-label="Роль">Роль</th>
                        <th scope="col" data-label="Управление">Управление</th>

                    </tr>
                </thead>
                <tbody class="text-center">
                    <tr>
                        <th data-label="Пользователь">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            </div>
                        </th>
                        <th scope="row" data-label="uID">1</th>
                        <td data-label="Имя">Никита</td>
                        <td data-label="E-mail">abdulin00@vk.com</td>
                        <td data-label="Пароль">Мужской</td>
                        <td data-label="Роль">
                            <span class="badge text-bg-danger">Администратор</span>
                        </td>
                        <td class="pt-4 pt-lg-2">
                            <div class="btn-group">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Изменить
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Понизить</a></li>
                                    <li><a class="dropdown-item" href="#">Редактировать</a></li>
                                    <li><a class="dropdown-item" href="#">Повысить</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Удалить</a></li>
                                </ul>
                              </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center mt-5 d-flex gap-4">
                <button type="button" class="btn btn-outline-danger d-none d-lg-block">Удалить пользователя</button>
                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addUserModal">Добавить пользователя</button>
            </div>

            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Добавить пользователя</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addUserForm">
                                <div class="mb-3">
                                    <label for="fullName" class="form-label">Имя</label>
                                    <input type="text" class="form-control" id="fullName" name="fullName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Пароль</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="submit" class="btn btn-primary px-5" form="addUserForm">Добавить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>