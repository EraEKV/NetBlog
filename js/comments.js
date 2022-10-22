const authorBlog = document.body.dataset.authorid
const baseurl = document.body.dataset.baseurl
const blogId = document.body.dataset.blogid

const divComments = document.getElementById('comments')
const textarea = document.getElementById('textarea')
const addComment = document.getElementById('add-btn')

const currentUser = localStorage.getItem('user_id')

function getComments() {
    axios.get(`${baseurl}/api/comments/list.php?id=${blogId}`).then(res => {
        console.log(res.data);
        showComments(res.data);
    })
}
getComments()

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

function showComments(comments) {
    let divHTML = `<h2> ${comments.length} комментария </h2>`
    for(let i = comments.length - 1; i >= 0; i--) {
        let deleteButton = ``
        if(comments[i]["author_id"] == currentUser || currentUser == authorBlog) {
            deleteButton = `<span onclick="removeComment(${comments[i]['id']})"> Удалить </span>`
        }
        divHTML += `
        <div class="comment">
            <div class="comment-header">
                <a href="${baseurl}/profile.php?nickname=${comments[i]["nickname"]}">
                    <img class="avatar" src="${comments[i]["ava"]}" alt="">
                    <p>${comments[i]["full_name"]}</p>
                </a>
                ${deleteButton}
            </div>
            <div class="blog-desc">
                <p>${comments[i]["text"]}</p>
            </div>
        </div>
        `
    }

    divComments.innerHTML = divHTML
}

if(currentUser != null) {
    addComment.onclick = function() {
        axios.post(`${baseurl}/api/comments/add.php`, {
            text:textarea.value,
            blog_id:blogId
        }).then(res => {
            getComments()
            textarea.value = ""
        })
    }
}


function removeComment(id) {
    axios.delete(`${baseurl}/api/comments/delete.php?id=${id}`).then(res => {
        getComments()
        console.log("gotcha");
    })
} 

