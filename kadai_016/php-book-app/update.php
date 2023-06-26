<?php
  $dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
  $user = 'root';
  $password = 'root';

// submitパラメータの値が存在するとき（「更新」ボタンを押したとき）の処理
  if(isset($_POST['submit'])){
    try{
      $pdo = new PDO($dsn,$user,$password);

      $sql_update = 'UPDATE books SET 
      book_code = :book_code,
      book_name = :book_name,
      price = :price,
      stock_quantity = :stock_quantity,
      genre_code = :genre_code
      where id = :id';

      $stmt_update = $pdo ->prepare($sql_update);

      $stmt_update -> bindValue(':book_code',$_POST['book_code'],PDO::PARAM_INT);
      $stmt_update -> bindValue(':book_name',$_POST['book_name'],PDO::PARAM_STR);
      $stmt_update -> bindValue(':price',$_POST['price'],PDO::PARAM_INT);
      $stmt_update -> bindValue(':stock_quantity',$_POST['stock_quantity'],PDO::PARAM_INT);
      $stmt_update -> bindValue(':genre_code',$_POST['genre_code'],PDO::PARAM_INT);
      $stmt_update -> bindValue(':id',$_GET['id'],PDO::PARAM_INT);

      $stmt_update->execute();

     
      $count = $stmt_update->rowCount();

      $message = "商品を{$count}件編集しました";

      header("Location:read.php?message={$message}");
    }catch (PDOException $e){
      exit($e ->getMessage());
    }
  }



  if(isset($_GET['id'])){
    try{
      $pdo = new PDO($dsn,$user,$password);

      // idカラムの値をプレースホルダ（:id）に置き換えたSQL文をあらかじめ用意する
      $sql_book_select = 'SELECT * FROM books where id = :id';
      $stmt_book_select = $pdo ->prepare($sql_book_select);

      $stmt_book_select -> bindValue(':id', $_GET['id'], PDO::PARAM_INT);

      $stmt_book_select -> execute();

      $book = $stmt_book_select -> fetch(PDO::FETCH_ASSOC);

      if($book === FALSE){
        exit('パラメータの値が不正です');
      }

      // vendorsテーブルからvendor_codeカラムのデータを取得するためのSQL文を変数$sql_select_vendor_codesに代入する
      $sql_genre_select = 'SELECT genre_code FROM genres ';

      $stmt_genre_select = $pdo ->query($sql_genre_select);

      $genre_codes = $stmt_genre_select -> fetchALL(PDO::FETCH_COLUMN);
    
    }catch (PDOException $e) {
      exit($e -> Message());
    }  

  } else {
    exit('idパラメータの値が存在しません');
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>書籍編集</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="index.php"><header id="header">書籍管理アプリ</header></a>

<main class="create-main">
  <h1>書籍編集</h1>
  <a href="read.php"><button class="back">戻る</button></a>


  <form action="update.php?id=<?= $_GET['id'] ?>" method="POST" class="form-01">
    <section>
    
    <label for="">書籍コード</label>
    <input type="number" name="book_code" value="<?= $book['book_code'] ?>"min="0" max="100000000" required>

    <label for="">書籍名</label>
    <input type="text" name="book_name" value="<?= $book['book_name'] ?>" min="0" max="10000000" required>

    <label for="">値段</label>
    <input type="number" name="price" value="<?= $book['price'] ?>"min="0" max="10000000" required>

    <label for="">在庫数</label>
    <input type="number" name="stock_quantity" value="<?= $book['stock_quantity'] ?>"min="0" max="10000000" required>

    <label for="">ジャンルコード</label>
    <select name="genre_code" id="">
      <option disabled selected value>選択してください</option>
      <!-- php記載 -->
      <?php
        foreach($genre_codes as $genre_code){
          if($genre_code === $book['genre_code']){
            echo"<option value='{$genre_code}' selected>{$genre_code}</option>";
          }else
          echo"<option value = '{$genre_code}' >{$genre_code}</option>" ;
        }
      ?>
    </select>
</section>
    <div class="btn-02">
      <div>
      <button type="submit" name="submit" class="submit-btn">更新</button>
      </div>
    </div>
  </form>
 
</main>

<footer class="footer-02">
    &copy; 書籍管理アプリ All rights reserved.
  </footer>
  
  
</body>
</html>
