$('.current_post').insertBefore('.out_of');
$('#post1,#post2,#post3,#post4,#post5,#post6,#post7,#post8,#post9').prepend('0');

$('#1').addClass('current');
$('#post1').addClass('on');

$('.arrow-right').hover(function() {
	$(this).stop().animate({ 'opacity' : '1' }, 300, 'swing');
}, function() {
	$(this).stop().animate({ 'opacity' : '0.35' }, 300, 'swing');
});
$('.arrow-left').hover(function() {
	$(this).stop().animate({ 'opacity' : '1' }, 300, 'swing');
}, function() {
	$(this).stop().animate({ 'opacity' : '0.35' }, 300, 'swing');
});

function videoReset(slide_num) { // -------------------------- turns off video when going to the next slide
	vimeoWrap = $(slide_num).find('.videoWrapper');
		vimeoWrap.html( vimeoWrap.html() );
	$(slide_num).find('.videoWrapper').css('visibility', 'hidden');
}

$('.arrow-right').click(function() {
	var count = parseInt($('.current').attr('id')) + 1;
	
	if($('.current').hasClass('last')) {
		$('.last').stop().animate({
			'left' : '-42%'
		}, 700, 'easeInOutQuint', function(){
			$(this).removeClass('current').css('left', 'auto');
			videoReset(this);
		});
		
		$('#1').stop().removeClass('past').addClass('current').css('left','150%').animate({
			'left' : '50%'
		}, 700, 'easeInOutQuint', function() {
			$(this).find('.videoWrapper').css('visibility', 'visible');
			
			$('.on').removeClass('on'); // ---------------------------------> updates current post number
			$('#post1').addClass('on'); // ---------------------------------> updates current post number
		});
		
		$('.project_wrap').not('last').removeClass('past').css('left', 'auto');
		
	} else {
		$('.current').stop().animate({
			'left' : '-42%'
		}, 700, 'easeInOutQuint', function(){
			$(this).removeClass('current');
			$(this).addClass('past');
			videoReset(this);
		});
		
		$('#' + count).stop().addClass('current').css('left','150%').animate({
			'left' : '50%'
		}, 700, 'easeInOutQuint', function() {
			$(this).find('.videoWrapper').css('visibility', 'visible');
			
			$('.on').removeClass('on'); // ---------------------------------> updates current post number
			$('#post' + count).addClass('on'); // ---------------------------------> updates current post number
		});
	}	
});

$('.arrow-left').click(function() {
	var neg_count = parseInt($('.current').attr('id')) - 1;
	
	if($('#1').hasClass('current')) {
		
		$('.project_wrap').not('.current').stop().addClass('past').css('left', '-42%');
		
		$('.current').stop().animate({
			'left' : '150%'
		}, 700, 'easeInOutQuint', function(){
			$(this).removeClass('current').addClass('past').css('left', '-42%');
			videoReset(this);
		});
		
		$('.last').stop().animate({
			'left' : '50%'
		}, 700, 'easeInOutQuint', function(){
			$(this).removeClass('past').addClass('current');
			$(this).find('.videoWrapper').css('visibility', 'visible');
			
			$('.on').removeClass('on'); // ---------------------------------> updates current post number
			$('.current_post.last_post').addClass('on'); // ---------------------------------> updates current post number	
		});
		
	} else {
	
		$('.current').stop().animate({
			'left' : '150%'
		}, 700, 'easeInOutQuint', function(){
			$(this).removeClass('current').css('left', 'auto');	
			videoReset(this);
		});
		
		$('#' + neg_count).stop().animate({
			'left' : '50%'
		}, 700, 'easeInOutQuint', function(){
			$(this).removeClass('past').addClass('current');
			$(this).find('.videoWrapper').css('visibility', 'visible');
			
			$('.on').removeClass('on'); // ---------------------------------> updates current post number
			$('#post' + neg_count).addClass('on'); // ---------------------------------> updates current post number		
		});
		
	}
});

function randomFrom(array) {
	return array[Math.floor(Math.random() * array.length)];
}
$('.portland').html(randomFrom(['<a href="http://www.wweek.com/portland/article-21073-automatic_for_the_people.html" target="_blank">Portland</a>', '<a href="https://vimeo.com/41011190" target="_blank">Portland</a>', '<a href="http://www.hulu.com/watch/339646" target="_blank">Portlandia</a>', '<a href="http://www.rosefestival.org/" target="_blank">City of Roses</a>', '<a href="http://www.flickr.com/groups/bridgesofportland/" target="_blank">Bridgetown</a>', '<a href="http://www.portlandbeer.org/breweries/map/" target="_blank">Beervana</a>', '<a href="http://www.blueoregon.com/2010/07/little-beirut-americas-most-patriotic-city/" target="_blank">Little Beirut</a>', '<a href="https://www.facebook.com/BillSchonely" target="_blank">Rip City</a>', '<a href="http://www.stumpedinstumptown.com/2011/03/why-is-portland-named-stumptown/" target="_blank">Stumptown</a>', '<a href="http://www.tumblr.com/tagged/pdx" target="_blank">PDX</a>']));

$('.resume_link').hover(function() {
	$(this).parent().find('.arrow').stop().animate({ 'opacity' : '1' }, 300, 'swing');
}, function() {
	$(this).parent().find('.arrow').stop().animate({ 'opacity' : '0.5' }, 300, 'swing');
});


$('nav a').hover(function() {
	$(this).stop().animate({ 'opacity' : '.6' }, 300, 'swing');
}, function() {
	$(this).stop().animate({ 'opacity' : '1' }, 300, 'swing');
});