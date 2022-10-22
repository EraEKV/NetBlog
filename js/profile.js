

const baseurl = document.body.dataset.baseurl
const blogsDiv = document.querySelector(".blogs")
const countPosts = document.querySelector(".posts-desc")
const countPostsAdaptive = document.querySelector(".posts-desc.adaptive")
const currentUserId = localStorage.getItem("user_id")
let nickname = localStorage.getItem("nickname")

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

function timeSinceAdaptive(date) {

    var seconds = Math.floor((new Date() - date) / 1000);
  
    var interval = seconds / 31536000;
  
    if (interval > 1) {
      return Math.floor(interval) + " г. назад";
    }
    interval = seconds / 2592000;
    if (interval > 1) {
      return Math.floor(interval) + " мес. назад";
    }
    interval = seconds / 86400;
    if (interval > 1) {
      return Math.floor(interval) + " дн. назад";
    }
    interval = seconds / 3600;
    if (interval > 1) {
      return Math.floor(interval) + " час. назад";
    }
    interval = seconds / 60;
    if (interval > 1) {
      return Math.floor(interval) + " мин. назад";
    }
    return Math.floor(seconds) + " сек. назад";
}


let url = window.location.href
let newNickname = url.split("=")
if(newNickname[1]) {
    nickname = newNickname[1]
    getBlogs()
}

function getBlogs() {
    axios.get(`${baseurl}/api/blog/list.php?nickname=${nickname}`).then(res => {
        showBlogs(res.data)
    })
}   

const path1 = `${baseurl}/images/lightning.svg`
const path2 = `${baseurl}/images/lightning_solid.svg`
let path = ""

let number = 0;

function showBlogs(blogs) {
    let divHTML = ""
    if(blogs.length == 0) {
        blogsDiv.innerHTML = `<h1>0 блогов</h1>`
    } else {
        for(let i = blogs.length - 1; i >= 0; i--) {
            let dropdown = ""
            let img = ""
            
            if(currentUserId == blogs[i]["author_id"]) {
                dropdown = `
                <span class="link dropdown-link">
                <img src="images/dots.svg" alt="">
                Еще

                        <ul class="dropdown">
                        <li><a href="${baseurl}/editblog.php?id=${blogs[i]['id']}">Редактировать</a> </li>
                        <li><a href="${baseurl}/api/blog/delete_blog.php?id=${blogs[i]['id']}" class="danger">Удалить</a></li>
                        </ul>
                    </span>
                    `
            }

            if(blogs[i]["img"] != "") {
                img = `
                <a class="blog_link" href="${baseurl}/blog-details.php?id=${blogs[i]['id']}">
                <img class="blog-item--img" src="${baseurl}/${blogs[i]['img']}" alt="">
                </a>
                `
            }

            divHTML += `
            <div class="blog-item data-blogid=${blogs[i]["id"]}">
                ${img}
                <div class="blog-info">
                    <span class="link date-link">
                        <img src="images/date.svg" alt="">
                        ${timeSince(Date.parse(blogs[i]['date']))}
                    </span>
                    <span data-blogid="${blogs[i]["id"]}" onclick="editLike(${blogs[i]["id"]}, ${number})" class="link like-link">
                        ${checkLike(blogs[i]["id"], number)}   
                    </span>
                    <a class="link comments-link" href="${baseurl}/blog-details.php?id=${blogs[i]['id']}">
                        ${checkComment(blogs[i]["id"], number)}
                    </a>
                    <span class="link">
                        <img src="images/forums.svg" alt="">
                        ${blogs[i]["name"]}
                    </span>
                    <a class="link">
                        <img src="images/person.svg" alt="">
                        ${nickname}
                    </a>
                </div>
                <div class="blog-header">
                    <h3>
                        <p class="link date-link adaptive">${timeSinceAdaptive(Date.parse(blogs[i]["date"]))}</p>
                        ${blogs[i]["title"]}
                    </h3> 
                    ${dropdown}
                </div>
                <p class="blog-desc">
                    ${blogs[i]["description"]}
                </p>
                <br>
                <hr style="border: 1px solid #1d9bf0">
                <br>
            </div> 
            `      
            number++                                                                     
        }
    }
    countPosts.innerHTML = blogs.length + " постов за всё время"
    countPostsAdaptive.innerHTML = blogs.length + " постов за всё время"
    blogsDiv.innerHTML = divHTML
    
}


let checkComment = (blog_id, index) => {
    axios.get(`${baseurl}/api/comments/check.php?blog_id=${blog_id}`).then(comment => {
        totalComment(comment.data, index)
    })
}

let totalComment = (data, index) => {
    let total = 0;
    total = data

    let commentSpan = document.querySelectorAll(".comments-link")

    commentSpan[index].innerHTML = `
        <img src="images/message.svg" alt="">
        ${total}
    ` 
}

let checkLike = (blog_id, index) => {
    axios.get(`${baseurl}/api/like/check.php?blog_id=${blog_id}`).then(like => { 
        totalLike(like.data, index)
    })
}

let totalLike = (data, index) => {
    let total = 0;
    total = data.length

    let likeSpan = document.querySelectorAll(".like-link")

    path = path1

    for(let j of data) {
        if(j['like_author'] == currentUserId) {
            path = path2
        } 
    }

    likeSpan[index].innerHTML = `
        <img src="${path}" id="like">
        ${total}
    `
}


function editLike(blog_id, index) {
    let likeImg = document.querySelectorAll("#like") 

    axios.get(`${baseurl}/api/like/edit.php?user_id=${currentUserId}&blog_id=${blog_id}&profile=1`)

    if(likeImg[index].src == path1) {
        likeImg[index].src = path2
    } else {
        likeImg[index].src = path1
    }

    checkLike(blog_id, index)
}