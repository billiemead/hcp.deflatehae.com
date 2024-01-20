// pharvaris-deflate-hcp JS


//Select the elements you want inside
const divs = document.querySelectorAll("#home-tradeoff-row, #hae-banner-section, #hae-facts-section");

// create the div to wrap your elements
const wrapper = document.createElement("div");
wrapper.classList.add('section-background');

// add it to the DOM
divs[0].before(wrapper);

// insert the elements into the newly created div
divs.forEach(div => wrapper.append(div));