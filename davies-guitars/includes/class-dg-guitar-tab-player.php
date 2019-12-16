<?php
/**
 * Class for building guitar tab player
 *	https://daviesguitars.io/testing/
 *
 * Args and usage: 
 *
 * @package  DaviesGuitars/Classes
 * @version  1.0.0
 * @since    1.0.0
 */

defined( 'ABSPATH' ) || exit;

class DG_Guitar_Tab_Player {

	public function __construct() {

        add_action( 'wp_enqueue_scripts', array($this, 'register_scripts') );
		$this->register_shortcode();

	}

	public function register_shortcode() {

		if ( ! is_admin() ) {

			add_shortcode( 'guitar-tab', array( $this, 'tab_player' ) );

		}

	}

	public function register_scripts() {

	    // JS
		wp_register_script( 'dg-tab-swfobject', DGURLPATH . 'assets/js/guitar-tab/swfobject.js' );
		wp_register_script( 'dg-tab-alphatab', DGURLPATH . 'assets/js/guitar-tab/AlphaTab.js' );
		wp_register_script( 'dg-tab-bootstrap', DGURLPATH . 'assets/js/guitar-tab/bootstrap.min.js' );
		wp_register_script( 'dg-tab-bs-slider', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/bootstrap-slider.min.js' );

	    // CSS
		wp_register_style( 'dg-tab-bootstrap', DGURLPATH . 'assets/css/guitar-tab/bootstrap.css' );
		wp_register_style( 'dg-tab-override', DGURLPATH . 'assets/css/guitar-tab/override.css', array(), time() );
		wp_register_style( 'dg-tab-bs-slider', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.min.css' );

	}

	/** 
	 *
	 *	Good documentation for alphatab
	 *	@link https://docs.alphatab.net/master/examples/stave-configurations/tab
	 *
	 */
	public function tab_player( $atts ) { 

		// Enqueue scripts
		wp_enqueue_script( 'dg-tab-swfobject' );
		wp_enqueue_script( 'dg-tab-alphatab' );
		wp_enqueue_script( 'dg-tab-bootstrap' );
		wp_enqueue_script( 'dg-tab-bs-slider' );

		// Enqueue styles
		wp_enqueue_style( 'dg-tab-bootstrap' );
		wp_enqueue_style( 'dg-tab-bs-slider' );
		wp_enqueue_style( 'dg-tab-override' );

		// Begin building output
        ob_start();

        /**
         *	Decode shortcode atts
         */
        $a = shortcode_atts( array(
        'tab'           => null,
        'dir'           => 'songs',
        'guitar-god-tab' => false
        ), $atts );

        /**
         *	Setup vars
         */
        $tab            	= $a['tab'];
        $dir            	= $a['dir'];
        $guitar_god_tab     = $a['guitar-god-tab'];
        $tabpath    		= '/wp-content/uploads/guitar-tabs/';
        $locked_content 	= FALSE;

        /**
         *	Check user priviledges
         */
        $guitar_god_member = dg_is_user_guitar_god();
        $purchased_exercises = dg_has_user_bought( 'exercises' );
        if ($guitar_god_member || $purchased_exercises) {

			$user_has_access = TRUE;

        } else {

        	$user_has_access = FALSE;

        }

        /**
         *	Setup directory
         */
        if ($dir == 'lessons') {

            $tabpath = $tabpath.'lessons/';

        } elseif ($dir == 'exercises') {

            $tabpath = $tabpath.'exercises/';

        }  elseif ($dir == 'songs') {

            $tabpath = $tabpath.'songs/';

        } elseif ($dir == 'absolute') {

            $tabpath = null;

        } else {

            $tabpath = $tabpath.'songs/';

        }


        /*
         *  If we've entered a guitar god tab AND the user has guitar god status
         */
        if ( $guitar_god_tab && $user_has_access ) {

            $tab = $guitar_god_tab;

        }
        if ($guitar_god_tab) {

        	$locked_content = TRUE;

        } ?>

		<body>
		    <!-- Toolbar containing the buttons -->
		    <nav class="navbar navbar-default navbar-fixed-top">
		      <div class="container">
		        <div id="navbar" class="navbar-collapse collapse">
		        <ul class="dropdown-menu-custom" id="trackList" style="display: none;"></ul>
		          <!-- Player controls -->
		          <ul class="nav navbar-nav">
		            <li><button id="playPause" disabled="disabled" class="btn btn-link navbar-btn fa fa-play"></button></li>
		            <li><button id="stop" disabled="disabled" class="btn btn-link navbar-btn fa fa-stop"></button></li>
		            <li class="dropdown">
		              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Speed: <span id="playbackSpeed">100%</span> <span class="caret"></span></a>
		              <ul class="dropdown-menu" id="playbackSpeedSelector">
		                <li><a href="#" data-value="0.25">25%</a></li>
		                <li><a href="#" data-value="0.5">50%</a></li>
		                <li><a href="#" data-value="0.6">60%</a></li>
		                <li><a href="#" data-value="0.7">70%</a></li>
		                <li><a href="#" data-value="0.8">80%</a></li>
		                <li><a href="#" data-value="0.9">90%</a></li>
		                <li><a href="#" data-value="1">100%</a></li>
		                <li><a href="#" data-value="1.1">110%</a></li>
		                <li><a href="#" data-value="1.25">125%</a></li>
		                <li><a href="#" data-value="1.5">150%</a></li>
		                <li><a href="#" data-value="2">200%</a></li>
		              </ul>
		            </li>
		            <li><a href="#" id="looping" class="fa fa-refresh"><span>Loop</span></a></li>
		            <li><a href="#" id="metronome" class="fas fa-stopwatch" data-toggle="tooltip" data-placement="bottom" title="Metronome"><span>Metronome</span></a></li>
		          </ul>
		          <ul class="nav navbar-nav navbar-right">
		            <!-- Loading indicator for soundfont -->
		            <li id="soundFontProgressMenuItem">
		                <span class="navbar-text">Loading...</span>
		            </li>
		            <!-- Print Button -->
		            <li><a href="#" id="print">Print</a></li>
		            <!-- Track Selector -->
		            <li class="dropdown">
		                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="currentTrack">
		                    Tracks
		                </a>
		            </li>            
		            <!-- Buttons for changing the layout -->
		            <li class="active"><a href="#" id="page" data-layout="page" data-scrollmode="vertical">Page View</a></li>
		            <li><a href="#" id="horizontalBarwise" data-layout="horizontal" data-scrollmode="horizontal-bar">Horizontal</a></li>
		            <li><a href="#" id="horizontalOffscreen" data-layout="horizontal" data-scrollmode="horizontal-offscreen">Horizontal</a></li>
		          </ul>
		        </div>
		      </div>
		    </nav>

		    <div class="buttons-wrapper tab-player">
		        
		        <?php echo do_shortcode('[practice-reminder-email]'); ?>

		        <?php if ( ! $user_has_access && $locked_content ) {

		            echo '<a href="#disabled" class="gem-button download-tab-button small grey guitar-god-popup" download>Download Tab</a>';

		        } elseif ( is_user_logged_in() ) {

		            echo '<a href="' . $tabpath . $tab . '" class="gem-button download-tab-button small grey" download>Download Tab</a>';

		        } else {

		            echo '<div class="gem-button download-tab-button logged-in-popup small grey">Download Tab</div>';

		        } ?>
		        
		    </div>

		    <div class="members-notice">
		        <?php 
		            if ($dir == 'exercises') {

		                //
		                if ( ! $user_has_access ) {
		                    ?>
		                        <p style="text-align:center;" class="non-member"><span class="members-notice-tabs"><i class="fas fa-lock"></i></span>You are currently viewing our free exercises. Gain full access by becoming a <a href="/become-guitar-god/">Guitar God</a>.</p>
		                    <?php
		                }             
		            }
		        ?>
		    </div>

		    <?php if ( ! $user_has_access && $locked_content ) {

		    	echo '<div class="tab-placeholder"><div class="title-h1">This Content Is Locked</div><p class="subtitle">You can unlock this content by becoming a <a href="/become-guitar-god/">Guitar God</a>.</p><a href="/become-guitar-god/" class="gem-button medium">Unlock Content</a></div>';

		    	// End output here if not allowed to view
		    	return ob_get_clean();

		    } ?>

		    <!-- Main container -->
		    <div id="alphaTab"  data-file="<?php echo $tabpath.$tab ?>"
		                        data-player="<?php echo DGURLPATH . 'assets/js/guitar-tab/default.sf2'; ?>"
		                        data-player-offset="[-10, -70]"
		                        data-tracks="0"
		                        data-staves="tab"
		                        data-layout-hide-info="true"
		                        ></div>

		    <!-- Load settings / init -->
		    <script src="<?php echo DGURLPATH . 'assets/js/guitar-tab/settings.js'; ?>"></script>

		</body>

		<?php return ob_get_clean();

	}

}

/**
 *	Future dev: if option is set to active then init class
 */
$dg_tab_player = new DG_Guitar_Tab_Player();