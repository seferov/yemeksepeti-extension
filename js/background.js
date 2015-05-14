// (c) 2015 Farhad Safarov <http://ferhad.in>

document.addEventListener('DOMContentLoaded', function () {
  jokerNotifier.checkJoker();
});

// Check every 5 minutes
setInterval(function() {
    jokerNotifier.checkJoker()
}, 5 * 60 * 1000);
