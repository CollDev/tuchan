(function(win, $, undefined) {
  MediaSplitter = function(id, options) {
    var that = this,
      block = $(id),
      target = $('<div/>', {
        id: 'target_' + id
      }),
      player = block.find('.mediasplitter-player'),
      slider = block.find('.mediasplitter-slider'),
      loader = block.find('.mediasplitter-loader'),
      progress = block.find('.mediasplitter-progress'),
      indicator = block.find('.mediasplitter-progress-indicator'),
      videoControls = block.find('.mediasplitter-controls'),
      videoPlay = block.find('.mediasplitter-play'),
      timeRemaming = block.find('.mediasplitter-time-remaming'),
      dstart = block.find('.mediasplitter-time-start'),
      dend = block.find('.mediasplitter-time-end'),
      ddur = block.find('.mediasplitter-time-dur'),
      btnPlay = block.find('.mediasplitter-play'),
      metaDataLoaded = false,
      vstart,
      vend,
      stats = [vstart, vend],
      range = [],
      videoProgressInterval,
      timer,
      ratio,
      duration,
      el,
      handlers = {

        updateVideoCutDuration: function() {
          ddur.val((stats[1] - stats[0]).toFixed(1));
        },

        updateProgressPosition: function() {
          var vals = slider.val(),
            diff = vals[1] - vals[0];
          progress.css({
            left: vals[0] / duration * 100 + '%',
            width: diff / duration * 100 + '%'
          });
        },
        onMetaData: function() {
          if (metaDataLoaded) return;
          metaDataLoaded = true;
          
          duration = el.getDuration();
          $("#dur_total").val(duration);
          
          range = [0, duration];
          var h = el.getMeta().height,
            w = el.getMeta().width;
          if (h < w) {
            ratio = h / w;
          } else if (h < w) {
            ratio = w / h;
          } else {
            ratio = 1;
          }
          // player.height(player.width() * ratio);
          bindSlider();
        },
        timeUpdate: function(o) {
          var time = o.position,
            vals = slider.val(),
            diff = vals[1] - vals[0];
          if (time >= stats[1]) {
            handlers.stop();
          }
          indicator.width((time - vals[0]) / diff * 100 + '%');
          timeRemaming.text(time.toFixed(1));
        },
        stop: function(){
          handlers.end();
          indicator.width(0);
          timeRemaming.text(stats[0]);
        },
        end: function(){
          el.seek(stats[0]);
          el.pause();
        },
        onPause: function(){
          btnPlay.text('Play');
        },
        onPlay: function() {
          btnPlay.text('Stop');
        }
      },
      bindSlider = function() {
        vstart = dstart.val() == '' ? range[0] : Number(dstart.val());
        vend = dend.val() == '' ? range[1] : Number(dend.val());

        stats = [vstart, vend];
        slider.noUiSlider({
          range: range,
          start: [vstart, vend],
          handles: 2,
          serialization: {
            to: [dstart, dend],
            resolution: 0.1
          },
          slide: function() {
            var vals = $(this).val(),
              time, j;
            handlers.updateProgressPosition();
            window.clearTimeout(timer);
            timer = window.setTimeout(function() {
              for (j in vals) {
                if (stats[j] != vals[j]) {
                  time = vals[j];
                  break;
                }
              }
              if (j == 0) {
                // left handler change
              } else {
                // right handler change
              }
              stats = vals.slice(0);
              handlers.updateVideoCutDuration();
              if (time != undefined) {
                el.seek(time);
                handlers.stop();
              }
            }, 100);
          }
        });
        handlers.updateVideoCutDuration();
      };

    btnPlay.on('click', function(){
      if (el.getState() == 'PLAYING'){
        handlers.stop();
      } else {
        el.play();
      }
    });

    el = jwplayer(target.appendTo(player).get(0)).setup({
      file: block.attr('data-src'),
      startparam: 'ec_seek',
      primary: "flash",
      width: '100%',
      controls: false
    });

    el.onMeta(handlers.onMetaData);
    el.onTime(handlers.timeUpdate);
    el.onPause(handlers.onPause);
    el.onPlay(handlers.onPlay);

    el.onReady(function() {
      handlers.stop();
    });

    return {
      player: el,
      slider: slider
    };
  };
}(window, window.jQuery));