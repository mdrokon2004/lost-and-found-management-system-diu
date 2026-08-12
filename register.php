<?php
require_once __DIR__.'/config/config.php';
require_once __DIR__.'/config/db.php';

if(is_logged_in()) redirect('index.php');

$page_title='Create account';

if($_SERVER['REQUEST_METHOD']==='POST'){

  $name=trim($_POST['name']??'');
  $email=trim($_POST['email']??'');
  $pass=$_POST['password']??'';

  if(!$name || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($pass)<6){
    flash('danger','Enter a valid name, email and password (minimum 6 characters).');
  }else{

    $role=$pdo->query("SELECT id FROM roles WHERE code='user'")->fetchColumn();

    try{
      $s=$pdo->prepare("INSERT INTO users(role_id,name,email,password_hash) VALUES(?,?,?,?)");

      $s->execute([
        $role,
        $name,
        $email,
        password_hash($pass,PASSWORD_DEFAULT)
      ]);

      flash('success','Account created. Please log in.');
      redirect('login.php');

    }catch(PDOException $e){
      flash('danger','That email is already registered.');
    }
  }
}

include __DIR__.'/includes/header.php';
?>

<div class="row justify-content-center py-5">

  <div class="col-lg-6">

    <div class="form-card p-4 p-lg-5">

      <div class="text-primary fw-bold">WELCOME</div>

      <h2 class="section-title">Create your account</h2>

      <p class="text-secondary">
        Join the DIU Lost & Found community.
      </p>

      <form method="post" class="mt-4">

        <label class="form-label">Full name</label>
        <input
          class="form-control mb-3"
          name="name"
          required
        >

        <label class="form-label">Email</label>
        <input
          class="form-control mb-3"
          type="email"
          name="email"
          required
        >

        <label class="form-label">Password</label>
        <input
          class="form-control mb-4"
          type="password"
          name="password"
          minlength="6"
          required
        >

        <button
          type="submit"
          class="btn btn-primary-glass w-100 py-3"
        >
          Create account
        </button>

      </form>

      <div class="text-center mt-4 small">
        Already registered?
        <a href="<?=BASE_URL?>/login.php">Log in</a>
      </div>

    </div>

  </div>

</div>

<?php include __DIR__.'/includes/footer.php'; ?>