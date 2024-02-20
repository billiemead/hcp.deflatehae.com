const riveCanvas = document.getElementById("rive-canvas");

const balloon = new rive.Rive({
    src: "/wp-content/themes/conall-child/animations/pharvaris_balloon_animation_-_feb_20_2024_v3.riv",
    canvas: riveCanvas,
    stateMachines: "balloon_sm",
    autoplay: true,
    artboard: "mobile-576x1130",
    fit: rive.Fit.Contain,
    // layout: layout,
    onLoad: () => {
        balloon.resizeDrawingSurfaceToCanvas();
    },
});