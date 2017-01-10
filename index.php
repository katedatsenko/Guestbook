<?php
session_start();
$con =  mysql_connect("guestbook", "root", "");
if (!$con) {
    die ("Error: ".mysql_error());
}
mysql_select_db("guestbook", $con);
if (!empty($_POST)) {
    if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $_POST['keystring']){
        $name = isset($_POST['name']) ? mysql_real_escape_string($_POST['name']) : '';
        $email = isset($_POST['email']) ? mysql_real_escape_string($_POST['email']) : '';
        $text = isset($_POST['text']) ? mysql_real_escape_string($_POST['text']) : '';
        $s = "INSERT INTO `records` SET
            `name` = '". $name ."',
            `email` = '". $email ."',
            `description` = '". $text ."',
            `when` = UNIX_TIMESTAMP()";

        mysql_query($s);
        echo '<script type="text/javascript">alert("Отзыв опубликован!");</script>';
    }else{
        echo '<script type="text/javascript">alert("Капча введена не верно!");</script>';
    }

}
unset($_SESSION['captcha_keystring']);

$result = mysql_query("SELECT * FROM records ");

?>
<!DOCTYPE html>
<html lang="ru" >
<head>
    <meta charset="utf-8" />
    <title>Гостевая книга</title>

    <link href="css/main.css" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>


    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.css">

    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.js"></script>


    <style type="text/css">
        @import "css/demo_table.css";
        @import "";
    </style>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function(){
            $('#datatables').dataTable({
                "aoColumnDefs": [
                    { "sWidth": "15%", "aTargets": [ 0 ] },
                    { "sWidth": "15%", "aTargets": [ 1 ] },
                    { "sWidth": "55%", "aTargets": [ 2 ] },
                    { "sWidth": "15%", "aTargets": [ 3 ] }
                ],
                "order": [[ 3, "desc" ]],
                "autoWidth": true,
                "sPaginationType":"full_numbers",
                "aoColumns": [
                    null,
                    null,
                    { "bSortable": false },
                    null
                ],
                "iDisplayLength": 25,
                "bLengthChange": false,
                "bFilter" : false,

                language: {
                    "processing": "Подождите...",
                    "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
                    "infoEmpty": "Записи с 0 до 0 из 0 записей",
                    "infoFiltered": "(отфильтровано из _MAX_ записей)",
                    "infoPostFix": "",
                    "loadingRecords": "Загрузка записей...",
                    "zeroRecords": "Записи отсутствуют.",
                    "emptyTable": "В таблице отсутствуют данные",
                    "paginate": {
                        "first": "Первая",
                        "previous": "Предыдущая",
                        "next": "Следующая",
                        "last": "Последняя"
                    },
                }
            });

        })

    </script>


</head>

<body>
<header>
    <div>
        <h1>GUEST BOOK</h1>
    </div>
</header>


<div class="form">
    <form action="index.php" method="post" class="contact_form">
        <ul>
            <li><h2>Оставьте свой отзыв</h2></li>
            <li><label>Ваше имя: </label>
                <input type="text" pattern="[a-zA-Z0-9]+" value="" title="Разрешается вводить только цирфы и буквы латинского алфавита" name="name" maxlength="255" placeholder="Введите ваше имя латиницей" required/>
            </li>
            <li>
                <label>E-mail: </label>
                <input type="email" value="" title="Введите ваш e-mail" name="email" maxlength="255" placeholder="Введите ваш e-mail" required />
            </li>
            <li><label>Отзыв: </label>
                <textarea  name="text" maxlength="500" placeholder="Ваш отзыв..." required></textarea>
            </li>
            <li>
                <img src="./capcha/?<?php echo session_name()?>=<?php echo session_id()?>">
                <input type="text" pattern="[a-zA-Z0-9]+" name="keystring" placeholder="Введите значения с картинки" required>
            </li>
            <li>
                <button class="submit" type="submit" name="send">Отправить</button>
            </li>
        </ul>
    </form>
</div>

<div class="table">
    <h2>Отзывы других пользователей</h2>
    <table id="datatables" class="display">
        <thead>
        <tr>
            <th>Автор</th>
            <th>E-mail</th>
            <th>Отзыв</th>
            <th>Дата</th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($row = mysql_fetch_array($result)){
            ?>
            <tr>
                <td><?=$row['name']?></td>
                <td><?=$row['email']?></td>
                <td><?=$row['description']?></td>
                <td><?php echo date('Y-m-d H:i:s', $row['when'])?></td>
            </tr>

            <?php
        } ?>
        </tbody>
    </table>
</div>
<footer>
    <h5>Developed by Kate Datsenko</h5>
</footer>
</body>
</html>
