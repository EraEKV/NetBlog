<?php 
	include "config/base_url.php"; 
	include "config/db.php"; 
	include "common/time.php"; 
	
	$q = "";
	$limit = 3;
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
	if(isset($_GET["q"]) && $_GET["q"] > 0) {
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
			header("Location:$BASE_URL/index.php?page=1");
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
	<link rel="stylesheet" href="style/slick/slick.css">
	<link rel="stylesheet" href="slick/slick.css"/>
	<link rel="stylesheet" href="slick/slick-theme.css"/>
    <?php include "views/head.php"; ?>
</head>
<body data-baseurl="<?=$BASE_URL?>">

<?php include "views/header.php" ?>

<div class="slider">
	<?php $query_categ = mysqli_query($con, "SELECT * FROM categories"); ?>
	<div class="category-slider">
		<?php while($category = mysqli_fetch_assoc($query_categ)) { ?>
			<div class="slide">
				<a href="?cat_id=<?=$category["id"]?>&page=1"><?=$category["name"]?></a>
			</div>
		<?php } ?>
	</div>
</div>

<section class="container page adaptive">
	<div class="page-content">
			<?php if(isset($_GET["cat_id"]) || isset($_GET["q"])) { ?>
				<h2 style="display: none;" class="page-title">Блоги по <span id='output'></span> </h2>
			<?php } else { ?>
				<h2 class="page-title">Блоги по <span id='output'></span> </h2>
				<p class="page-desc">Популярные блоги различных авторов и популярных личностей.</p>
			<?php } ?>
		<div class="blogs">
			<?php
				if(mysqli_num_rows($query_blog) > 0) {
					$number = 0;
					while($blog = mysqli_fetch_assoc($query_blog)) {
						$comments = mysqli_query($con, "SELECT CEIL(COUNT(*)) as total FROM comments c WHERE c.blog_id=".$blog["id"]);
						$comment = mysqli_fetch_assoc($comments);
			?>
				<div class="blog-item">	
					<?php if(isset($blog["img"]) && strlen($blog["img"]) > 0) { ?>
						<a class="blog_link" href="<?=$BASE_URL?>/blog-details.php?id=<?=$blog["id"]?>">
							<img class="blog-item--img" src="<?=$BASE_URL?>/<?=$blog["img"]?>" alt="">
						</a>
					<?php }
					$url = "page=".$_GET["page"];
					if(isset($_GET["q"])) {
						$url .= "&q=".$_GET["q"];
					}
					if(isset($_GET["cat_id"])) {
						$url .= "&cat_id=".$_GET["cat_id"];
					}
					?>
					<div class="blog-info">
						<span class="link date-link">
							<img src="<?=$BASE_URL?>/images/date.svg" alt="">
							<?=to_time_ago($blog["date"])?>
						</span>
						<a <?php if(isset($_SESSION["user_id"])) { ?> onclick="likePress(<?=$number?>)"  href="<?=$BASE_URL?>/api/like/edit.php?user_id=<?=$_SESSION["user_id"]?>&blog_id=<?=$blog["id"]?>&<?=$url?>"  <?php } else { ?> onclick="openModalWindow()" <?php } ?> class="link like-link">
						<?php
								$number++;
								$total = 0;
								if(isset($blog["id"]) && intval($blog["id"])) {
									$check = mysqli_prepare($con, "SELECT * FROM likes WHERE blog_id=?");
									mysqli_stmt_bind_param($check, "i", $blog["id"]);
									mysqli_stmt_execute($check);
									$likes = mysqli_stmt_get_result($check);
									$img = '<img class="like-img" src="images/lightning.svg">';

									if(mysqli_num_rows($likes) > 0) {
											while($row = mysqli_fetch_assoc($likes)) {
												$total++;
												if(isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $row["like_author"]) {
													$img = '<img class="like-img" src="images/lightning_solid.svg">';
												} 
											
										} 
									} 
									echo $img;
								}
							?>
							<?= $total ?>
						</a>
						<a class="link comments-link" href="<?=$BASE_URL?>/blog-details.php?id=<?=$blog["id"]?>">
							<img src="images/message.svg" alt="">
							<?=$comment["total"]?>
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
							<h3>
								<p class="link date-link adaptive"><?=to_time_ago_adaptive($blog["date"])?></p>
								<?=$blog["title"]?>
							</h3> 
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
				<h3>0 блогов</h3>
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
	<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="slick/slick.min.js"></script>
	<script src="js/slick.js"></script>
	<script src="js/textwriter.js"></script>
	<script src="js/likeScript.js"></script>
	<script>
		let Y = localStorage.getItem("scrollPos")
		window.scrollTo(0, Y)
		localStorage.removeItem("scrollPos")
		console.log("removed srcollPos");
	</script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.0/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
</body>
</html>