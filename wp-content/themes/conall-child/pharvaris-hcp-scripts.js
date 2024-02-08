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
        document.getElementById("living-hae-impacts-row").style.backgroundImage = "url(/wp-content/themes/conall-child/images/living-backg-4-1920x1200.jpg)";
    }
    else {
        document.getElementById("living-hae-impacts-row").style.backgroundImage = "url(/wp-content/themes/conall-child/images/living-backg-1-1920x1200.jpg)";
    }
}

function showDiv(select) {
    var pic1 = document.getElementById('listswap-container-1');
    if (!pic1.ariaSelected.value || pic1.select.value == "emotional") {
        pic1.style.display = "block";
    }
    else {
        pic1.style.display = "none";
    }
    var pic2 = document.getElementById('listswap-container-2');
    if (!pic2.ariaSelected.value || pic2.select.value == "occupational") {
        pic2.style.display = "block";
    }
    else {
        pic2.style.display = "none";
    }
    var pic3 = document.getElementById('listswap-container-3');
    if (!pic3.ariaSelected.value || pic3.select.value == "social") {
        pic3.style.display = "block";
    }
    else {
        pic3.style.display = "none";
    }
    var pic4 = document.getElementById('listswap-container-4');
    if (!pic4.ariaSelected.value || pic4.select.value == "familial") {
        pic4.style.display = "block";
    }
    else {
        pic4.style.display = "none";
    }
}