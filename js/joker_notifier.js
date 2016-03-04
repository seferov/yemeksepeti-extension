// (c) 2015-2016 Farhad Safarov <http://farhadsafarov.com>

var jokerNotifier = {

  _jokerUrl: 'https://www.yemeksepeti.com/basket/GetNewJokerOffer',
  _showNotification: false,

  checkJoker: function(showNotification) {
    var _this = this;
    _this._showNotification = showNotification;
    chrome.storage.local.get('check', function(data) {
      if (data.check) {
        _this._check();
      }
      else {
        $('.result').hide();
        $('.duration-holder').hide();
      }
    });
  },

  _check: function() {
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
    });
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
        if(_this._showNotification) {
          _this.showNotification(data);
        } else {
          _this._showNotification = true;
        }
        _this.displayResult(data);
      }
    });
  },

  displayResult: function(data) {
    var resultArea = $('.result');
    resultArea.html('');

    if (data.OfferItems && data.OfferItems.length) {
      if (typeof(startTimer) === typeof(Function)) {
        startTimer(data.RemainingDuration/1000, $('#duration'));
      }

      var table = $('<table/>');

      $.each(data.OfferItems, function(index, offer) {
        var row = $('<tr/>', {
          'data-href': 'http://www.yemeksepeti.com' + offer.Restaurant.RestaurantUrl
        });

        row.append($('<td/>').html($('<img />', {
          src: 'http:'+offer.Restaurant.JokerImageUrl,
          alt: offer.Restaurant.DisplayName,
          width: 60
        })));
        row.append($('<td/>').text(offer.Restaurant.DisplayName));
        row.append($('<td/>', {'class': 'strong'}).html(offer.Restaurant.AveragePoint));
        table.append(row);
      });

      resultArea.append(table);

      chrome.browserAction.setBadgeText ( { text: data.OfferItems.length.toString() } );
    }
    else {
      // resultArea.html(data.IsValid ? 'Joker yok :(' : data.Message);
      resultArea.html('Joker yok :(');
      chrome.browserAction.setBadgeText ( { text: '' } );
    }
  },

  // Aynı notification tekrar çıkmasın diye son 8 jokeri tutuyor
  history: Array(8).fill(''),

  showNotification: function(data) {
    var _this = this;
    $.each(data.OfferItems, function(index, offer) {
      if(_this.history.indexOf(offer.Restaurant.DisplayName) == -1) {
        var options = {
          type: 'basic',
          title: 'Joker İndirimi!',
          message: offer.Restaurant.DisplayName + '('+ offer.Restaurant.AveragePoint +')',
          iconUrl: 'http:'+ offer.Restaurant.JokerImageUrl
        }

        _this.history.shift();
        _this.history.push(offer.Restaurant.DisplayName);
        chrome.notifications.create(options);
      }
    });
  }
};
