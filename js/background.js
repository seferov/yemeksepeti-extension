// (c) 2015 Farhad Safarov <http://ferhad.in>

document.addEventListener('DOMContentLoaded', function () {
  jokerNotifier.checkJoker();
});

// Check every 2 minutes
setInterval(function() {
  chrome.storage.local.get('check', function(data) {
    if (data.check) {
      jokerNotifier.checkJoker();
    }
  });
}, 2 * 60 * 1000);
