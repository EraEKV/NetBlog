<?php 
	include "config/base_url.php"; 
	include "config/db.php"; 
	include "common/time.php"; 
	
	$q = "";
	$limit = 5;
	$page = $_GET["page"];
	$categor_id = "";

	$sql = "SELECT b.*, u.nickname, c.name FROM blogs b
	LEFT OUTER JOIN users u ON u.id=b.author_id
	LEFT OUTER JOIN categories c ON c.id=b.category_id";

	$sql_count = "SELECT CEIL(COUNT(*) / $limit) as total FROM blogs b
	LEFT OUTER JOIN users u ON u.id=b.author_id
	LEFT OUTER JOIN categories c ON c.id=b.category_id";

	if(isset($_GET["cat_id"])) {
		$categor_id = $_GET["cat_id"];
		$sql .= " WHERE b.category_id=$categor_id";
		$sql_count .= " WHERE b.category_id=$categor_id";
	}
	if(isset($_GET["q"])) {
		$q = strtolower($_GET["q"]);
		$sql .= " WHERE LOWER(b.title) LIKE ?
		OR LOWER(b.description) LIKE ?
		OR LOWER(u.nickname) LIKE ?
		OR LOWER(c.name) LIKE ?";

		$sql_count .= " WHERE LOWER(b.title) LIKE ?
		OR LOWER(b.description) LIKE ?
		OR LOWER(u.nickname) LIKE ?
		OR LOWER(c.name) LIKE ?";
	}

	if($q) {
		if(isset($_GET["page"]) && intval($_GET["page"])) {
			$skip = ($page - 1) * $limit;
			$sql .= " LIMIT $skip, $limit";
		} else {
			$sql .= "LIMIT $limit";
		}
		$param = "%$q%";

		$prep_count = mysqli_prepare($con, $sql_count);
		mysqli_stmt_bind_param($prep_count, "ssss", $param, $param, $param, $param);
		mysqli_stmt_execute($prep_count);
		$query_count = mysqli_stmt_get_result($prep_count);
		$count = mysqli_fetch_assoc($query_count);

		$search_prep = mysqli_prepare($con, $sql);
		mysqli_stmt_bind_param($search_prep, "ssss", $param, $param, $param, $param);
		mysqli_stmt_execute($search_prep);
		$query_blog = mysqli_stmt_get_result($search_prep);
	} else {
		if(isset($_GET["page"]) && intval($_GET["page"])) {
			$skip = ($page - 1) * $limit;
			$sql .= " LIMIT $skip, $limit";
		} else {
			$sql .= "LIMIT $limit";
		}

		$query_blog = mysqli_query($con, $sql);
		$query_count = mysqli_query($con, $sql_count);
		$count = mysqli_fetch_assoc($query_count);
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Главная</title>
    <?php include "views/head.php"; ?>
</head>
<body>

<?php include "views/header.php" ?>


<section class="container page">
	<div class="page-content">
			<h2 class="page-title">Блоги по самым разным темам</h2>
			<p class="page-desc">Популярные блоги различных авторов и популярных личностей.</p>
		<div class="blogs">
			<?php
				if(mysqli_num_rows($query_blog) > 0) {
					while($blog = mysqli_fetch_assoc($query_blog)) {
			?>
				<div class="blog-item">
					<a class="blog_link" href="<?=$BASE_URL?>/blog-details.php?id=<?=$blog["id"]?>">
						<img class="blog-item--img" src="<?=$BASE_URL?>/<?=$blog["img"]?>" alt="">
					</a>
					<div class="blog-info">
						<span class="link">
							<img src="<?=$BASE_URL?>/images/date.svg" alt="">
							<?=to_time_ago($blog["date"])?>
						</span>
						<span class="link">
							<img src="images/visibility.svg" alt="">
							21
						</span>
						<a class="link comments-link"  href="<?=$BASE_URL?>/blog-details.php?id=<?=$blog["id"]?>">
							<img src="images/message.svg" alt="">
							4
						</a>
						<a class="link forums-link" href="<?=$BASE_URL?>/index.php?page=1&cat_id=<?=$blog["category_id"]?>">
							<img src="images/forums.svg" alt="">
							<?=$blog["name"]?> 
						</a>
						<a class="user-link link" href="<?=$BASE_URL?>/profile.php?nickname=<?=$blog["nickname"]?>">
							<img src="images/person.svg" alt="">
							<?=$blog["nickname"]?> 
						</a>
					</div>
					<div class="blog-header">
							<h3><?=$blog["title"]?></h3>
					</div>
					<p class="blog-desc">
						<?=$blog["description"]?>
					</p>
					<br>
					<hr style="border: 1px solid #1d9bf0">
					<br>
				</div>
			<?php
					}
				} else {
			?>
				<h3>0 blogs</h3>
			<?php
				}
			?>

			<div class="pagination-header">
				<?php 
					$cat_str = "";
					$q_str = "";
					$page_str1 = "";
					$page_str2 = "";
					if($q) {
						$q_str = "&q=$q";
					}
					if($categor_id) {
						$cat_str = "&cat_id=$categor_id";
					}
					if($page != 1) { $page_str1 = $_GET["page"] - 1; ?>
						<a class="pagination-item"  href="<?="?page=$page_str1$q_str$cat_str"?>"><<</a>
				<?php
					} if($count["total"] != 1) { 
						for($i = 1; $i <= $count["total"]; $i++){ 
						if($_GET["page"] == $i) { ?>
						<a class="pagination-item active" href="<?="?page=$i$q_str$cat_str"?>"><?=$i?></a>
				<?php } else if($_GET["page"] != $i) { ?> 
						<a class="pagination-item" href="<?="?page=$i$q_str$cat_str"?>"><?=$i?></a>
				<?php } } } 
					if($page < $count["total"]) { $page_str2 = $_GET["page"] + 1; ?>
					 	<a href="<?="?page=$page_str2$q_str$cat_str"?>">>></a>
					<?php } ?>
			</div>
			
		</div>
	</div>
	<?php
		include "views/categories.php"
	?>
</section>	
</body>
</html>