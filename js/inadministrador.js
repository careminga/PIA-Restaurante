document.addEventListener("DOMContentLoaded", function (){
    sessionStorage.getItem("user");
    if(user !== "admin")
    {
        window.location.href = "./iniciodesesion.html"
    }
})