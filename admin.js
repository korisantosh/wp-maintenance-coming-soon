jQuery(document).ready(function ($) {
  $("#mmcs-logo-upload").click(function (e) {
    e.preventDefault();
    var mediaUploader = wp
      .media({
        title: "Choose Logo",
        button: {
          text: "Use this logo",
        },
        multiple: false,
      })
      .on("select", function () {
        var attachment = mediaUploader
          .state()
          .get("selection")
          .first()
          .toJSON();
        $("#mmcs-logo").val(attachment.url);
      })
      .open();
  });
  $("#mmcs-accordion").accordion({
    collapsible: false,
    heightStyle: "content",
    active: 0, // Initially collapsed
  });
});
