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

const ava = axios.get(`${baseurl}/${avatar}`)
console.log(ava);

function showComments(comments) {
    let divHTML = `<h2> ${comments.length} комментария </h2>`
    for(let i in comments) {
        let deleteButton = ``
        if(comments[i]["author_id"] == currentUser || currentUser == authorBlog) {
            deleteButton = `<span onclick="removeComment(${comments[i]["id"]})"> Удалить коментарий </span>`
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
            <div class="flex-comment">
                <p>${comments[i]["text"]}</p>
            </div>
        </div>
        `
    }

    divComments.innerHTML = divHTML
}


addComment.onclick = function() {
    axios.post(`${baseurl}/api/comments/add.php`, {
        text:textarea.value,
        blog_id:blogId
    }).then(res => {
        getComments()
        textarea.value = ""
    })
}


function removeComment(id) {
    axios.delete(`${baseurl}/api/comments/delete.php?id=${id}`).then(res => {
        getComments()
    })
} 

