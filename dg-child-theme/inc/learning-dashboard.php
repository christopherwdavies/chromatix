<?php
/*
**
**		@ Description: List of exercises for learning dashboard
**		https://daviesguitars.io/learning-dashboard/
**		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" integrity="sha256-Uv9BNBucvCPipKQ2NS9wYpJmi8DTOEfTA/nH2aoJALw=" crossorigin="anonymous"></script>
**		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css" integrity="sha256-aa0xaJgmK/X74WM224KMQeNQC2xYKwlAt08oZqjeF0E=" crossorigin="anonymous" />
*/
add_shortcode('exercise-progress','dg_exercise_progress');
function dg_exercise_progress() {

	ob_start();

	// Silence is golden

	$user_id        = get_current_user_id();
    $meta_key       = 'dg_track_guitar_exercises';
    $tab_analytics  = get_user_meta( $user_id, $meta_key, FALSE )[0];
    $args 			= array('child_of' => 23097, 'sort_column' => 'menu_order');
    $exercise_pages = get_pages($args);

    $progress['incomplete'] 	= 0;
    $progress['in-progress'] 	= 0;
    $progress['complete'] 		= 0;
    $total_number_exercises 	= 0;

    $experience_levels 			= dg_calculate_experience();
    $experience 				= $experience_levels['total_experience'];
    $level 						= $experience_levels['level'];
    $exp_to_next_level  		= $experience_levels['exp_to_next_level'];
    $total_exp_to_next_level	= $experience_levels['total_exp_to_next_level'];

    foreach($exercise_pages as $exercise) {

    	$post_id 		= $exercise->ID;

    	// if it's a parent page then it's not an exercise so skip through this loop
    	if ( dg_check_if_page_has_children($exercise->ID) == true) {

    		continue;

    	}

    	$tab_plays      = 0;
        $tab_pause      = 0;
        $tab_finished   = 0;

        foreach ($tab_analytics as $tab) {

	        $tab_id  = $tab['id'];

	        if ($post_id == $tab_id) {

	        	$tab_plays      = $tab['play']['count'];
		        $tab_pause      = $tab['pause']['count'];
		        $tab_finished   = $tab['finished']['count'];

		        break;

	        }

    	}

    	$total_number_exercises++;
    	$progress['total'] 		= $total_number_exercises;
    	$status 				= dg_return_exercise_status_rules($tab_plays, $tab_finished);
    	$status 				= $status['status'];
    	$progress[$status] 		= $progress[$status] + 1;

    }



    ?>
    <style>
    .learning-dashboard-stats .text p, .learning-dashboard-stats .hero {
        text-align: center;
        display: block;
    }
    .learning-dashboard-stats .text p {
        font-size: 14px;
    }
    @import "compass/css3";

	.progress {
	  width: 100%;
	  height: 15px;
	}
	.progress-wrap.progress {
	    border-radius: 3px;
	}
	.progress-wrap {
	  background: #b30909;
	  margin-bottom: 25px;
	  overflow: hidden;
	  position: relative;
	}
	.progress-wrap .progress-bar {
	    background: #ddd;
	    left: 0;
	    position: absolute;
	    top: 0;
	}
	.progress-bar-wrappers p {
	    margin-bottom: 5px;
	}
	.next-goal {
	    margin: 50px 0px 100px;
	}
	.exercise-overview-chart {
		display: inline-block;
		width: 100%;
	}
	.next-goal i {
		float: left;
		font-size: 50px;
	    color: #b30909;
	    padding-right: 20px;
	}


    </style>
    <div class="row learning-dashboard-stats">


    	<div class="col-md-4 col-sm-12">

	    	<div class="exercise-overview-chart">

				<div class="wrapper">
					<canvas id="exercise-progress-doughnut-graph" width="200" height="200"></canvas>
				</div>

                <div class="col-md-4 col-xs-4 col-sm-4">
                    <div class="wrapper">
                        <span class="hero styled-subtitle"><?php echo $progress['incomplete'] . '/' . $progress['total'] ?></span>
                        <div class="text"><p class="">Incomplete</p></div>
                    </div>
                </div>

                <div class="col-md-4 col-xs-4 col-sm-4">
                    <div class="wrapper">
                        <span class="hero styled-subtitle"><?php echo $progress['in-progress'] . '/' . $progress['total'] ?></span>
                        <div class="text"><p class="">In Progress</p></div>
                    </div>
                </div>

                <div class="col-md-4 col-xs-4 col-sm-4">
                    <div class="wrapper">
                        <span class="hero styled-subtitle"><?php echo $progress['complete'] . '/' . $progress['total'] ?></span>
                        <div class="text"><p class="">Complete</p></div>
                    </div>
                </div>
			</div>
		</div>

		<div class="col-md-8 col-sm-12">

			<div class="next-goal">
				<i class="fas fa-trophy"></i>
				<div class="styled-subtitle">Your Next Goal</div>
				<p class="subtitle"><?php echo dg_next_goal(); ?></p>
			</div>

            <div class="progress-bar-wrappers">

            	<div class="title-h3">Your Learning Progress</div>

				<p>Completed <?php echo $progress['complete'] . '/' . $progress['total'] ?> Exercise Groups</p>
				<div class="progress-wrap progress" data-progress-percent="<?php echo ($progress['complete'] / $progress['total']) * 100 ?>">
				  <div class="progress-bar progress"></div>
				</div>

	            <p>Level <?php echo $level . '/99' ?></p>
				<div class="progress-wrap progress" data-progress-percent="<?php echo ($level / 99) * 100 ?>">
				  <div class="progress-bar progress"></div>
				</div>

				<p><?php echo number_format($exp_to_next_level) ?>xp Until Level <?php echo $level + 1 ?></p>
				<div class="progress-wrap progress" data-progress-percent="<?php echo ($exp_to_next_level / $total_exp_to_next_level) * 100 ?>">
				  <div class="progress-bar progress"></div>
				</div>

				<p>Total Experience <?php echo number_format($experience) ?>/13,034,431</p>
				<div class="progress-wrap progress" data-progress-percent="<?php echo ($experience / 13034431) * 100 ?>">
				  <div class="progress-bar progress"></div>
				</div>

			</div>

		</div>
	</div>

	<script type="text/javascript">

		var ctxExerciseGraph 	= jQuery('#exercise-progress-doughnut-graph');

		var incomplete 		= <?php echo $progress['incomplete'] ?>;
		var inprogress 		= <?php echo $progress['in-progress'] ?>;
		var complete 		= <?php echo $progress['complete'] ?>;

		var myExerciseChart = new Chart(ctxExerciseGraph, {
		    type: 'doughnut',
		    data: {
		        labels: ['Incomplete', 'In Progress', 'Complete'],
		        datasets: [{
		            label: '# of Times',
		            data: [incomplete, inprogress, complete],
		            backgroundColor: [
		                'rgba(179, 9, 9, 0.33)',
		                'rgba(179, 9, 9, 0.6)',
		                'rgb(179, 9, 9)'
		            ],
		        }]
		    },
		    options: {
		        cutoutPercentage: 70,
		        legend: {
		            display: true,
		            position: 'bottom'
		        }
		    }
		});

		jQuery(document).ready(function() {

			moveProgressBar();

		    // on browser resize...
/*		    jQuery(window).resize(function() {
		        moveProgressBar();
		    });*/

		    // SIGNATURE PROGRESS
		    function moveProgressBar() {
		      console.log("moveProgressBar");

		      jQuery('.progress-wrap').each(function(index) {

			        var getPercent = (jQuery(this).data('progress-percent') / 100);
			        var getProgressWrapWidth = jQuery(this).width();
			        var progressTotal = getPercent * getProgressWrapWidth;
			        var animationLength = 2500;
			        
			        jQuery(this).children('.progress-bar').stop().delay(500 * index).animate({
			            left: progressTotal
			        }, animationLength);

		      });

		    }
		});

	</script>

    <?php
    return ob_get_clean();

}
/*
**
**		@ Description: List of exercises for learning dashboard
**
*/
add_shortcode('exercise-dashboard','dg_exercise_dashboard');
function dg_exercise_dashboard() {

    ob_start();

    if (is_user_logged_in()) {

	    $user_id        = get_current_user_id();
	    $meta_key       = 'dg_track_guitar_exercises';
	    $tab_analytics  = get_user_meta( $user_id, $meta_key, FALSE )[0];
	    
    }

    $args 			= array('child_of' => 23097, 'sort_column' => 'menu_order');
    $exercise_pages = get_pages($args);

    $iteration = 1;

    ?>
    <style type="text/css">
        .stat-wrapper {
            text-align: center;
            margin-bottom: 50px !important;
            background: #f7f7f7;
            box-shadow: 0px 2px 6px 0px #b5b5b5;
		    border-radius: 5px;
		    background: #f7f7f7;
		    text-align: center;
		    overflow: hidden;
        }
        .stats-wrapper {
            padding: 25px 0px;
        }
        .stat-wrapper a {
		    display: inline-block;
		    width: 100%;        
		}
        .stat-wrapper * {
            color: #262626;
        }
        .stat-wrapper .stat {
            display: inline-block;
        }
        .stat-wrapper .stat {
            display: inline-block;
            margin-right: 10px;
            padding-right: 10px;
            border-right: solid 1px #efefef;
            font-size: 14px;
        }
        .stat-wrapper .stat .stat-number {
            font-weight: 600;
        }
        .stat-wrapper i {
            color: #b30909;
        }
        .stat-wrapper .subtitle {
            background: black;
            color: white;
            padding: 10px;
            margin-bottom: 0px;
        }
		span.stat-text {
		    display: none;
		}
		.stat-wrapper .styled-subtitle {
		    padding: 20px 0px;
		    margin: 0px;
		}
		.stat-wrapper *, .stat-wrapper {
			transition: all .2s ease-in-out;
		}
		.stat-wrapper:hover {
		    box-shadow: 0px 10px 10px 0px #a6a6a6;
		    transform: translateY(-12px);
		}
/*		.stat-wrapper:hover {
	    	background: #262626;
		}
		.stat-wrapper:hover * {
		    color: white;
		}
		.stat-wrapper:hover i {
		    color: #b30909;
		}
		.stat-wrapper:hover .current-progress i {
			color: white;
		}*/
		.current-progress p {
		    font-size: 14px;
		    color: white;
		}
		.current-progress {
		    background: #b30909;
		    padding: 5px;
		}
		.current-progress i {
		    color: white;
		    font-size: 40px;
		}
		.current-progress.complete {
		    background-color: #43b309;
		}
		.current-progress.in-progress {
		    background-color: #e46810;
		}
		.stat-wrapper .content-wrapper {
		    padding: 40px 10px;
		}
    </style>
    <?php


    echo '<div class="row">';

    foreach($exercise_pages as $exercise) {

    	$post_id 		= $exercise->ID;

    	$has_children 	= dg_check_if_page_has_children($post_id);

    	if ($has_children == true ) {

    		continue;

    	}

    	$title 			= get_the_title( $post_id );
        $url 			= get_permalink( $post_id );
    	$tab_plays      = 0;
        $tab_pause      = 0;
        $tab_finished   = 0;

        // User specific
        if (is_user_logged_in()) {

	        foreach ($tab_analytics as $tab) {
		        $tab_id  = $tab['id'];

		        if ($post_id == $tab_id) {

		        	$tab_plays      = $tab['play']['count'];
			        $tab_pause      = $tab['pause']['count'];
			        $tab_finished   = $tab['finished']['count'];

			        break;

		        }

	    	}

        } else {

        	$tab_plays      = 0;
			$tab_pause      = 0;
			$tab_finished   = 0;

        }

    	$status 			= dg_return_exercise_status_rules($tab_plays, $tab_finished);
    	$status_response 	= $status['icon'] . '<p>' . $status['response'].'</p>';

        ?>
            <div class="col-md-4">
                <div class="stat-wrapper <?php echo $status['status'] ?>">
                    <a href="<?php echo $url ?>">
                    	<div class="current-progress <?php echo $status['status'] ?>"><?php echo $status_response ?></div>
                    	<div class="content-wrapper">
	                        <p class="styled-subtitle"><?php echo $title ?></p>
	                        <div class="status">Next goal: Complete <?php echo $status['next-goal']; ?> time(s)</div>
	                        <div class="stats-wrapper">
	                            <div class="stat"><span class="stat-text">Played</span> <i class="fas fa-play"></i> <span class="stat-number"><?php echo $tab_plays ?></span></div>
	                            <div class="stat"><span class="stat-text">Paused</span> <i class="fas fa-pause"></i> <span class="stat-number"><?php echo $tab_pause ?></span></div>
	                            <div class="stat"><span class="stat-text">Finished</span> <i class="fas fa-flag"></i> <span class="stat-number"><?php echo $tab_finished ?>/25</span></div>
	                        </div>
                    	</div>
                    </a>
                </div>
            </div>
        <?php

        if ( $iteration % 3 == 0 ) {
        	echo '</div><div class="row">';
        }
        //end of loop
        $iteration++;
    }

    echo '</div>';

    return ob_get_clean();

}

/*
**
**		@Description: Logic to determine the status of an exercise
**
*/
function dg_return_exercise_status_rules($tab_plays, $tab_finished) {

    	if ($tab_plays == 0 && $tab_finished == 0) {

    		$result['status'] 		= 'incomplete';
    		$result['response'] 	= 'Not started';
    		$result['next-goal'] 	= 1;
    		$result['icon'] 		= '<i class="fas fa-times"></i>';

    	} elseif ($tab_plays > 0 && $tab_finished == 0) {

    		$result['status'] 		= 'incomplete';
    		$result['response'] 	= 'Started but not finished';
    		$result['next-goal'] 	= 5;
    		$result['icon'] 		= '<i class="fas fa-exclamation-triangle"></i>';

    	} elseif ($tab_plays > 0 && $tab_finished > 0 && $tab_finished < 5) {

    		$result['status'] 		= 'in-progress';
    		$result['response'] 	= 'In progress';
    		$result['next-goal'] 	= 5;
    		$result['icon'] 		= '<i class="fas fa-ellipsis-h"></i>';

    	} elseif ($tab_plays > 0 && $tab_finished > 4 && $tab_finished < 25) {

    		$result['status'] 		= 'in-progress';
    		$result['response'] 	= 'Gettin\' there';
    		$result['next-goal'] 	= 25;
    		$result['icon'] 		= '<i class="fas fa-ellipsis-h"></i>';

    	} elseif ($tab_plays > 24 && $tab_finished > 24) {

    		$result['status'] 		= 'complete';
    		$result['response'] 	= 'Complete';
    		$result['next-goal'] 	= 25;
    		$result['icon'] 		= '<i class="fas fa-check"></i>';

    	} else {

    		$result['status'] 		= '';
    		$result['response'] 	= '';
    		$result['icon'] 		= '';
    		$result['next-goal'] 	= 0;

    		error_log('some group of tab plays / finished that hasnt got logic. Plays: ' . $tab_plays.'. Finishes: ' . $tab_finished.'.');

    	}

	return $result;

}

function dg_next_goal($user_id = null) {

	if ($user_id == null) {
    	$user_id = get_current_user_id();
	}

	$total_exercise_stats 		= dg_check_total_tab_analytic_stats($user_id);
	$exercise_specific_stats 	= dg_get_all_exercise_data($user_id);
	$total_finished 			= $total_exercise_stats['finish'];
	$total_played 				= $total_exercise_stats['plays'];
	$completed 				 	= 0;

	foreach($exercise_specific_stats as $exercise) {

		if ($exercise['finish'] > 24) {
			$completed++;
		}

	}

	if ($total_played == 0 ) {

		$goal = 'Looks like you\'ve never completed an exericse, try starting your first exercise!';

	} elseif( $total_played > 0 && $total_finished == 0 ) {

		$goal = 'Congratulations you\'re on your way! Try finishing your first exercise.';

	} elseif( $total_finished > 0 && $completed == 0) {

		$goal = 'Try fully completing your first exercise by finishing it 25 times.';

	} elseif( $completed == 1) {

		$goal = 'Congratulations on fully completing your first exercise. Now let\'s try to complete them all!';

	} elseif( $completed > 1) {

		$goal = 'You are absolutely killing it! Congratulations only fully completing '. $completed .' exercise(s). Keep pushing.';

	} else {

		$goal = 'Mate, youre a bloody legend.';

	}

    return $goal;
}