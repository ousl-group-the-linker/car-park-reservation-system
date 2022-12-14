/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*************************************************!*\
  !*** ./resources/components/sidebar/sidebar.js ***!
  \*************************************************/
function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
  var expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
$(document).on("click", ".sidebar-trigger-btn", function () {
  var parent = $(".side-bar").parent();
  parent.toggleClass("expanded");
  setCookie("sidebar-state", parent.hasClass("expanded"));
});
/******/ })()
;