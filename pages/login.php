<?php
  if(isset($_GET['acao']) && $_GET['acao'] == 'logout'){
    unset($_SESSION['login']);
  }
  if(isset($_SESSION['login'])){ die("Your already logged in.");}
  if(isset($_POST['acao'])){
    $login = strip_tags($_POST['login']);
    $senha = strip_tags($_POST['senha']);

    $sql = MySql::getConn()->prepare("SELECT * FROM usuarios WHERE login = ? and senha = ?");
    $sql -> execute(array($login, $senha));

    if($sql->rowCount() == 1){
      // logged in
      $info = $sql->fetch();
      $_SESSION['login'] = $login;
      $_SESSION['id'] = $info['id'];
      $_SESSION['stripe_acc'] = $info['stripe_acc'];
    } else {
      // Failed login attempt
      die("username or password incorrect");
    }
  }


  if(isset($_SESSION['login'])){
    echo '<script>location.href="/"</script>';
  }
  

?>
<div class="container">
<h1>Login page</h1>
  <form method="post">
    <img class="mb-4" src="/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

    <div class="form-floating">
      <input name="login" type="text" class="form-control" id="floatingInput" placeholder="Usuario">
      <label for="floatingInput">Login</label>
    </div>
    <br>
    <div class="form-floating">
      <input name="senha" type="password" class="form-control" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div>

    <div class="checkbox mb-3">
      <label>
        <input type="checkbox" value="remember-me"> Remember me
      </label>
    </div>
    <button name='acao' class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
    <p class="mt-5 mb-3 text-muted">© 2017–2021</p>
  </form>
</div>