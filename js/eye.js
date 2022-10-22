
eye1 = document.querySelector(".eye1")
eye2 = document.querySelector(".eye2")
path = "images/eye.svg"
eye1.src = path
eye2.src = path

eye1.addEventListener("click", function showPassword() {
    const input = document.querySelector(".pass1")
    if(input.type == "password") {
        input.type = "text"
        eye1.src = "images/eye-closed.svg"
    }
    else {
        input.type = "password"
        eye1.src = path  
    }
})

eye2.addEventListener("click", function showPassword() {
    const input = document.querySelector(".pass2")
    if(input.type == "password") {
        input.type = "text"
        eye2.src = "images/eye-closed.svg"
    }
    else {
        input.type = "password"
        eye2.src = path  
    }
})

