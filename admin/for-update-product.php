<?php

function check_input($data) {
    $data = trim($data); //Удаляет пробелы из начала и конца строки
    $data = stripslashes($data);//Удаляет экранирование символов
    $data = htmlspecialchars($data);// Преобразует специальные символы в HTML сущности. Например <a href='test'>Test</a> преобразует в &lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;
    return $data;
}

if(isset($_POST['title']) && isset($_POST['category-id']) && isset($_POST['authors']) && isset($_POST['discipline']) && isset($_POST['publisher']) && isset($_POST['binding']) && isset($_POST['num-pages']) && isset($_POST['year-of-publication']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['product-id'])){
  $title = $_POST['title'];
  $category_id = $_POST['category-id'];
  $authors = $_POST['authors'];
  $discipline = $_POST['discipline'];
  $publisher = $_POST['publisher'];
  $binding = $_POST['binding'];
  $num_pages = $_POST['num-pages'];
  $year_of_publication = $_POST['year-of-publication'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $product_id = $_POST['product-id'];

  $title = check_input($title);
  $category_id = check_input($category_id);
  $authors = check_input($authors);
  $discipline = check_input($discipline);
  $publisher = check_input($publisher);
  $binding = check_input($binding);
  $num_pages = check_input($num_pages);
  $year_of_publication = check_input($year_of_publication);
  $description = check_input($description);
  $price = check_input($price);

  //Работаем с базой данных
  try {
      require_once ('../db.php');
      
      $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); // подготовка запроса SQL на установку русской локации
      $set_local->execute(); // выполняем запрос

      //Переменная, которая будет хранить id предмета, который будет привязан к параметру в SQL-запросе
      $discipline_id = 0;
      //Выборка id предмета по его названию, если он есть в таблице
      $select_discipline = $conn->prepare('SELECT id FROM shop_db.disciplines WHERE name=:name'); 
      $select_discipline ->bindParam(':name', $discipline);
      $select_discipline ->execute(); 
      
      //Если id найден, то присваиваем его значение переменной, иначе добавляем название предмета в таблицу и его id присваиваем переменной 
      if($select_discipline->rowCount() > 0 ) {
        $discipline_id = $select_discipline->fetchColumn(); 
      }
      else {
        $insert_discipline = $conn->prepare('INSERT INTO shop_db.disciplines (id, name) VALUES (NULL, :name)');
        $insert_discipline->bindParam(':name', $discipline);
        $insert_discipline->execute();

        $discipline_id = $conn->lastInsertId();
      }

      //Переменная, которая будет хранить id издательства, который будет привязан к параметру в SQL-запросе
      $publisher_id = 0;
      //Выборка id издательства по его названию, если он есть в таблице
      $select_publisher = $conn->prepare('SELECT id FROM shop_db.publishers WHERE name=:name'); 
      $select_publisher ->bindParam(':name', $publisher);
      $select_publisher ->execute(); 
      
      //Если id найден, то присваиваем его значение переменной, иначе добавляем название издательства в таблицу и его id присваиваем переменной 
      if($select_publisher->rowCount() > 0 ) {
        $publisher_id = $select_publisher->fetchColumn(); 
      }
      else {
        $insert_publisher = $conn->prepare('INSERT INTO shop_db.publishers (id, name) VALUES (NULL, :name)');
        $insert_publisher->bindParam(':name', $publisher);
        $insert_publisher->execute();

        $publisher_id = $conn->lastInsertId();
      }      

      //Переменная, которая будет хранить id вида переплета, который будет привязан к параметру в SQL-запросе
      $binding_id = 0;
      //Выборка id вида переплета по его названию, если он есть в таблице
      $select_binding = $conn->prepare('SELECT id FROM shop_db.bindings WHERE name=:name'); 
      $select_binding ->bindParam(':name', $binding);
      $select_binding ->execute(); 
      
      //Если id найден, то присваиваем его значение переменной, иначе добавляем название вида переплета в таблицу и его id присваиваем переменной 
      if($select_binding->rowCount() > 0 ) {
        $binding_id = $select_binding->fetchColumn(); 
      }
      else {
        $insert_binding = $conn->prepare('INSERT INTO shop_db.bindings (id, name) VALUES (NULL, :name)');
        $insert_binding->bindParam(':name', $binding);
        $insert_binding->execute();

        $binding_id = $conn->lastInsertId();
      }   

      $authors_array = explode(", ", $authors);
      //Массив, который будет хранить id авторов
      $authors_id_array = [];
      foreach($authors_array as $author) {
        //Переменная, которая будет хранить id автора
        $author_id = 0;
        //Выборка id автора по его имени, если он есть в таблице
        $select_author = $conn->prepare('SELECT id FROM shop_db.authors WHERE name=:name'); 
        $select_author ->bindParam(':name', $author);
        $select_author ->execute(); 
        
        //Если id найден, то присваиваем его значение переменной, иначе добавляем имя автора в таблицу и его id присваиваем переменной 
        if($select_author->rowCount() > 0 ) {
          $author_id = $select_author->fetchColumn(); 
        }
        else {
          $insert_author = $conn->prepare('INSERT INTO shop_db.authors (id, name) VALUES (NULL, :name)');
          $insert_author->bindParam(':name', $author);
          $insert_author->execute();
  
          $author_id = $conn->lastInsertId();
        }  
        $authors_id_array[] = $author_id;
      }

      //Добавляем товар
      $update_product = $conn->prepare('UPDATE shop_db.products SET category_id=:category_id, discipline_id=:discipline_id, publisher_id=:publisher_id, binding_id=:binding_id, title=:title, price=:price, description=:description, num_pages=:num_pages, year_of_publication=:year_of_publication WHERE id=:id');
      $update_product->bindParam(':category_id', $category_id);
      $update_product->bindParam(':discipline_id', $discipline_id);
      $update_product->bindParam(':publisher_id', $publisher_id);
      $update_product->bindParam(':binding_id', $binding_id);
      $update_product->bindParam(':title', $title);
      $update_product->bindParam(':price', $price);
      $update_product->bindParam(':description', $description);
      $update_product->bindParam(':num_pages', $num_pages);
      $update_product->bindParam(':year_of_publication', $year_of_publication);
      $update_product->bindParam(':id', $product_id);
      $update_product->execute();      

      $photo = $_FILES['photo']['name'];
      $photo = filter_var($photo, FILTER_SANITIZE_STRING);
      $photo_size = $_FILES['photo']['size'];
      $photo_tmp_name = $_FILES['photo']['tmp_name'];
      $photo_folder = '../uploads/'.$photo;         

      //Обновляем фото товара, если не пустая переменная
      if(!empty($photo)){
        $select_old_photo = $conn->prepare('SELECT photo FROM shop_db.products WHERE id=:id'); 
        $select_old_photo ->bindParam(':id', $product_id);
        $select_old_photo ->execute(); 
        $old_photo = $select_old_photo->fetchColumn(); 

        $update_photo = $conn->prepare('UPDATE shop_db.products SET photo=:photo WHERE id=:id');
        $update_photo->bindParam(':photo', $photo);
        $update_photo->bindParam(':id', $product_id);
        $update_photo->execute(); 

        move_uploaded_file($photo_tmp_name, $photo_folder);
        unlink('../uploads/'.$old_photo);
      }

      $old_authors_id_array = [];
      $select_old_authors = $conn->prepare('SELECT author_id FROM shop_db.products_authors WHERE product_id=:product_id'); 
      $select_old_authors ->bindParam(':product_id', $product_id);
      $select_old_authors ->execute(); 
      $old_authors_id_array = $select_old_authors->fetchAll(PDO::FETCH_COLUMN); ; 

      //Добавляем авторов, которых не было
      foreach($authors_id_array as $author_id){
        if(!in_array($author_id, $old_authors_id_array)) {
          $insert_author = $conn->prepare('INSERT INTO shop_db.products_authors (id, product_id, author_id) VALUES (NULL, :product_id, :author_id)');
          $insert_author->bindParam(':author_id', $author_id);
          $insert_author->bindParam(':product_id', $product_id);
          $insert_author->execute();
        }
      }
      
      //Удаляем авторов, которых теперь нет
      foreach($old_authors_id_array as $old_authors_id){
        if(!in_array($old_authors_id, $authors_id_array)) {
          $delete_author = $conn->prepare('DELETE FROM shop_db.products_authors WHERE product_id=:product_id AND author_id=:author_id');
          $delete_author->bindParam(':author_id', $old_authors_id);
          $delete_author->bindParam(':product_id', $product_id);
          $delete_author->execute();
        }
      }

      header('Location: products.php');
  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
      echo 'ERROR: ' . $e->getMessage();
  }
}

?>