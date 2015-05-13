// (c) 2015 Farhad Safarov <http://ferhad.in>

$(document).ready(function($) {
  jokerNotifier.checkJoker();

  $(document.body).on('click', '.result table tr' ,function() {
    chrome.tabs.create({
      url: $(this).data('href')
    });
  });
});
