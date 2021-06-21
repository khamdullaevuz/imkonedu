<?php

$db = @mysqli_connect("localhost", "elbek", "1529", "imkonedu") or die("Ulanishda xatolik");
@mysqli_set_charset($db, "utf8mb4") or die("Kodirovka xatoligi");