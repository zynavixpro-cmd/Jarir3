setInterval(() => {
    debugger; // يوقف المتصفح إذا حاول شخص فتح الـ Console
}, 100);

document.onkeydown = function(e) {
    if (e.ctrlKey && (e.keyCode === 67  e.keyCode === 86  e.keyCode === 85 || e.keyCode === 117)) {
        return false;
    }
};
