// (c) 2015 Farhad Safarov <http://ferhad.in>

$(document).ready(function($) {
  jokerNotifier.checkJoker();

  $(document.body).on('click', '.result table tr' ,function() {
    chrome.tabs.create({
      url: $(this).data('href')
    });
  });
});

function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10)
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.text(minutes + ":" + seconds);

        if (--timer < 0) {
            timer = duration;
        }
    }, 1000);
}
