<?php
  if(!isset($_SESSION['login'])){ die("Your not logged in.");} 

    if(isset($_POST['acao'])){
      $nome = strip_tags($_POST['nome']);
      $descricao = strip_tags($_POST['descricao']);
      $preco = strip_tags($_POST['preco']);
      $conteudo = strip_tags($_POST['conteudo']);

    //   $sql = MySql::getConn()->prepare("SELECT * FROM produtos WHERE login = ?");
    //   $sql -> execute(array($login));

    //   if($sql->rowCount() == 1){
    //     // Username already exists, proceed to return error message
    //     echo '<script>alert("Username is not available")</script>'; //Alert 
    //     echo '<script>location.href="/"</script>';
    //     die("username is not available");
    //   } else {
        // Username is available to be registred
        $sql = MySql::getConn()->prepare("INSERT INTO produtos values (null, ?, ?, ?, ?, ?)");
        $sql->execute(array($_SESSION['id'], $nome, $descricao, $preco, $conteudo));
        echo '<script>alert("Cadastro do produto realizado com sucesso")</script>'; //Alert 
        // $_SESSION['login'] = $login;
      }
    // };

    

?>

<div class="container">
    <h1>Cadastre um produto page</h1>   
  <form method="post">
    <img class="mb-4" src="/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
    <h1 class="h3 mb-3 fw-normal">Cadastre um produto</h1>

    <div class="form-floating">
      <input name="nome" type="text" class="form-control" id="floatingInput" placeholder="Nome do produto">
      <label for="floatingInput">Nome do produto</label>
    </div>
    <br>
    <div class="form-floating">
      <input name="descricao" type="text" class="form-control" id="floatingInput" placeholder="Descrição do produto">
      <label for="floatingInput">Descrição do produto</label>
    </div>
    <br>
    <div class="form-floating">
      <input name="preco" type="number" class="form-control" id="floatingInput" placeholder="Preço do produto">
      <label for="floatingInput">Preço do produto</label>
    </div>
    <br>
    <div class="form-floating">
      <input name="conteudo" type="text" class="form-control" id="floatingInput" placeholder="Conteudo do produto">
      <label for="floatingInput">Conteudo do produto</label>
    </div>
    <br>



    <button name='acao' class="w-100 btn btn-lg btn-primary" type="submit">Cadastrar</button>
    <p class="mt-5 mb-3 text-muted">© 2017–2021</p>
  </form>
</div>