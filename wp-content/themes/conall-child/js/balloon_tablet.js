
const riveCanvas = document.getElementById("rive-canvas");

// Fill the canvas, cropping Rive if necessary
let layout = new rive.Layout({
    fit: rive.Fit.Cover,
});

// Fit to the width and align to the top of the canvas
layout = new rive.Layout({
    fit: rive.Fit.FitWidth,
    alignment: rive.Alignment.TopCenter,
});

// Constrain the Rive content to (minX, minY), (maxX, maxY) in the canvas
layout = new rive.Layout({
    fit: rive.Fit.Contain,
    minX: 50,
    minY: 50,
    maxX: 100,
    maxY: 100,
});

const balloon = new rive.Rive({
    src: "/wp-content/themes/conall-child/animations/balloonstream2.riv",
    canvas: riveCanvas,
    stateMachines: "balloon_sm",
    autoplay: true,
    artboard: "tablet-1024x696",
    // fit: rive.Fit.FitWidth,
    layout: layout,
    onLoad: () => {
        balloon.resizeDrawingSurfaceToCanvas();
    },
});

balloon.layout = new rive.Layout({ fit: rive.Fit.Fill });