if(!localStorage.getItem("nickname") || !localStorage.getItem("user_id")) {
    const div = document.getElementById("modal_window")

    function openModalWindow() {
        div.classList.add("active")
    }

    function closeModalWindow() {
        div.classList.remove("active")
    }
}