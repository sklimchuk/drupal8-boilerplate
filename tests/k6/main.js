import {parseHTML} from "k6/html";
import http from "k6/http";
import { check, group } from "k6";
import {sleep} from "k6";
import { Rate } from "k6/metrics";

export let errorRate = new Rate("errors");

export let options = {
  // Set threshold rate to 2%.
  thresholds: {
    "errors": ["rate<0.02"],
  },
  insecureSkipTLSVerify: true
};

export default function() {
  // Please update the following line with URL to test.
  const base_url = "https://example.com";
  var res;

  // Check for Homepage availability.
  group("Front page", function() {
    check(res = http.get(base_url), {
      "status is 200": (r) => r.status == 200
    }) || errorRate.add(1);
  });

  // Loop through all links from main menu.
  group("Main menu", function() {
    const sel = parseHTML(res.body).find('.region-header-navigation .menu-item a');
    var url;

    for(var i = 0; i < sel.size(); i++) {
      if (sel.get(i).hostname().length == 0) {
        url = base_url + sel.get(i).href();
      }
      else {
        url = sel.get(i).href();
      }

      console.log(url);
      check(http.get(url), {
        "status is 200": (r) => r.status == 200
      }) || errorRate.add(1);

    }
  });
};
