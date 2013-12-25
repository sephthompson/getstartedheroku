
var gShortcutIcons = document.getElementById("shortcutIconsPanel");
gShortcutIcons.style.position = "absolute";
gShortcutIcons.style.display = "none";

var gBlurShortcutIcons = false;
gShortcutIcons.addEventListener("mouseover", function() {
	gBlurShortcutIcons = false;
});
gShortcutIcons.addEventListener("mouseout", function() {
	gBlurShortcutIcons = true;
});
document.addEventListener("mousedown", function() {
	if (gBlurShortcutIcons) {
		gShortcutIcons.style.display = "none";
	}
});
function showShortcutIcons() {
	gShortcutIcons.style.display = "inline-block";
	gBlurShortcutIcons = true;
}
