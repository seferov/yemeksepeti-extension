// (c) 2015 Farhad Safarov <http://ferhad.in>

var jokerNotifier = {

  _jokerUrl: 'https://www.yemeksepeti.com/basket/GetNewJokerOffer',

  checkJoker: function() {
    var _this = this,
      ysRequest = {
        'Culture': 'tr-TR',
        'LanguageId': 'tr-TR'
      };

    chrome.cookies.getAll({}, function(data) {
      $.each(data, function(index, cookie){
        switch(cookie.name) {
          case 'catalogName':
            ysRequest['CatalogName'] = cookie.value;
            break;
          case 'loginToken':
            ysRequest['Token'] = cookie.value;
            break;
        }
      });

      _this._fetchResult(ysRequest);
    })
  },

  _fetchResult: function(ysRequest) {
    var _this = this;
    $.ajax({
      url: this._jokerUrl,
      type: 'post',
      data: {
        'ysRequest': ysRequest
      },
      headers: {
        'Content-Type': 'application/json;charset=UTF-8',
        'X-Requested-With': 'XMLHttpRequest'
      },
      dataType: 'json',
      success: function (data) {
        _this.displayResult(data);
      }
    });
  },

  displayResult: function(data) {
    if (data.OfferItems && data.OfferItems.length) {
      var table = $('<table/>');

      for (var i = data.OfferItems.length - 1; i >= 0; i--) {
        var restaurant = data.OfferItems[i].Restaurant,
          row = $('<tr/>');
        row.append($('<td/>').text(restaurant.DisplayName));
        row.append($('<td/>').text(restaurant.AveragePoint));
        table.append(row);
      };

      $('.result').html(table);

      chrome.browserAction.setBadgeText ( { text: data.OfferItems.length.toString() } );
    }
    else {
      $('.result').html('Joker yok :(');
      chrome.browserAction.setBadgeText ( { text: '' } );
    }
  }
};