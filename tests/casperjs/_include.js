// Include
casper.pageSettings.loadImages = false;

/**
 * Add a listener for the phantomjs resource request.
 *
 * This allows us to abort requests for external resources that we don't need
 * like Google adwords tracking.
 */
casper.options.onResourceRequested = function(casper, requestData, request) {
  // If any of these strings are found in the requested resource's URL, skip
  // this request. These are not required for running tests.
  var skip = [
    'googleads.g.doubleclick.net',
    'cm.g.doubleclick.net',
    'www.googleadservices.com'
  ];

  skip.forEach(function(needle) {
    if (requestData.url.indexOf(needle) > 0) {
      request.abort();
    }
  })
};
