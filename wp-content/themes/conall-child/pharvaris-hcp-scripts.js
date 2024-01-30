// pharvaris-deflate-hcp JS

/* const redselect = document.querySelector('#living-hae-impacts-row');

redselect.addEventListener('change', function () {
    this.dataset.background = this.value;
}); */

var selectEl = document.getElementById("redselect");

selectEl.onclick = function () {

    if (this.value == "occupational") {
        document.getElementById("living-hae-impacts-row").style.backgroundImage = "url(/wp-content/themes/conall-child/images/living-backg-2-1920x1200.jpg)";
    }
    else if (this.value == "social") {
        document.getElementById("living-hae-impacts-row").style.backgroundImage = "url(/wp-content/themes/conall-child/images/living-backg-3-1920x1200.jpg)";
    }
    else if (this.value == "familial") {
        document.getElementById("living-hae-impacts-row").style.backgroundImage ="url(/wp-content/themes/conall-child/images/living-backg-4-1920x1200.jpg)";
    }
    else {
        document.getElementById("living-hae-impacts-row").style.backgroundImage = "url(/wp-content/themes/conall-child/images/living-backg-1-1920x1200.jpg)";
    }
}

function showDiv(select){
    if (select.value =="emotional") {
       document.getElementById('listswap-container-1').style.display = "block";
    }
    else {
        document.getElementById('listswap-container-1').style.display = "none";
    }
    if (select.value == "occupational") {
        document.getElementById('listswap-container-2').style.display = "block";
    }
    else {
        document.getElementById('listswap-container-2').style.display = "none";
    }
    if (select.value == "social") {
        document.getElementById('listswap-container-3').style.display = "block";
    }
    else {
        document.getElementById('listswap-container-3').style.display = "none";
    }
    if (select.value == "familial") {
        document.getElementById('listswap-container-4').style.display = "block";
    }
    else {
        document.getElementById('listswap-container-4').style.display = "none";
    }
}
