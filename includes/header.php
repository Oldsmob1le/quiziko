<?php include ('connect.php'); ?>
<?php include ('session.php'); ?>

<nav class="navbar navbar-expand-lg navbar-light" aria-label="Offcanvas navbar large">
  <div class="container">
    <div class="navbar-logo">
      <a href="#" class="logo text-decoration-none">
        NEWS
      </a>
    </div>

    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar2"
      aria-controls="offcanvasNavbar2" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="offcanvas offcanvas-end text-bg-light" tabindex="-1" id="offcanvasNavbar2"
      aria-labelledby="offcanvasNavbar2Label">
      <div class="offcanvas-header">
        <div class="navbar-logo">
          <a href="#" class="logo text-decoration-none">
            NEWS
          </a>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-center flex-grow-1">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Главная</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="news.php">Новости</a>
          </li>
          <li class="nav-item">
            <?php
            if (isset($_SESSION['uid'])) {
              echo '<a class="nav-link" href="account.php">Личный кабинет</a>';
            }
            ?>
          </li>
        </ul>

        <div class="navbar-btn">
          <?php
          if (isset($_SESSION['uid'])) {
            echo '<a href="?do=exit">
                    <button type="button" class="btn btn-primary">Выйти</button>
                  </a>';
          } else {
            echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                    Войти
                  </button>';
            echo '<button type="button" class="btn btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#registerModal">
                    Регистрация
                  </button>';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- AUTH -->

<?php
// if (isset($_SESSION['uid'])) {
//   // echo '<script>document.location.href="account.php"</script>';
// }
$error = '';
$email = '';
if (isset($_POST['signin'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE email='$email'";
  $result = $conn->query($sql);
  $temp_user = $result->fetch();

  if (empty($email)) {                                       // на пустоту почты
    $error .= "<p>Введите почту</p>";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {     // на формат почты
    $error .= "<p>Неверный формат почты</p>";
  } elseif ($temp_user == false) {                           // есть ли такой пользователь
    $error .= "<p>вас нет в базе</p>";
  } elseif (empty($password)) {
    $error .= "<p>введите пароль</p>";
  } elseif (!password_verify($password, $temp_user['password'])) {    // парвильно ли введен пароль
    $error .= "<p>неверный пароль</p>";
  }

  if (empty($error)) {
    // процесс авторизации
    $_SESSION['uid'] = $temp_user['id'];
    echo '<script>document.location.href="account.php"</script>';
  }
}
?>

<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Войти в аккаунт</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" name="signin">
          <?= $error ?>
          <div class="mb-3">
            <label for="loginEmail" class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" id="loginEmail" placeholder="Введите email"
              value="<?= $email ?>">
          </div>
          <div class="mb-3">
            <label for="loginPassword" class="form-label">Пароль:</label>
            <input type="password" name="password" class="form-control" id="loginPassword" placeholder="Введите пароль">
          </div>
          <button type="submit" name="signin" class="btn btn-primary">Войти</button>
        </form>
        <p class="mt-3">
          Нет аккаунта? <a href="#" id="switchToRegisterFromLogin">Зарегистрироваться</a>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- REG -->

<?php

$error = '';

if (isset($_POST['signup'])) {

  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $password_repeat = $_POST['password_repeat'];

  if ($name === '') {
    $error .= "Введите имя! <br>";
  } else if (strlen($name) < 4) {
    $error .= "Введите корректрное имя, минимум 4 символа! <br>";
  }

  if ($email === '') {
    $error .= "Введите почту! <br>";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error .= "Ввидите верный формат почты! <br>";
  }

  if ($password === '') {
    $error .= "Введите пароль! <br>";
  } else if (strlen($password) < 6) {
    $error .= "Пароль слишком короткий, минимум 6 символа! <br>";
  }

  if ($password_repeat === '') {
    $error .= "Введите повтор пароль! <br>";
  } else if ($password !== $password_repeat) {
    $error .= "Пароли не совподают! <br>";
  }

  // Уникальность
  $sql = "SELECT count(*) FROM users WHERE email = '$email'";
  $user_count = $conn->query($sql)->fetchColumn();
  if ($user_count == 1) {
    $error .= 'Данный аккаунт уже зарегестрирован!';
  }

  if (empty($error)) {

    // Шифрование пароля
    $hash_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (`name`, `email`, `password`)
            VALUES ('$name', '$email', '$hash_password')";
    $conn->query($sql);
    echo '<script>document.location.href="?"</script>';
  }

}
?>

<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="registerModalLabel">Зарегистрироваться</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" name="signup">
          <div class="mb-3">
            <label for="registerName" class="form-label">Имя:</label>
            <input type="text" name="name" class="form-control" id="registerName" placeholder="Введите Имя"
              value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>">
          </div>

          <div class="mb-3">
            <label for="registerEmail" class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" id="registerEmail" placeholder="Введите email"
              value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
          </div>

          <div class="mb-3">
            <label for="registerPassword" class="form-label">Пароль:</label>
            <input type="password" name="password" class="form-control" id="registerPassword"
              placeholder="Введите пароль">
          </div>

          <div class="mb-3">
            <label for="registerPassword" class="form-label">Повторите пароль:</label>
            <input type="password" name="password_repeat" class="form-control" id="registerPassword"
              placeholder="Повторите пароль">
          </div>

          <button type="submit" class="btn btn-outline-primary" name="signup">Зарегистрироваться</button>
        </form>
        <p class="mt-3">
          Уже есть аккаунт? <a href="#" id="switchToLoginFromRegister">Войти</a>
        </p>
      </div>
    </div>
  </div>
</div>

<script src="style/modal.js"></script>