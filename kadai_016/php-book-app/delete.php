<?php
  $dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
  $user = 'root';
  $password = 'root';

  try{
    $pdo = new PDO($dsn,$user,$password);

    $sql_delete = 'DELETE FROM books where id = :id';

    $stmt_delete = $pdo -> prepare($sql_delete);

    $stmt_delete ->bindValue(':id',$_GET['id'],PDO::PARAM_INT);

    $stmt_delete->execute();

    $count = $stmt_delete->rowCount();
 
     $message = "商品を{$count}件削除しました。";
 
     // 商品一覧ページにリダイレクトさせる（同時にmessageパラメータも渡す）
     header("Location: read.php?message={$message}");
  }catch (PDOException $e) {
    exit($e->getMessage());
}

?>