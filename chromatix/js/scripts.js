/**
 *
 *	Jquery / JS storyboard to show Chromatix Chris
 *	@todo Get job with Chromatix
 *
 */
(function ($, root, undefined) {

	var chromatix = {};
	
	$(function () {

		// Init vars
		var stage;
				
		// Once they start typing their name, fade in start button
		$('.first-name-input').keyup(function() {

			// Reveal button
			$('.start').removeClass('hidden');

			// Store name for use
			var name = $('.first-name-input').val();

			// Add it to relevant HTML
			$('#first-name').text(name);
			$('#first-name-two').text(name);

		});

		// Start button
		$('.start').click(function() {

			// Start storyboard
			chromatix.start();

		});

		// Next scene button
		$('.next-scene').click(function() {

			// Execute next scene
			chromatix.aboutMe();

		});

		// Move to next part
		$('.continue-button').click(function() {

			// Move animation to next frame
			chromatix.move();

		});

		// Move to next part (space bar)
		$('body').keyup(function(e){

			if(e.keyCode == 32){

				// user has pressed space
				chromatix.move();
				return false;

			}

		});

		// Reveal sub-menu on skills
		$('.has-children').click(function() {

			// Toggle UL display
			$(this).next('.sub-skills').slideToggle();

			// Add active class to flip chevron around
			$(this).toggleClass('active');

		});

		// Begin storyboard
		var startStory = function() {

			// Animate background
			$('.floating-stars').addClass('background-slide');

			// Get rid of a text & button
			$('.introduction').addClass('slide-out-x hidden');

			// Bring space ship onto screen
			$('.space-ship-container').addClass('on-screen');

			// Display 
			$('.instruction').removeClass('hidden');

			// Delay by 1s
			setTimeout( function () {
				
				// Automatically begin first chapter
				chromatix.one();


			}, 1000 );

		}

		// Stage 1
		var storyOne = function() {

			// Set current stage
			stage = 1;

			console.log('Story one has begun.');

			// Remove previous content
			$('.introduction-container').fadeToggle();

			// Bring in new content after 1s delay
			$('.story-one').delay(1000).fadeToggle();
		}

		// Stage 2
		var storyTwo = function() {

			// Set current stage
			stage = 2;

			console.log('Story two has begun.');

			// Remove previous content
			$('.story-one').fadeToggle();

			// Bring in new content after 1s delay
			$('.story-two').delay(1000).fadeToggle();

		}

		// Stage 3
		var storyThree = function() {

			// Set current stage
			stage = 3;

			console.log('Story three has begun.');

			// Remove previous content
			$('.story-two').fadeToggle();

			// Bring in new content after 1s delay
			$('.story-three').delay(1000).fadeToggle();

		}

		// Stage 4
		var storyFour = function() {

			// Set current stage
			stage = 4;

			console.log('Story four has begun.');

			// Remove previous content
			$('.story-three').fadeToggle();

			// Bring in new content after 1s delay
			$('.story-four').delay(1000).fadeToggle();

		}

		// Stage 5
		var storyFive = function() {

			// Set current stage
			stage = 5;

			console.log('Story five has begun.');

			// Remove previous content
			$('.story-four').fadeToggle();

			// Bring in new content after 1s delay
			$('.story-five').delay(1000).fadeToggle();

		}

		// Display Closing Options
		var storyClose = function() {

			// Set current stage
			stage = 6;

			// Rocket goes forward
			$('.space-ship-container').addClass('slide-out-x-invert');

			// Fade out Text
			$('.story-five .hero-title').delay(1000).addClass('hidden');

			// Fade the continue button in
			$('.next-scene').delay(1000).fadeToggle(3000);

		}

		// Display About Me
		var aboutMe = function() {

			stage = 7;

			console.log('Executing About Me.');

			// Remove the next scene button
			$('.next-scene').slideToggle(1000).delay(3000);

			// Introduce new content
			$('.section-two').fadeToggle();

			// Scroll down by window height
			var n = $('.section-two').offset().top;

			// Scroll after 400ms
			setTimeout( function () {
				
				// Scroll down after section two is available
    			$('html, body').animate({ scrollTop: n }, 2000);

			}, 400 );

		}

		// Display Super Power
		var superPowers = function() {

			stage = 8;

			console.log('Revealing super powers.');

			// fade out each P element in 
			$('.section-two p').each(function( index ) {

				wait = index * 200 + 200;

				$(this).delay(wait).queue(function() {

					$(this).addClass('hidden slide-out-y').dequeue();

				});

			});

			// Remove button
			$('.super-powers-activate').addClass('hidden');

			// Introduce new content
			$('.section-three').fadeToggle();

			// Animate superhoer
			$('.superhero').removeClass('slide-out-x').addClass('slide-out-x-invert');

			// Scroll down by window height
			var n = $('.section-three').offset().top;

			// Scroll after 400ms
			setTimeout( function () {
				
				// Scroll down after section two is available
    			$('html, body').animate({ scrollTop: n }, 2000);

			}, 2000 );

		}

		// Bring It Home
		var bringItHome = function() {

			stage = 9;

			console.log('Bringing it home.');

			// Display final content
			$('.section-zero').fadeToggle();

			// Hide previous text
			$('.cover-letter').addClass('hidden');

			// Hide top section of how well do you know Chris
			$('.progress-navigator-top').fadeToggle(1000);

			//rotate rocket so it's vertical, 
			$('.floating-ship').removeClass('floating-ship');

			// Bring the rocket into the center of the screen
			$('.space-ship-container').removeClass('slide-out-x-invert').delay(3000).queue(function(){

				// Move rocket beyond container
    			$('.space-ship-container').addClass('slide-out-y').dequeue();

			});

			// Scroll to top of page
			setTimeout( function () {
				
				// Scroll to top of page
    			$('html, body').animate({ scrollTop: 0 }, 4000 );

			}, 1000 );

			// On complete, get rid of everything else.
    		$('.section-one, .section-two, .section-three').delay(5000).fadeToggle();

		}

		// Slide progress bar
		var chrisProgress = function( progress ) {

			// Progress points (max / final stage = 1)
			var max = 9;

			// Percentage complete
			percentage = (progress/max) * 100;

			// Update progress bar
			$('.progress-fill').css('width', percentage + '%');

			if ( progress == 3 ) {

				// Entered the acquiantance zone
				$('.progress-status').text('We just became acquaintances');

			} else if ( progress == 6 ) {

				// Entered the friendship zone
				$('.progress-status').text('Standby, entering the friendship zone');

			} else if ( progress == max ) {

				// Officially besties
				$('.progress-status').text('We\'ve just officially become besties');

			}

		}

		// Handles moving forward in the storyboard
		var moveStory = function() {

			if (stage == 0) {

				chromatix.one();
				chromatix.progress(stage);

			} else if (stage == 1) {

				chromatix.two();
				chromatix.progress(stage);

			} else if (stage == 2) {

				chromatix.three();
				chromatix.progress(stage);

			} else if (stage == 3) {

				chromatix.four();
				chromatix.progress(stage);

			} else if (stage == 4) {

				chromatix.five();
				chromatix.progress(stage);

			} else if (stage == 5) {

				chromatix.closeStory();
				chromatix.progress(stage);

			} else if (stage == 6) {

				chromatix.aboutMe();
				chromatix.progress(stage);

			} else if (stage == 7) {

				chromatix.superPowers();
				chromatix.progress(stage);

			} else if (stage == 8) {

				chromatix.bringItHome();
				chromatix.progress(stage);

			} else if (stage == 9) {

				chromatix.progress(stage);

			}

		}

		// Init
		chromatix.start 		= startStory;
		chromatix.one 			= storyOne;
		chromatix.two 			= storyTwo;
		chromatix.three 		= storyThree;
		chromatix.four 			= storyFour;
		chromatix.five 			= storyFive;
		chromatix.closeStory 	= storyClose;
		chromatix.aboutMe	 	= aboutMe;
		chromatix.superPowers	= superPowers;
		chromatix.bringItHome	= bringItHome;
		chromatix.move 			= moveStory;
		chromatix.progress 		= chrisProgress;

	});
	
})(jQuery, this);

jQuery(document).ready(function($) {

	// On load, bring the first elements in
	$('.introduction').removeClass('slide-out-y hidden');

});