<?php
function is_empty_val($str){
	if (!empty($_POST[$str])){return $_POST[$str];}
	return null;
}
$user="solopov";
$pass="neto0794";
$filter_on=false;
try {
	$pdo = new PDO('mysql:host=localhost;dbname=global;charset=utf8', $user, $pass);	
	$col_name = $pdo->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'books' AND table_schema = 'global'");
	$data =	$pdo->query("SELECT * FROM books");		
	}catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
	}
	
if (!(empty($_POST))){
	$pdo = new PDO('mysql:host=localhost;dbname=global;charset=utf8', $user, $pass);
	//$prep_q = $pdo->prepare('SELECT * FROM books WHERE LOCATE(? , author) AND LOCATE(? , name) AND LOCATE(? , isbn)');
	//Сумма фильтров
	$prep_q = $pdo->prepare('SELECT * FROM books WHERE LOCATE(? , author) UNION SELECT * FROM books WHERE LOCATE(? , name) UNION SELECT * FROM books WHERE LOCATE(? , isbn)');	
	$author=is_empty_val('author');
	$name=is_empty_val('name');
	$isbn=is_empty_val('ISBN');
	/*
	$author=$_POST['author'];
	$name=$_POST['name'];
	$isbn=$_POST['ISBN'];
	*/
	$prep_q->execute(array($author,$name, $isbn));
	$filter_on=true;
}	
	
	
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <style>
	table {
    border-collapse: collapse;
	
	}
	th{
	background: gray;
	}
	td, th{
    border: 1px solid black;
	}
</style>  
</head>
<body>
<form action="index.php" method="post">
	<?php if (!($filter_on)){ ?>
	<input name="ISBN" type="text" placeholder="ISBN">
	<input name="name" type="text" placeholder="Название книги">
	<input name="author" type="text" placeholder="Автор">
	
	<?php } else{ ?>
	<input name="ISBN" type="text" placeholder="ISBN" value="<?php echo $isbn; ?>">
	<input name="name" type="text" placeholder="Название книги" value="<?php echo $name; ?>">
	<input name="author" type="text" placeholder="Автор"value="<?php echo $author; ?>">
	<?php }; ?>
	
	
	<button type="submit" name="search" value="search">Найти</button>
	<table>	
		<tr>
			<?php
			while ($row=$col_name->fetch(PDO::FETCH_ASSOC) ){
				foreach($row as $key=>$value){ ?>
				<th> <?php echo $value; ?></th>
			<?php } ?>	
		<?php } ?>		
		</tr>
		<?php
			if (!($filter_on)){
			while ($row=$data->fetch(PDO::FETCH_ASSOC) ){ 
		?>
				<tr>
				<?php foreach($row as $key=>$value){ ?>
				<td> <?php echo $value ."</br>"; ?></td>
		<?php } ?>	
				</tr>
		<?php } ?>	
		<?php }else { 			
			while ($row=$prep_q->fetch(PDO::FETCH_ASSOC) ){
			?>
				<tr>
				
				<?php foreach($row as $key=>$value){ ?>
				<td> <?php echo($value) ."</br>"; ?></td>
			<?php } ?>	
			</tr>
		<?php } ?>	
	<?php } ?>	
		
		
		
	</table>
  </form>
</body>
</html>
