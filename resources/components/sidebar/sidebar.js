function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

$(document).on("click", ".sidebar-trigger-btn", function () {
    let parent = $(".side-bar").parent();
    parent.toggleClass("expanded");
    setCookie("sidebar-state", parent.hasClass("expanded"));
})
