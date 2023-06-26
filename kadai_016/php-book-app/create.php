<?php
  $dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
  $user= 'root';
  $password = 'root';

  if(isset($_POST['submit'])){

  try {
    $pdo = new PDO($dsn,$user,$password);

    $sql_insert = 'INSERT INTO books (book_code,book_name,price,stock_quantity,genre_code)
    VALUES (:book_code,:book_name,:price,:stock_quantity,:genre_code)';

    $stmt_insert = $pdo -> prepare($sql_insert);

    $stmt_insert -> bindValue(':book_code' ,$_POST['book_code'] ,PDO::PARAM_INT);
    $stmt_insert -> bindValue(':book_name' ,$_POST['book_name'] ,PDO::PARAM_STR);
    $stmt_insert -> bindValue(':price' ,$_POST['price'] ,PDO::PARAM_INT);
    $stmt_insert -> bindValue(':stock_quantity' ,$_POST['stock_quantity'] ,PDO::PARAM_INT);
    $stmt_insert -> bindValue(':genre_code' ,$_POST['genre_code'] ,PDO::PARAM_INT);

    $stmt_insert -> execute();
    
    $count = $stmt_insert -> rowCount();
    $message = "商品を{$count}件登録しました";

    header("Location: read.php?message={$message}");
    } catch(PDOException $e) {
        exit($e->getMessage());
    }
  }

  // セレクトボックスの選択肢として設定するため、仕入先コードの配列を取得する
try{
  $pdo = new PDO($dsn,$user,$password);

  $sql_select = 'SELECT genre_code FROM genres';

  $stmt_select = $pdo -> query($sql_select);

  $genre_codes = $stmt_select -> fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e){
  exit($e->getMessage());
}
  
?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>書籍登録</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="index.php"><header id="header">書籍管理アプリ</header></a>

<main class="create-main">
  <h1>書籍登録</h1>
  <a href="read.php"><button class="back">戻る</button></a>


  <form action="create.php" method="POST" class="form-01">
    <section>
    
    <label for="">書籍コード</label>
    <input type="number" name="book_code" min="0" max="100000000" required>

    <label for="">書籍名</label>
    <input type="text" name="book_name" min="0" max="10000000" required>

    <label for="">値段</label>
    <input type="number" name="price" min="0" max="10000000" required>

    <label for="">在庫数</label>
    <input type="number" name="stock_quantity" min="0" max="10000000" required>

    <label for="">ジャンルコード</label>
    <select name="genre_code" id="">
      <option disabled selected value>選択してください</option>
      <!-- php記載 -->
      <?php
        foreach($genre_codes as $genre_code){
          echo"<option value = '{$genre_code}'>{$genre_code}</option>" ;
        }
      ?>
    </select>
</section>
    <div class="btn-02">
      <div>
      <button type="submit" name="submit" class="submit-btn">登録</button>
      </div>
    </div>
  </form>
 
</main>

<footer class="footer-02">
    &copy; 書籍管理アプリ All rights reserved.
  </footer>
  
  
</body>
</html>