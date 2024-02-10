// pharvaris-deflate-hcp JS

var changeBgImage = function () {

    var idBackgnd = document.getElementById("redselect");
    /* var contain1 = document.getElementById('listswap-container-1'); */

    idBackgnd.style.backgroundImage = "url('/wp-content/themes/conall-child/images/living-backg-1-1920x1200.jpg')";

    if (document.getElementById("living-hae-impacts-row").value === "occupational") {
        idBackgnd.style.backgroundImage = "url('/wp-content/themes/conall-child/images/living-backg-2-1920x1200.jpg')";
    } else if (document.getElementById("living-hae-impacts-row").value === "social") {
        idBackgnd.style.backgroundImage = "url('/wp-content/themes/conall-child/images/living-backg-3-1920x1200.jpg')";
    } else if (document.getElementById("living-hae-impacts-row").value === "familial") {
        idBackgnd.style.backgroundImage = "url('/wp-content/themes/conall-child/images/living-backg-4-1920x1200.jpg')";
    } else {
        idBackgnd.style.backgroundImage = "url('/wp-content/themes/conall-child/images/living-backg-1-1920x1200.jpg')";
    }
    /* idBackgnd.style.backgroundColor = document.getElementById("living-hae-impacts-row").value; */

};

/* function showDiv(select) {
    if (select.value == "emotional") {
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
} */