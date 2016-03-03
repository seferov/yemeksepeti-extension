// (c) 2015 Farhad Safarov <http://farhadsafarov.com>

document.addEventListener('DOMContentLoaded', function () {
    jokerNotifier.checkJoker();
});

// Check every 2 minutes
setInterval(function() {
  jokerNotifier.checkJoker(true);
}, 2 * 60 * 1000);
