

const baseurl = document.body.dataset.baseurl
const path1 = `${baseurl}/images/lightning.svg`
const path2 = `${baseurl}/images/lightning_solid.svg`
let likeImg = document.querySelectorAll(".like-img")

function likePress(index) {
    localStorage.setItem("scrollPos", window.scrollY)
    if(likeImg[index].src == path1) {
        likeImg[index].src = path2
    } else {
        likeImg[index].src = path1
    }
}

