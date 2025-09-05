/*!

=========================================================
* Soft UI Dashboard Tailwind - v1.0.5
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard-tailwind
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (site.license)

* Coded by www.creative-tim.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/
var page = window.location.pathname.split("/").pop().split(".")[0];
var aux = window.location.pathname.split("/");
var to_build = (aux.includes('templates') ? '../' : './');
var root = window.location.pathname.split("/")
if (!aux.includes("pages")) {
  page = "dashboard";
}

loadStylesheet("templates/assets/css/perfect-scrollbar.css");
loadJS("templates/assets/js/perfect-scrollbar.js", true);

if (document.querySelector("nav [navbar-trigger]")) {
  loadJS("templates/assets/js/navbar-collapse.js", true);
}

if (document.querySelector("[data-target='tooltip']")) {
  loadJS("templates/assets/js/tooltips.js", true);
  loadStylesheet("templates/assets/css/tooltips.css");
}

if (document.querySelector("[nav-pills]")) {
  loadJS("templates/assets/js/nav-pills.js", true);
}

if (document.querySelector("[dropdown-trigger]")) {
  loadJS("templates/assets/js/dropdown.js", true);

}

if (document.querySelector("[fixed-plugin]")) {
  loadJS("templates/assets/js/fixed-plugin.js", true);
}

if (document.querySelector("[navbar-main]")) {
  loadJS("templates/assets/js/sidenav-burger.js", true);
  loadJS("templates/assets/js/navbar-sticky.js", true);
}

if (document.querySelector("canvas")) {
  loadJS("templates/assets/js/chart-1.js", true);
  loadJS("templates/assets/js/chart-2.js", true);
}

function loadJS(FILE_URL, async) {
  let dynamicScript = document.createElement("script");

  dynamicScript.setAttribute("src", FILE_URL);
  dynamicScript.setAttribute("type", "text/javascript");
  dynamicScript.setAttribute("async", async);

  document.head.appendChild(dynamicScript);
}

function loadStylesheet(FILE_URL) {
  let dynamicStylesheet = document.createElement("link");

  dynamicStylesheet.setAttribute("href", FILE_URL);
  dynamicStylesheet.setAttribute("type", "text/css");
  dynamicStylesheet.setAttribute("rel", "stylesheet");

  document.head.appendChild(dynamicStylesheet);
}
