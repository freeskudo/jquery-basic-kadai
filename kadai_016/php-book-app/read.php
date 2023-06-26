<!-- read.php -->
<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = 'root';

try {
  $pdo = new PDO($dsn,$user,$password);

  // 昇順、降順、検索について 
  if(isset($_GET['order'])){
    $order = $_GET['order'];
  } else {
    $order = NULL;
  }

  if(isset($_GET['keyword'])){
    $keyword = $_GET['keyword'];
  } else {
    $keyword = NULL ;
  }


  // 並び替え＋検索
  if($order === 'asc'){
    $sql_select = 'SELECT * FROM books where book_name like :keyword order by updated_at ASC'; 
  } else {
    $sql_select = 'SELECT * FROM books where book_name like :keyword order by updated_at DESC';
  }

  $stmt_select = $pdo -> prepare($sql_select);

  $PM = "%{$keyword}%";

  $stmt_select -> bindValue(':keyword', $PM, PDO::PARAM_STR);

  $stmt_select -> execute();
  

  $books = $stmt_select -> fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    exit ($e->getMessage());
}

?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>商品一覧</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <a href="index.php"><header id="header">書籍管理アプリ</header></a>
  <main>
  <section id="sec-02">
    <h1>商品一覧</h1>
    <?php
      if(isset($_GET['message'])){
        echo"{$_GET['message']}";
      }
    ?>
  </section>
  <section id="sec-03">
    <div class="sec-04">
      <div>
       <a href="read.php?order=asc&keyword=<?= $keyword ?>">
        <img src="images/asc.png" alt="昇順" class="asc-icon">
        </a>
        <a href="read.php?order=desc&keyword=<?= $keyword ?>">
        <img src="images/desc.png" alt="降順" class="desc-icon">
        </a>
      </div>
      <div class="sec-05">
       <form action="read.php" method="get" class="search">
        <input type="hidden" name="order" value="<?= $order ?>">
        <input type="text" class="search-box" placeholder="商品名で検索" name="keyword" value="<?= $keyword ?>">
       </form>
      </div>
    </div>
    <div>
    <a href="create.php"><button class="btn-02">商品登録</button></a>
    </div>
  </section>
  
  <table class="table-book">
    <tr>
      <th>書籍コード</th>
      <th>書籍名</th>
      <th>単価</th>
      <th>在庫数</th>
      <th>ジャンルコード</th>
      <th>編集</th>
      <th>削除</th>
    </tr>
    <?php
    foreach($books as $book) {
      $table_row ="
      <tr>
      <td>{$book['book_code']}</td>
      <td>{$book['book_name']}</td>
      <td>{$book['price']}</td>
      <td>{$book['stock_quantity']}</td>
      <td>{$book['genre_code']}</td>
      <td><a href='update.php?id={$book['id']}'><img src='images/edit.png' alt='編集' class='edit-icon'></a></td>
      <td><a href='delete.php?id={$book['id']}'><img src='images/delete.png' alt='編集' class='delete-icon'></a></td>

      </tr>
      ";
      echo $table_row;
    }
    ?>
  </table>
  </main>

  <footer class="footer-02">
    &copy; 書籍管理アプリ All rights reserved.
  </footer>
  


</body>
</html>