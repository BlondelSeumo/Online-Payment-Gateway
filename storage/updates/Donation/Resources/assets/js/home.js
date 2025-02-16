"use strict";

$(document).ready(function() {
    
  var page = 1;
  $(window).scroll(function() {
    if ($(window).scrollTop() + $(window).height() >= $(document).height() - 2800) {
      page++;
      
      if (page <= lastPage) {
        loadMoreData(page);
      }
    }
  });

  function loadMoreData(page) {

    $.ajax({

      url: 'campaigns?page=' + page,
      type: "get",
      beforeSend: function() {
        $('.loading').show();
      }
    })
    .done(function(data) {
      $('.loading').hide();
      $(".doantion-container").append(data.donations);
      lastPage = data.last_page;
    })
    .fail(function(jqXHR, ajaxOptions, thrownError) {
      
    });
  }
});