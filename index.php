<?php

use Stripe\Balance;
use Stripe\FinancialConnections\Account;

  require('vendor/autoload.php');
	include('MySql.php');

	session_start();

  \Stripe\Stripe::setApiKey($stripe_test_sk);
 
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
    echo '<div class="container"><h3>Bem-Vindo ' . $_SESSION['login'] . '<br>';
    print_r($_SESSION);
    if( $_SESSION['stripe_acc' == null ]) { echo ('stripe account null'); } ;
    echo '<hr>';

    if($_SESSION['stripe_acc'] == null){
      if(isset($_GET['sucesso_stripe_onboarding'])){
        $account_id = $_GET['sucesso_stripe_onboarding'];
        $atualizar = \MySql::getConn()->prepare("UPDATE usuarios SET  stripe_acc = ? WHERE id = ?");
        $atualizar -> execute(array($account_id, $_SESSION['id']));
        $_SESSION['stripe_acc'] = $account_id;
        echo "<script>window.location.href='http://localhost/'</script>";
        die();
      };
      // Usuario não possue conta no stripe
      $account = \Stripe\Account::create([
        'country' => 'BR',
        'type' => 'standard'
      ]);
      $account_link = \Stripe\AccountLink::create([
        'account' => $account['id'],
        'refresh_url' => 'http://localhost/',
        'return_url' => 'http://localhost/?sucesso_stripe_onboarding=' . $account['id'],
        'type' => 'account_onboarding'
      ]);
      echo '<h3> Você não possui nenhuma conta bancaria associada, vamos cadastrar para voce receber o dinheiro das suas vendas!</h3>';
      echo '<br> <a href="'.$account_link['url'].'">Clique aqui para iniciar!</a>';
      
    } else {
      $stripe_acc = \MySql::getConn()->prepare("SELECT * FROM usuarios");
      $stripe_acc->execute();
      $stripe_acc = $stripe_acc->fetch()['stripe_acc'];
      $account = \Stripe\Account::retrieve($stripe_acc);
      if($account['capabilities']['card_payments'] == 'active') {
        $balance = \Stripe\Balance::retrieve(['stripe_account' => $stripe_acc] );
        print_r($balance);
        echo '<hr>';
        print_r($balance['available'][0]['amount']);
        print_r($balance['pending'][0]['amount']);
        echo '<h3> Seu saldo R$ ' . $balance['available'][0]['amount'] . '</h3>';
      } else {
        echo '<h3>Conta Stripe pendente de cadastro</h3>';
        // print_r($account);
        $account_link = \Stripe\AccountLink::create([
          'account' => $account['id'],
          'refresh_url' => 'http://localhost/',
          'return_url' => 'http://localhost/?sucesso_stripe_onboarding=' . $account['id'],
          'type' => 'account_onboarding'
        ]);
        echo '<br> <a href="'.$account_link['url'].'">Clique aqui para completar!</a>';
      };
      echo '<hr></div>';
    }
  };
  if(isset($_GET['url'])) { 
    $url = $_GET['url']; 
    if(file_exists('pages/'.$url.'.php'))  {
      include('pages/' . $url . '.php'); 
    } else {
      die('404, page not found');
    }
  } else {
    
    // Homepage
    $sql = Mysql::getConn()->prepare("SELECT * from produtos");
    $sql->execute();
    $produtos = $sql->fetchAll();

    foreach ($produtos as $key => $value){
      $usuario = MySql::getConn()->prepare("SELECT * FROM usuarios WHERE id = $value[usuario_id]");
      $usuario->execute();
      // print_r($usuario->fetch());
      $usuario = $usuario->fetch();

      $strip_session = \Stripe\Checkout\Session::create([
        'line_items' => [[
          'price_data' => [
            'currency' => 'brl',
            'product_data' => [
              'name' => $value['nome'],
          ],
          'unit_amount' => $value['preco'],  
        ],
        'quantity' => 1,
        ]],
        'locale' => 'pt-BR',
        'payment_intent_data' => [
          'application_fee_amount' => (int)($value['preco'] * 0.4),
          'transfer_data' => ['destination' => $usuario['stripe_acc']]
          ],
        'mode' => 'payment',
        'success_url' => 'http://localhost/?code={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhost/?code={CHECKOUT_SESSION_ID}',
        ]);
      // echo '<a href="'.$strip_session['url'].'">'.$strip_session['url'].'</a>';

      echo '<div class="container"><h2>' . $value['nome'] . '</h2><p>' . $value['descricao'] . ' por <b>' .$usuario['login']. '</b></p> <h3>R$' . $value['preco'] . '</h3> <a href="'. $strip_session['url'] .'" class="btn btn-primary">Comprar Agora</a> <hr> </div>';
    }
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