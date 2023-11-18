<?php

	include('MySql.php');

	session_start();

?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="">


	<title>MarketPlace | Home</title>
</head>

<body>
	<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
      
      <a href="/" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
        <h3>Danki Code</h3>  
      </a>

      <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li><a href="#" class="nav-link px-2 link-secondary">Produtos</a></li>
        <li><a href="#" class="nav-link px-2 link-dark">Contato</a></li>
        <li><a href="#" class="nav-link px-2 link-dark">Sobre</a></li>
        <li><a href="#" class="nav-link px-2 link-dark">FAQ</a></li>
      </ul>

      <div class="col-md-3 text-end">
        <?php if(!isset($_SESSION['login'])){ ?>
          <button id="login" type="button" class="btn btn-outline-primary me-2">Login</button>
          <button id="cadastro" type="button" class="btn btn-primary">Sign-up</button>
        <?php } else { ?>
          <button id="cadastro_produto" type="button" class="btn btn-primary">cadastrar produto</button>
          <button id="logoff" type="button" class="btn btn-outline-primary">logout</button>
        <?php }; ?>
      </div>
    </header>
  </div>



	<?php
  if(isset($_SESSION['login'])){
    echo '<h3>Bem-Vindo ' . $_SESSION['login'] ;
  };
  if(isset($_GET['url'])) { 
    $url = $_GET['url']; 
    if(file_exists('pages/'.$url.'.php'))  {
      include('pages/' . $url . '.php'); 
    } else {
      die('404, page not found');
    }
  // } else {
  //   // Homepage
  //   $sql = Mysql::getConn()->prepare("SELECT * from produtos");
  //   $sql->execute();
  //   $produtos = $sql->fetchAll();

  //   foreach ($produtos as $key => $value){
  //     $usuario = MySql::getConn()->prepare("SELECT * FROM usuarios WHERE id = $value[usuario_id]");
  //     $usuario->execute();
  //     // print_r($usuario->fetch());
  //     $usuario = $usuario->fetch()['login'];

  //     echo '<div class="container"><h2>' . $value['nome'] . '</h2><p>' . $value['descricao'] . ' por <b>' .$usuario. '</b></p> <h3>R$' . $value['preco'] . '</h3> <button class=btn btn-primary">Comprar Agora</button> <hr> </div>';
  //   }
  }

	?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <script>
    document.getElementById('login')?.addEventListener('click', ()=> window.location.href="?url=login")
    document.getElementById('cadastro')?.addEventListener('click', ()=> window.location.href="?url=cadastro")
    document.getElementById('cadastro_produto')?.addEventListener('click', ()=> window.location.href="?url=cadastro_produto")
    document.getElementById('logoff')?.addEventListener('click', ()=> window.location.href="?url=login&acao=logout")
    
  </script>


</body>
</html>