// (c) 2015 Farhad Safarov <http://ferhad.in>

document.addEventListener('DOMContentLoaded', function () {
  jokerNotifier.checkJoker();
});

// Check every minutes
setInterval(function() {
    jokerNotifier.checkJoker()
}, 60000);
