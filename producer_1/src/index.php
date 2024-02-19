<?php
session_start();
?>

<?php

if (isset($_SESSION['error_connection_rabbitmq'])) {
    echo "<b>" . $_SESSION['error_connection_rabbitmq']['message'] . "</b>";
    unset ($_SESSION['error_connection_rabbitmq']);
}
?>


<div>
    <form method="POST" action="lead.php" style="max-width:500px;margin-left: auto; margin-right: auto;">
        <div style="margin-bottom: 5px;">
            <label for="name">Имя</label>
            <br>
            <input id="name" value="Arthur" name="name"/>
        </div>

        <div style="margin-bottom: 5px;">
            <label for="phone">Телефон</label>
            <br>
            <input id="phone" value="0732525256" name="phone"/>
        </div>

        <div style="margin-bottom: 5px;">
            <label for="comment">Комментарий</label>
            <br>
            <textarea id="comment" name="comment">Почем окно? 20*30см</textarea>
        </div>

        <button type="submit">Отправить</button>

    </form>
</div>



