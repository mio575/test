<html> 

 
<form method="POST" action="mission_5-1.php"> 
 
<?php 
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql = "CREATE TABLE IF NOT EXISTS info" 
 ." (" 
 . "id INT AUTO_INCREMENT PRIMARY KEY," 
 . "name char(32)," 
  . "comment TEXT," 
  . "date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP," 
  . "password char(30)" 
  .");"; 
     
$stmt = $pdo->query($sql); 
 
 
 
$editflag=1; 
 
//削除の時 
if( !empty($_POST["delnum"]) && !empty($_POST["delpass"])){ 
  $delnum=intval($_POST["delnum"]);   
  $delpass=$_POST["delpass"]; 
  $id=$delnum; 
  $sql = 'SELECT * FROM info'; 
  $stmt = $pdo->query($sql); 
  $result = $stmt->fetchAll(); 
  foreach ($result as $row){ 
      if($delnum==intval($row['id'])&&strcmp($delpass,$row['password'])==0){        
        $sql = 'delete from info where id=:id'; 
        $stmt = $pdo->prepare($sql); 
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute(); 
      }elseif($delnum==intval($row['id'])&&strcmp($delpass,$row['password'])!=0){ 
        echo "パスワードが違います<br>"; 
      }   
  } 
} 
 
  //編集番号に該当する行を取り出す 
if( !empty($_POST["editnum"]) && !empty($_POST["editpass"])){ 
  $editnum=intval($_POST["editnum"]); 
  $editpass=$_POST["editpass"]; 
   
 
  $editnum=intval($_POST["editnum"]); 
  $sql = 'SELECT * FROM info'; 
  $stmt = $pdo->query($sql); 
  $result = $stmt->fetchAll(); 
  foreach ($result as $row){ 
      if($editnum==intval($row['id'])&&strcmp($editpass,$row['password'])==0){ 
        $editflag=-1; 
        $editname=$row['name']; 
        $editcomment=$row['comment']; 
      }elseif($editnum==intval($row['id'])&&strcmp($editpass,$row['password'])!=0){ 
        echo "パスワードが違います<br>"; 
      } 
  } 
} 
 
//編集した内容をファイルに上書き 
if(!empty($_POST["SendEditname"])&& !empty($_POST["SendEditcomment"]) &&!empty($_POST["SendEditpass"])){ 
  $SendEditname=$_POST["SendEditname"]; 
  $SendEditcomment=$_POST["SendEditcomment"]; 
  $SendEditnum=intval($_POST["SendEditnum"]); 
  $SendEditpass=$_POST["SendEditpass"]; 
 
  $id = $SendEditnum; //変更する投稿番号 
  $name = $SendEditname; 
  $comment = $SendEditcomment; //変更したい名前、変更したいコメントは自分で決めること 
 
  $sql = 'SELECT * FROM info'; 
  $stmt = $pdo->query($sql); 
  $result = $stmt->fetchAll(); 
  foreach ($result as $row){ 
      if($SendEditnum==intval($row['id'])&&strcmp($SendEditpass,$row['password'])==0){ 
        $name = $SendEditname; 
       $comment = $SendEditcomment; 
        $sql = 'update info set name=:name,comment=:comment where id=:id'; 
        $stmt = $pdo->prepare($sql); 
        $stmt->bindParam(':name', $name, PDO::PARAM_STR); 
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR); 
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute(); 
      }elseif($SendEditnum==intval($row['id'])&&strcmp($SendEditpass,$row['password'])!=0){ 
        echo "パスワードが違います<br>"; 
      } 
  } 
} 
 
 
 
//ここからフォーム 
if($editflag==1){ //通常モード  
?> 
  <input type="text" name="name" value="" placeholder="名前"><br> 
  <input type="text" name="comment" value="" placeholder="コメント"><br> 
  <input type="text" name="password" value="" placeholder="パスワード"><br> 
  <input type="submit" value="送信"><br> 
 
   
   
<?php 
}else{ //編集用フォーム 
?>   
   
  <input type="text" name="SendEditname" value="<?php echo $editname;?>" placeholder="編集する名前"><br> 
  <input type="text" name="SendEditcomment" value="<?php echo $editcomment;?>" placeholder="編集するコメント"><br> 
  <input type="text" name="SendEditpass" value="" placeholder="パスワード"> 
  <input type="hidden" name="SendEditnum" value="<?php echo $editnum;?>"> 
  <input type="submit" value="編集文送信"><br> 
 
<?php  
} 
?> 
 
<p> 
  <input type="text" name="delnum" value="" placeholder="削除対象番号"><br> 
  <input type="text" name="delpass" value="" placeholder="パスワード">      
  <input type="submit" value="削除"> 
</p>     
 
<p> 
  <input type="text" name="editnum" value="" placeholder="編集対象番号"><br> 
  <input type="text" name="editpass" value="" placeholder="パスワード">      
  <input type="submit" value="編集"> 
</p>     
 
</form> 
 
<?php  
   
//通常モード処理 
  if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){ 
         
    $sql = $pdo -> prepare("INSERT INTO info (name, comment, date, password) VALUES (:name, :comment, :date, :password)"); 
    $sql -> bindParam(':name', $name, PDO::PARAM_STR); 
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR); 
    $sql -> bindParam(':date', $date, PDO::PARAM_STR); 
    $sql -> bindParam(':password', $password, PDO::PARAM_STR); 
     
    $name=$_POST["name"]; 
    $comment=$_POST["comment"]; 
    $date=date( "Y-m-d H:i:s" ); 
    $password=$_POST["password"]; 
     
    $sql -> execute(); 
 
  } 
  
 
 
  
 
 
  //フォーム下に表示するやつ 
 
  $sql = 'SELECT * FROM info'; 
  $stmt = $pdo->query($sql); 
  $results = $stmt->fetchAll(); 
  foreach ($results as $row){ //$rowの中にはテーブルのカラム名が入る 
   echo $row['id'].' '; 
   echo $row['name'].' '; 
    echo $row['comment'].' '; 
    echo $row['date'].'<br>'; 
   echo "<hr>"; 
  } 
 
?> 
 
</html>