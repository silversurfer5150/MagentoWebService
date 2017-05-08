$(document).ready(function() {

    // Deals with App Navigation
    $(".nav-stacked li").click(function(e) {
        e.preventDefault();
        $(".active").removeClass("active");
        $(this).addClass("active");
        var section = $(this).find("a").attr("href");
        $(".section").hide();
        $(section).show();
    });

    // jQuery plugin dealing with get / set / save config
    (function($) {
        $.fn.setPrices = function() {
            this.config = {
                config: "test"
            };
            this.getConfig = function() {
                return this.config;
            };
            this.setConfig = function(config) {
                this.config = config;
            };
            return this;

        }

    }(jQuery));


    // Load local config file and setupUi
    $.ajax({
        dataType: "json",
        url: "config.json",
        success: function(json) {
            // Create data management plugin and get reference
            var configObj = $(document).setPrices();
            // load from JSON into pricing plugin
            configObj.setConfig(json.data);
            // get local reference to data inside plugin
            var pricing = configObj.getConfig();
            // pass reference into ui setup function
            setUpUi(pricing);
        }
    });

    function setUpUi(pricing) {
        d = new Date();
        d.toLocaleString();
        d.toLocaleDateString();
        d.toLocaleTimeString();
        $(".glyphicon-time").parent().text(d);

        // Loop each slider and load in values from config object
        $(".sliderSection .range").each(function(index) {
            var i = (index + 1);
            $("#range" + i).slider({
                min: pricing[i - 1]['min'],
                max: pricing[i - 1]['max'],
                step: 0.1,
                value: pricing[i - 1]['val'],
                slide: function(event, ui) {
                    // user sliders and the value of the input changes to reflect
                    $("#case" + i).val(" < " + ui.value.toFixed(2));
                }
            });
            // render current value in input on load
            $("#case" + i).val(" < " + $("#range" + i).slider("value").toFixed(2));
            //render % margin from config
            $("#percentage" + i).val(pricing[i - 1]['margin']).next(".greenPercent").text(pricing[i - 1]['margin'] + " %");

            // As user enters % profit margin, also show this visually next to the field
            $("#percentage" + i).keyup(function() {
                $(this).next(".greenPercent").text($(this).val() + " %");
            });

            $("#percentage" + i).blur(function() {
                $(this).next(".greenPercent").text($(this).val() + " %");
            });
        });

        $("button").click(function(e) {
            e.preventDefault();
            validate();
        })

    }

    function saveConfig() {
        var saveObj = {
            "data": []
        };
        var i, val;
        $(".sliderSection .range").each(function(index) {
            var temp = {};
            i = (index + 1);
            val = $("#range" + i).slider("value");
            temp['val'] = val;
            temp['min'] = $("#range" + i).slider("option", "min");
            temp['max'] = $("#range" + i).slider("option", "max");
            temp['margin'] = $("#percentage" + i).val();
            saveObj.data[index] = temp;
        });
        //  console.log(saveObj.data);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "saveConfig.php",
            data: { myjson: JSON.stringify({ data: saveObj.data }) },
            complete: function(response) {
                console.log(saveObj.data);
            }
        });
    }

    function validate() {
        var numCases = $(".sliderSection .case").length;
        var ascFlag = true;
        $(".sliderSection .case").each(function(index) {
            i = (index + 1);
            if (i < numCases) {
                var rangeVal = Number($("#case" + i).val().split("< ")[1]);
                var nextRangeVal = Number($("#case" + (i + 1)).val().split("< ")[1]);
                if (nextRangeVal < rangeVal || nextRangeVal === rangeVal) {
                    ascFlag = false;
                }
            }
        });
        if (ascFlag) {
            $(".rangeError.text-danger").hide();
            $(".fa-spin, .saveSuccess").css("display", "inline-block");
            setTimeout(function() {
                $(".fa-spin, .saveSuccess").css("display", "none");
            }, 1700);
            saveConfig();
        } else {
            $(".rangeError.text-danger").show();
        }
    }

});