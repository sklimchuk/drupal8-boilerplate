// Common used functions.
var _s_counter = 0;
// Capture screenshot.
var capture_screenshot = function(file_name, msg) {
    // Increment counter.
    _s_counter = _s_counter + 1;
    file_name = file_name || ('screen_' + _s_counter + '.png');
    msg = msg || "Captured screenshot " + file_name;

    // Take a screenshot.
    this.viewport(1280, 1080);
    this.captureSelector(reports_dir + file_name, 'body');
    this.echo(msg);
}

exports.capture_screenshot = capture_screenshot;
