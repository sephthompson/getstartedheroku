
function createPopupFrame(buttonID, frameID) {
	var button = document.getElementById(buttonID);
	var frame = document.getElementById(frameID);
	button.mPopupFrame = frame;
	frame.mBlurOnClick = true;
	frame.mShowPopup = false;
	
	frame.mSetVisible = function(show) {
		this.mShowPopup = show;
		this.style.display = show ? "inline-block" : "none";
	}
	button.addEventListener("click", function() {
		this.mPopupFrame.mSetVisible(!this.mPopupFrame.mShowPopup);
	});
	
	frame.addEventListener("mouseover", function() {
		this.mBlurOnClick = false;
	});
	frame.addEventListener("mouseout", function() {
		this.mBlurOnClick = true;
	});
	document.addEventListener("mousedown", function() {
		if (frame.mBlurOnClick) {
			frame.mSetVisible(false);
		}
	});
}