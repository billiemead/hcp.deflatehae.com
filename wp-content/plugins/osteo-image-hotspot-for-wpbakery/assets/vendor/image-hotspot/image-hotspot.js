(function($) {
    "use strict";

    jQuery(document).ready(function(t) {
        t(".osteo-tooltip-container").each(function() {
            var i, a = t(this),
                e = t(this).data("tooltipstyle") || "shadow",
                o = t(this).data("animation") || "fade",
                n = t(this).data("maxwidth") || null,
                s = t(this).data("opacity") || .5,
                r = (t(this).data("isdisplayall"), parseInt(t(this).data("displayednum")), t(this).data("marginoffset") || "0");
            "0" != r && (t(window).on("resize", function(i) {
                var e = t(this).width();
                e <= 540 ? t(".hotspot-item", a).each(function(i) { t(this).css("margin", r) }) : t(".hotspot-item", a).each(function(i) { t(this).css("margin", "0") })
            }), t(window).trigger("resize")), t(".osteo-tooltip", t(this)).each(function(a) {
                var r = t(this),
                    h = t(this).css("background-color"),
                    c = t(this).data("trigger") || "hover",
                    d = "yes" == t(this).data("isopen"),
                    p = t(this).data("position") || "top";
                i = h.indexOf("a") == -1 ? h.replace(")", ", " + s + ")").replace("rgb", "rgba") : h, t(this).on("click", function(i) { "" != t(this).attr("href") && "#" != t(this).attr("href") || i.preventDefault() });
                var f = t(".osteo-hotspot-content", t(this)).html(),
                    l = t(window).width() <= 480 ? 0 : 2,
                    u = 0;
                r = t(this).tooltipster({
                    content: f,
                    position: p,
                    offsetX: l,
                    offsetY: u,
                    maxWidth: n,
                    delay: 100,
                    speed: 500,
                    interactive: !0,
                    animation: o,
                    trigger: c,
                    contentAsHTML: !0,
                    theme: "tooltipster-" + e
                }), d && setTimeout(function() { r && r.tooltipster("show") }, 600)
            })
        })
    });
    
})(jQuery);