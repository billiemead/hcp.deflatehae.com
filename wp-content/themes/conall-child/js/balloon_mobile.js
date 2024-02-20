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
    src: "/wp-content/themes/conall-child/animations/pharvaris_balloon_animation_-_feb_20_2024_v3.riv",
    canvas: riveCanvas,
    stateMachines: "balloon_sm",
    autoplay: true,
    artboard: "mobile-576x1130",
    // fit: rive.Fit.Contain,
    layout: layout,
    onLoad: () => {
        balloon.resizeDrawingSurfaceToCanvas();
    },
});

balloon.layout = new rive.Layout({ fit: rive.Fit.Fill });