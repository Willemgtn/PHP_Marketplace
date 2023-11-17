<?php 
    if(isset($_POST['acao'])){
      $nome = strip_tags($_POST['nome']);
      $login = strip_tags($_POST['login']);
      $senha = strip_tags($_POST['senha']);

      $sql = MySql::getConn()->prepare("SELECT * FROM usuarios WHERE login = ?");
      $sql -> execute(array($login));

      if($sql->rowCount() == 1){
        // Username already exists, proceed to return error message
        echo '<script>alert("Username is not available")</script>'; //Alert 
        echo '<script>location.href="/"</script>';
        die("username is not available");
      } else {
        // Username is available to be registred
        $sql = MySql::getConn()->prepare("INSERT INTO usuarios values (null, ?, ?, ?, null)");
        $sql->execute(array($login, $senha, $nome));
        echo '<script>alert("Cadastro realizado com sucesso")</script>'; //Alert 
        // $_SESSION['login'] = $login;
      }
    };
    // Protecting for spam register.
    if(isset($_SESSION['login'])){
      echo '<script>location.href="/"</script>';
    }
    
    if(!isset($_SESSION['login'])){
?>
<h1>Login page</h1>
<div class="container">
  <form method="post">
    <img class="mb-4" src="/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
    <h1 class="h3 mb-3 fw-normal">Cadastre-se</h1>

    <div class="form-floating">
      <input name="nome" type="text" class="form-control" id="floatingInput" placeholder="nome">
      <label for="floatingInput">Nome</label>
    </div>
    <br>
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
<?php }; ?>