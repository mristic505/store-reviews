jQuery(document).ready(function($){
       

        function fire_rate_yo() {
        $("#rateYo").rateYo({
                rating: 0,
                spacing   : "5px",
                ratedFill : "#19ab87",
                fullStar: true
            }).on("rateyo.set", function(e, data) {
                // alert("The rating is set to " + data.rating + "!");
                $('#field_ng6yra').attr('value',data.rating)
            });
        }fire_rate_yo();
        

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            role: 'show_reviews_stats',
            data: {"action": "show_reviews_stats"},
            success: function(data){
                $('#result').html(data);
            }
        });  
        
        function show_reviews(orderby, order, posts_per_page) {
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                role: 'show_reviews',
                data: {
                    "action": "show_reviews",
                    "orderby": orderby,
                    "order" : order,
                    "posts_per_page" : posts_per_page
                },
                success: function(data){
                    $('#result2').html(data);
                }
            });        }
                
        var orderby = 'date';
        var order = 'DESC'
        var posts_per_page = 3;

        // SHOW INITIAL REVIEWS LOOP
        show_reviews(orderby, order, posts_per_page);

        // SHOW MORE OR LESS REVIEWS
        $('#show_more_reviews_btn').click(function(){
            if(posts_per_page == 3) {
               posts_per_page = -1;
               $(this).html('Show Less Reviews <span class="glyphicon glyphicon-triangle-top" aria-hidden="true"></span>'); 
            } 
            else {
               posts_per_page = 3; 
               $(this).html('Show More Reviews <span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span>'); 
            }
            show_reviews(orderby, order, posts_per_page);            
        });
        
        // FILTER REVIEWS 
        $('.reviews-filter li a').click(function(){            
            order = $(this).attr('data-filter');
            if (order == 'date') orderby = 'date';
            else orderby = 'meta_value';
            show_reviews(orderby, order, posts_per_page);
        });
                       
        $(document).ajaxComplete(function(event,xhr,settings){
            console.log("URL",settings.url);
            if(settings.role === "show_reviews_stats") {
                $('#rating_distribution li').each(function(){
                    var rating_distribution = $(this).attr('data-count');
                    var total_results = $(this).attr('data-total-results');
                    var bar_percentage = Math.round(rating_distribution/total_results*100);            
                    $(this).find('.progress .progress-bar').css('width',bar_percentage + '%');
                });
            }
            if(settings.role === "show_reviews") {
                $('.p1').text($('#reviews_list li').length);
                $('.p2').text($('#number_of_reviews').attr('data-nor'));   
                // Aniamte height of the review container when it changes
                var $mydiv = $('#result2');
                $mydiv.css('height', $mydiv.height() );
                $mydiv.wrapInner('<div/>');
                var newheight = $('div:first',$mydiv).height();
                $mydiv.animate( {height: newheight} );                                        
            }
        });

        // CHARACTER COUNT
        var maxLength = 100;
        $('#reviewtext textarea').keyup(function() {
          var length = $(this).val().length;
          var length = maxLength-length;
          if (length > 0) {
            if (length < 100) $('.cc2').addClass('red');
            else $('.cc2').removeClass('red');
            $('.cc2').show()
            $('.cc1').text('Character Minium: 100 | ');
            $('.cc3').show();
            $('#chars').text(length);
          }
          else {
            $('.cc1').text('You reached the required minimum number of characters.');
            $('.cc2').hide().removeClass('red');
            $('.cc3').hide();
          } 
        });
});
