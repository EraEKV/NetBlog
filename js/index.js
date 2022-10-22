const baseurl = document.body.dataset.baseurl
const blogsDiv = document.querySelector(".blogs")
const currentUserId = localStorage.getItem("user_id")
const nickname = $_GET["nickname"]

function timeSince(date) {

    var seconds = Math.floor((new Date() - date) / 1000);
  
    var interval = seconds / 31536000;
  
    if (interval > 1) {
      return Math.floor(interval) + " год назад";
    }
    interval = seconds / 2592000;
    if (interval > 1) {
      return Math.floor(interval) + " месяцев назад";
    }
    interval = seconds / 86400;
    if (interval > 1) {
      return Math.floor(interval) + " дней назад";
    }
    interval = seconds / 3600;
    if (interval > 1) {
      return Math.floor(interval) + " часов назад";
    }
    interval = seconds / 60;
    if (interval > 1) {
      return Math.floor(interval) + " минут назад";
    }
    return Math.floor(seconds) + " секунд назад";
}

function getBlogs() {
    axios.get(`${baseurl}/api/blog/index_list.php`).then(res => {
        showBlogs(res.data)
        // console.log(res.data)
    })
}
getBlogs()

function showBlogs(blogs) {
    let divHTML = ""
    if(blogs.length == 0) {
        blogsDiv.innerHTML = `<h1>0 блогов</h1>`
    } else {
        for(let i = blogs.length - 1; i >= 0; i--) {
            
            divHTML += `
            <div class="blog-item">
                <a class="blog_link" href="${baseurl}/blog-details.php?id=${blogs[i]['id']}">
                    <img class="blog-item--img" src="${baseurl}/${blogs[i]['img']}" alt="">
                </a>
                <div class="blog-info">
                    <span class="link">
                        <img src="images/date.svg" alt="">
                        ${timeSince(Date.parse(blogs[i]['date']))}
                    </span>
                    <span class="link">
                        <img src="images/visibility.svg" alt="">
                        21
                    </span>
                    <a class="link comments-link" href="${baseurl}/blog-details.php?id=${blogs[i]['id']}">
                        <img src="images/message.svg" alt="">
                        4
                    </a>
                    <span class="link forums-link" href="${baseurl}/index.php?page=1&cat_id=${blogs[i]["name"]}">
                        <img src="images/forums.svg" alt="">
                        ${blogs[i]["name"]}
                    </span>
                    <a class="link">
                        <img src="images/person.svg" alt="">
                        ${blogs[i]["nickname"]}
                    </a>
                </div>
                <div class="blog-header">
                    <h3>
                        ${blogs[i]["title"]}
                    </h3>
                </div>
                <p class="blog-desc">
                    ${blogs[i]["description"]}
                </p>
                <br>
                <hr style="border: 1px solid #1d9bf0">
                <br>
            </div> 
            `                                                                           
        }
    }
    blogsDiv.innerHTML = divHTML
}

let a = `
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
        <span class="link like-link">
            <img src="images/lightning.svg" alt="">
            21
        </span>
        <a class="link comments-link" onclick="likeScript()" href="<?=$BASE_URL?>/blog-details.php?id=<?=$blog["id"]?>">
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
`