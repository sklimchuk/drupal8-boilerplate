import http from "k6/http";
import {check, group} from "k6";

export let options = {
  // Set threshold rate to 2%.
  thresholds: {
    "errors": ["rate<0.02"],
  },
  insecureSkipTLSVerify: true
};

export default function () {

  group('API:getAvailabilityRooms', function () {
    // Please update the following line with URL to test.
    const baseUrl = "https://example.com";

    const properHotelsIds = [
      44,
      52,
      61,
    ];

    let roomsCount = 1;
    let adultsCount = 1;
    let childrenCount = 0;
    let currencyCode = 'EUR';

    let today = new Date();
    let fromDate = today.getMonth() + 1 + '/' + today.getDate() + '/' + today.getFullYear();

    let tomorrow = new Date(today.getTime() + 24 * 60 * 60 * 1000);
    let toDate = tomorrow.getMonth() + 1 + '/' + tomorrow.getDate() + '/' + tomorrow.getFullYear();

    let params = {
      'rooms_count': roomsCount,
      'adults_count': adultsCount,
      'children_count': childrenCount,
      'from_date': fromDate,
      'to_date': toDate,
      'currency_code': currencyCode
    };
    let res;
    properHotelsIds.forEach(function(item, index, array) {
      res = http.get(baseUrl + '/api/' + item + '/getAvailability', params);
      console.log('Node ' + item + ' availability request has ' + res.timings.duration + ' response time');
      check(res, {
        "status is 200": (r) => r.status == 200,
        "request duration is less than 3s": (r) => r.timings.duration < 3000
      }) || errorRate.add(1);
    })


  });

};
