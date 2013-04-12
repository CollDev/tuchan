(function(win, $, undefined) {
    MediaSplitter = function(id, swf, options) {
        var that = this,
                block = $(id),
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
                duration = el.getClip().fullDuration;
                range = [0, duration];
                var h = el.getClip().metaData.height,
                        w = el.getClip().metaData.width;
                if (h < w) {
                    ratio = h / w;
                } else if (h < w) {
                    ratio = w / h;
                } else {
                    ratio = 1;
                }
                player.height(player.width() * ratio);
                bindSlider();
                handlers.updateProgress();
            },
            timeUpdate: function() {
                var time = el.getTime(),
                        vals = slider.val(),
                        diff = vals[1] - vals[0];
                if (time >= stats[1]) {
                    handlers.end();
                }
                indicator.width((time - vals[0]) / diff * 100 + '%');
                timeRemaming.text(time.toFixed(1));
            },
            end: function() {
                el.pause();
                this.stopVideoChk();
            },
            onStart: function() {
                // todo http://flash.flowplayer.org/forum/3/102579
                el.seek(stats[0]);
                handlers.startVideoChk();
                handlers.updateProgressPosition();
            },
            onStop: function() {
                handlers.stopVideoChk();
            },
            onResume: function() {
                el.stop();
                el.play();
            },
            startVideoChk: function() {
                vpInterval = setInterval(handlers.timeUpdate);
            },
            stopVideoChk: function() {
                clearInterval(vpInterval);
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
                            handlers.end();
                            indicator.width(0);
                        }
                    }, 100);
                }
            });
            handlers.updateVideoCutDuration();
        };
        el = flowplayer(player.get(0), swf, {
            clip: {
                url: block.attr('data-src'),
                autoPlay: false,
                autoBuffering: true,
                scaling: 'fit',
                onMetaData: handlers.onMetaData,
                onStop: handlers.onStop,
                onStart: handlers.onStart,
                onResume: handlers.onResume
            },
            plugins: {
                controls: null
            },
            onLoad: function() {
                var chekStatusTimer = window.setInterval(function() {
                    if (el.getState() == 4) {
                        el.seek(stats[0]);
                        window.clearInterval(chekStatusTimer);
                        dur_video = $("#dur_corte").val();
                        $("#dur_total").val(dur_video);

                    }
                }, 25);
            }
        });
        return {
            player: el,
            slider: slider
        };
    };
}(window, window.jQuery));