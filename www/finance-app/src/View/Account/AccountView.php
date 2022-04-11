<h2>Ваш профиль</h2>
<a href="http://finance-app/account/logout">Выйти из аккаунта</a>
<p>Ваше имя: <?php echo $data['username'] ?></p>
<p>Ваш баланс: <?php echo $data['balance'] ?></p>
<hr />
<form action='http://finance-app/account/withdraw' method='POST'>
    <p>
        <label for='amount'>Списать средства в количестве:</label>
        <input name='amount' type='number'></input>
    </p>
    <p>Данные для подтверждения операции:</p>
    <p>
        <label for='username'>Логин:</label>
        <input name='username' type='text'></input>
    </p>
    <p>
        <label for='password'>Пароль:</label>
        <input name='password' type='password'></input>
    </p>
    <p>
        <input type='submit' value='Списать средства'></input>
    </p>
</form>