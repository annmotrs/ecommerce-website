<?php
session_start();

if(!isset($_SESSION['admin_id'])) {
  header("Location: index.php");
}    
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Добавление товара</title>
  <link rel="icon" type="image/x-icon" href="../images/icon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" href="../css/admin-style.css">
  <link rel="stylesheet" href="../css/admin-media.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
</head>
<body>

  <?php
    require "admin-header.php";
  ?>

  <?php 
      try {
        require_once ('../db.php');

            $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
            $set_local->execute(); 

            //Выборка всех категорий
            $select_categories = $conn->prepare('SELECT * FROM shop_db.categories');
            $select_categories ->execute(); 
            $select_categories_array = $select_categories->fetchAll(); 

            //Выборка всех авторов
            $select_authors = $conn->prepare('SELECT name FROM shop_db.authors');
            $select_authors ->execute(); 
            $select_authors_array = $select_authors->fetchAll(PDO::FETCH_COLUMN); 
            
            //Выборка всех предметов
            $select_disciplines = $conn->prepare('SELECT name FROM shop_db.disciplines');
            $select_disciplines ->execute(); 
            $select_disciplines_array = $select_disciplines->fetchAll(PDO::FETCH_COLUMN);        
            
            //Выборка всех издательств
            $select_publishers = $conn->prepare('SELECT name FROM shop_db.publishers');
            $select_publishers ->execute(); 
            $select_publishers_array = $select_publishers->fetchAll(PDO::FETCH_COLUMN);     

            //Выборка всех видов переплета
            $select_bindings = $conn->prepare('SELECT name FROM shop_db.bindings');
            $select_bindings ->execute(); 
            $select_bindings_array = $select_bindings->fetchAll(PDO::FETCH_COLUMN);     
           


  ?>

  <section class="product-form">
    <div class="product-form__wrapper">
    <h1 class="title">Добавление товара</h1>
    <form action="for-create-product.php" method="POST" enctype="multipart/form-data">

      <p><label for="title" class="product-form__label">Название</label></p>
      <p><input type="text" name="title" id="title" class="product-form__input" required></p>

      <p><label for="category" class="product-form__label">Категория</label></p>
      <p>
        <select id="category" name="category-id" class="product-form__select">

        <?php
          foreach($select_categories_array as $select_categories_array_row) {
        ?>   
          <option value="<?= $select_categories_array_row['id'] ?>"><?= $select_categories_array_row['name'] ?></option>
        <?php
          }
        ?>  
        </select>
      </p> 

      <p><label for="author" class="product-form__label">Авторы</label></p>
      <p>
        <input type="text" list="authors" id="author" name="author" class="product-form__input">
        <datalist id="authors">
        <?php
          foreach($select_authors_array as $select_authors_array_row) {
        ?>             
          <option value="<?= $select_authors_array_row; ?>">
        <?php
          }
        ?> 
        </datalist>
        <button type="button" class="button-add-author">Добавить автора</button>
        <p class="product-form__authors-title">Выбранные авторы:</p>
        <ul class="product-form__authors-list">
          <li class="product-form__authors-item">нет</li>
        </ul>
        <input type="hidden" name="authors" class="product-form__input-authors" required>
      </p>       

      <p><label for="discipline" class="product-form__label">Предмет</label></p>   
      <p>
        <input type="text" list="disciplines" id="discipline" name="discipline" class="product-form__input" required>
        <datalist id="disciplines">
        <?php
          foreach($select_disciplines_array as $select_disciplines_array_row) {
        ?>             
          <option value="<?= $select_disciplines_array_row; ?>">
        <?php
          }
        ?> 
        </datalist>
      </p>  

      <p><label for="publisher" class="product-form__label">Издательство</label></p>   
      <p>
        <input type="text" list="publishers" id="publisher" name="publisher" class="product-form__input" required>
        <datalist id="publishers">
        <?php
          foreach($select_publishers_array as $select_publishers_array_row) {
        ?>             
          <option value="<?= $select_publishers_array_row; ?>">
        <?php
          }
        ?> 
        </datalist>
      </p>  

      <p><label for="binding" class="product-form__label">Переплет</label></p>   
      <p>
        <input type="text" list="bindings" id="binding" name="binding" class="product-form__input" required>
        <datalist id="bindings">
        <?php
          foreach($select_bindings_array as $select_bindings_array_row) {
        ?>             
          <option value="<?= $select_bindings_array_row; ?>">
        <?php
          }
        ?> 
        </datalist>
      </p>

      <p><label for="num-pages" class="product-form__label">Количество страниц</label></p>   
      <p><input type="number" id="num-pages" name="num-pages" class="product-form__input" required></p>  

      <p><label for="year-of-publication" class="product-form__label">Год выпуска</label></p>   
      <p><input type="year" id="year-of-publication" name="year-of-publication" class="product-form__input" required></p>     
      
      <p><label for="description" class="product-form__label">Описание</label></p>   
      <textarea id="description" name="description" class="product-form__textarea" required></textarea>

      <p><label for="price" class="product-form__label">Цена, руб.</label></p>   
      <p><input type="number" id="price" name="price" class="product-form__input" required></p>    

      <p><label for="photo" class="product-form__label">Фотография</label></p>   
      <p>
        <input type="file" id="photo" name="photo" accept="image/jpg, image/jpeg, image/png, image/webp" class="product-form__input-file" required>
        <label for="photo" class="product-form__file-button">
          <span class="product-form__file-icon-wrapper"><i class="fa-solid fa-download"></i></span>
          <span class="product-form__file-button-text">Выберите файл</span>
        </label>
      </p>          
      
      <div class="product-form__button-wrapper">
        <button class="button" type="submit">Добавить</button>
      </div>
      
    </form>
    </div>
  </section>

  <?php  
        }
        catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
          echo 'ERROR: ' . $e->getMessage();
        }

      ?>

  
  <script src="../js/main.js"></script>
  <script src="../js/createProduct.js"></script>
  
</body>
</html>