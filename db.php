<?php

//PDO ( PHP Data Objects ) — расширение PHP, которое реализует взаимодействие с базами данных при помощи объектов. 
//Смысл в том, что отсутствует привязка к конкретной системе управления базами данных.

$host = 'localhost';
$db   = 'shop_db';
$user = 'root';
$pass = '';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset"; //Создали строку подключения со всеми параметрами
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,//Помимо задания кода ошибки PDO будет выбрасывать исключение PDOException, свойства которого будут отражать код ошибки и ее описание. Этот режим также полезен при отладке, так как сразу известно, где в программе произошла ошибка
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Возвращает массивы с текстовыми индексами
    PDO::ATTR_EMULATE_PREPARES   => false, //Возможность отправлять подготовленные выражения
];

$conn = new PDO($dsn, $user, $pass, $opt);// переменная подключения, ее будем использовать в других файлах