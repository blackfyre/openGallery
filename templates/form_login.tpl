{include file='header.tpl'}

<div class="login box">
    <form action="" method="POST">
        <fieldset>
            <legend>{$legend}</legend>
            <input type="text" name="text-userName" placeholder="{$userPlace}" value="{$user}"><br />
            <input type="password" name="text-userPass" placeholder="{$passPlace}" value="{$pass}"><br />
            <input type="submit" name="commit-FormForLogin" value="{$submitValue}">
        </fieldset>
    </form>
</div>

{include file='footer.tpl'}