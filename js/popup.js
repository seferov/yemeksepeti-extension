// (c) 2015-2016 Farhad Safarov <http://farhadsafarov.com>

$(document).ready(function($) {
  jokerNotifier.checkJoker();

  $(document.body).on('click', '.result table tr' ,function() {
    chrome.tabs.create({
      url: $(this).data('href')
    });
  });

  var onOfSwitch = $('.onoffswitch-checkbox');
  chrome.storage.local.get('check', function(data) {
    onOfSwitch.attr('checked', data.check);
  });

  onOfSwitch.on('change', function() {
    var isActive = $(this).is(':checked'),
      icon = isActive ? 'img/icon.png' : 'img/icon_off.png';

    chrome.storage.local.set({'check': isActive});
    chrome.browserAction.setIcon({path: icon});
    chrome.browserAction.setBadgeText ({text: ''});

    if (isActive) {
      jokerNotifier.checkJoker();
    }
  });
});

function startTimer(duration, display) {
  var timer = duration, minutes, seconds;
  setInterval(function () {
    minutes = parseInt(timer / 60, 10)
    seconds = parseInt(timer % 60, 10);

    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;

    display.text(minutes + ':' + seconds);

    if (--timer < 0) {
        timer = duration;
    }
  }, 1000);
}
