{include 'throne/login-header.tpl'}
<div class="container">

    <form role="form" class="form-signin" method="POST" data-async class="form-horizontal" action="/responders/throneLogin.php" id="form-login" accept-charset="utf-8">
        <h2 class="form-signin-heading">Login</h2>
        <input type="text" class="form-control" name="text-user" id="text-user" placeholder="Username" autofocus>
        <input type="password" class="form-control"  name="text-pass" id="text-pass" placeholder="Password">
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit-login">Sign in</button>
    </form>

</div> <!-- /container -->

{include 'throne/login-footer.tpl'}