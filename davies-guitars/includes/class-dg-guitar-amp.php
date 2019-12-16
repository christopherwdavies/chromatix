<?php
/**
 * Class for building guitar amp
 *
 * Args and usage:
 *  @link https://daviesguitars.io/
 *
 * @package  DaviesGuitars/Classes
 * @version  1.0.0
 * @since    1.0.0
 */

defined( 'ABSPATH' ) || exit;

class DG_Guitar_Amp {

	public function __construct() {

        add_action( 'wp_enqueue_scripts', array($this, 'register_scripts') );
		$this->register_shortcode();

	}

	public function register_shortcode() {

		if ( ! is_admin() ) {

			add_shortcode( 'guitar-amp', array( $this, 'amp_shortcode' ) );

		}

	}

	public function register_scripts() {

	    // JS
		wp_register_script( 'dg-amp-adapter', DGURLPATH . 'assets/js/amp/adapter.js' );
		wp_register_script( 'dg-amp-init-audio', DGURLPATH . 'assets/js/amp/initAudio.js', array(), time() );
		wp_register_script( 'dg-amp-visualization', DGURLPATH . 'assets/js/amp/visualization.js' );
		wp_register_script( 'dg-amp-js', DGURLPATH . 'assets/js/amp/amp.js', array(), time() );
		wp_register_script( 'dg-amp-utils', DGURLPATH . 'assets/js/amp/utils.js', array(), time() );
		wp_register_script( 'dg-amp-distorsion-factory', DGURLPATH . 'assets/js/amp/distorsionFactory.js' );
		wp_register_script( 'dg-amp-curve-drawer', DGURLPATH . 'assets/js/amp/curveDrawer.js' );
		wp_register_script( 'dg-amp-webcomponents-lite', DGURLPATH . 'assets/js/amp/webcomponents-lite.min.js' );

	    // CSS
		wp_register_style( 'dg-amp-translate-element', DGURLPATH . 'assets/css/amp/translateelement.css' );
		wp_register_style( 'dg-amp-css', DGURLPATH . 'assets/css/amp/amp.css' );
		wp_register_style( 'dg-amp-custom-css', DGURLPATH . 'assets/css/amp/amp-custom.css', array(), time() );

	}

	public function amp_shortcode() {

		// Enqueue styles
		wp_enqueue_style( 'dg-amp-translate-element' );
		wp_enqueue_style( 'dg-amp-css' );
		wp_enqueue_style( 'dg-amp-custom-css' );

		// Print extra imports
		$this->print_scripts();

		// Enqueue scripts
		wp_enqueue_script( 'dg-amp-adapter' );
		wp_enqueue_script( 'dg-amp-init-audio' );
		wp_enqueue_script( 'dg-amp-visualization' );
		wp_enqueue_script( 'dg-amp-js' );
		wp_enqueue_script( 'dg-amp-utils' );
		wp_enqueue_script( 'dg-amp-distorsion-factory' );
		wp_enqueue_script( 'dg-amp-curve-drawer' );
		wp_enqueue_script( 'dg-amp-webcomponents-lite' );

		return $this->amp_body();

	}

	public function print_scripts() {

	    echo '<link rel="import" href="' . DGURLPATH . 'assets/other/polymer.html">';
	    echo '<link rel="import" href="' . DGURLPATH . 'assets/other/webaudio-controls.html">';

	}

	public function amp_body() { ?>

		<?php ob_start(); ?>

		<?php if (current_user_can('administrator')) : ?>
	        <style>
	            #events {
	                display: inline-block !important; 
	            }
	        </style>
		<?php endif; ?>

        <div class="guitar-amp-section class-amp">
            <div class="amp">
            	<div class="sample-player module col-xs-12">
            		<div class="col-md-6 col-xs-12">
	                    <h4>Input</h4>
	                    <div id="demoSample">Choose a demo sample: 
	                        <select id="demoSampleMenu" onchange="changeDemoSample(this.value);">
	                            <option value="12">Master Of Puppets</option>
	                            <option value="13">Funky Guitar Riff</option>
	                            <option value="14">Les Paul Chords</option>
	                            <option value="0">Metal riff 1</option>
	                            <option value="1">Metal riff 2</option>
	                            <option value="4">Trash metal</option>
	                            <option value="5">Black Sabbath Rythm</option>
	                            <option value="6">Black Sabbath Solo</option>
	                            <option value="8">In Bloom riff</option>
	                        </select>
	                    </div>
	                    <audio id="player" controls loop>
	                        <source src="<?php echo DGURLPATH . 'assets/other/audio/Master-Of-Puppets.mp3'; ?>">
	                        Your browser does not support the audio tag.
	                    </audio>
						<!-- 	                     
							<div class="select">
					            <label for="audioSource">Current Input: </label><select id="audioSource"></select>
					        </div> 
				    	-->
	                    <button id="toggleGuitarIn" type="button" class="guitar-inactive gem-button gem-button-size-small gem-button-style-flat gem-button-text-weight-normal gem-button-icon-position-left" onclick="toggleGuitarInput(event)">Activate Guitar Input</button>
	                </div>
	                <div class="input-output-section col-xs-12 col-md-6">
	                	<h4>Monitor I/O</h4>
	                    <div class="input-gain-section col-xs-6">
		                    <webaudio-knob id="Knob9" src="<?php echo DGURLPATH . 'assets/other/img/Prophet.png';?>" value="3" default="1" min="0" max="10" step="0.1" diameter="49" sprites="99" tooltip="InputGain"></webaudio-knob>
		                    <canvas id="inputSignalCanvas" width=600 height=200></canvas>
		                    <p class="in-out" style="color:white;">Input Monitor</p>
	                    </div>
	                    <div class="output-gain-section col-xs-6">
		                    <webaudio-knob id="Knob10" src="<?php echo DGURLPATH . 'assets/other/img/Prophet.png';?>" value="2" default="5" min="0" max="10" step="0.1" diameter="49" sprites="99" tooltip="OutputGain"></webaudio-knob>
		                    <canvas id="outputSignalCanvas" width=600 height=200></canvas>
		                    <p class="in-out" style="color:white;">Output Monitor</p>
	                    </div>
	                </div>
                </div>

                <div class="preamp-eq module col-xs-12">
                    <div class="preset-dropdown">
                    	<h4>Preamp</h4>
                    	<div class="col-md-9">
                    		<label for="QFPresetMenu2">Tone Preset</label>
                    		<select id="QFPresetMenu2"></select>
                    	</div>
                    	<div class="col-md-3">
                    		<div class="power-switch">
                    			<label for="switch1">Bypass</label>
	                    		<webaudio-switch id="switch1"  style="" src="<?php echo DGURLPATH . 'assets/other/';?>img/switch_toggle.png" value="0" height="45" width="45" tooltip="Power On/Off"></webaudio-switch>
	                        	<webaudio-switch id="led" style="" src="<?php echo DGURLPATH . 'assets/other/';?>img/led_23_red.png" value="1" height="23" width="23" tooltip="Switch-B"></webaudio-switch>
                    		</div>
                    	</div>
                    </div>

                    <div class="first-section col-md-6 col-xs-12">
	                    <div class="col-xs-3">
                            <webaudio-switch style="color:white;" id="toggleBoost" value="0" src="<?php echo DGURLPATH . 'assets/other/';?>img/boostSwitch.png" tooltip="Toggle clean/disto channel"></webaudio-switch>
                        </div>

                        <webaudio-knob id="Knob1"   step="0.1" src="<?php echo DGURLPATH . 'assets/other/';?>img/Prophet.png" class="col-xs-3" value="7"  min="0" max="10" diameter="69" sprites="99" tooltip="Volume" ></webaudio-knob>

                        <webaudio-knob id="Knob3" src="<?php echo DGURLPATH . 'assets/other/';?>img/Prophet.png" class="col-xs-3" value="0" step="0.1" min="0" max="10" diameter="69" sprites="99" tooltip="Drive"></webaudio-knob>

                    	<webaudio-knob id="Knob4" src="<?php echo DGURLPATH . 'assets/other/';?>img/Prophet.png" class="col-xs-3" value="5" min="0" max="10" step="0.1" diameter="69" sprites="99" tooltip="Bass"></webaudio-knob>

                    </div>

                    <div class="second-section col-md-6 col-xs-12">

                        <webaudio-knob id="Knob5" src="<?php echo DGURLPATH . 'assets/other/';?>img/Prophet.png" class="col-xs-3" value="5" min="0" max="10" step="0.1" diameter="69" sprites="99" tooltip="Middle"></webaudio-knob>

                        <webaudio-knob id="Knob6" src="<?php echo DGURLPATH . 'assets/other/';?>img/Prophet.png" class="col-xs-3" value="5" min="0" max="10" step="0.1" diameter="69" sprites="99" tooltip="Treble"></webaudio-knob>

                        <webaudio-knob id="Knob8" src="<?php echo DGURLPATH . 'assets/other/';?>img/Prophet.png" class="col-xs-3" value="5" min="0" max="10" step="0.1" diameter="69" sprites="99" tooltip="Presence"></webaudio-knob>

                        <webaudio-knob id="Knob2"  step="0.1" src="<?php echo DGURLPATH . 'assets/other/';?>img/Prophet.png" class="col-xs-3" value="2" min="0" max="10" diameter="69" sprites="99" tooltip="Master"></webaudio-knob>
                	</div>
                </div>

                <div class="eq-sliders module col-xs-12">
                	<?php echo do_shortcode('[lock-content-fill-logged-out-users]'); ?>
                    <h4>Equalizer</h4>
                    <webaudio-slider id="slider1" class="col-xs-2" style="" src="<?php echo DGURLPATH . 'assets/other/';?>img/vsliderbody.png" knobsrc="<?php echo DGURLPATH . 'assets/other/';?>img/vsliderknob.png" value="-19" min="-30" max="30" step="1" basewidth="24" baseheight="128" knobwidth="24" knobheight="24" ditchLength="100" tooltip="Slider1"></webaudio-slider>
                    <webaudio-slider id="slider2" class="col-xs-2" Units="Db" style="" src="<?php echo DGURLPATH . 'assets/other/';?>img/vsliderbody.png" knobsrc="<?php echo DGURLPATH . 'assets/other/';?>img/vsliderknob.png" value="0" min="-30" max="30" step="1" basewidth="24" baseheight="128" knobwidth="24" knobheight="24" ditchLength="100"  tooltip="Slider2"></webaudio-slider>
                    <webaudio-slider id="slider3" class="col-xs-2" Units="Db" style="" src="<?php echo DGURLPATH . 'assets/other/';?>img/vsliderbody.png" knobsrc="<?php echo DGURLPATH . 'assets/other/';?>img/vsliderknob.png" value="0" min="-30" max="30" step="1" basewidth="24" baseheight="128" knobwidth="24" knobheight="24" ditchLength="100" tooltip="Slider3"></webaudio-slider>
                    <webaudio-slider id="slider4" class="col-xs-2" Units="Db" style="" src="<?php echo DGURLPATH . 'assets/other/';?>img/vsliderbody.png" knobsrc="<?php echo DGURLPATH . 'assets/other/';?>img/vsliderknob.png" value="0" min="-30" max="30" step="1" basewidth="24" baseheight="128" knobwidth="24" knobheight="24" ditchLength="100" units="%" tooltip="Slider4"></webaudio-slider>
                    <webaudio-slider id="slider5" class="col-xs-2" Units="Db" style="" src="<?php echo DGURLPATH . 'assets/other/';?>img/vsliderbody.png" knobsrc="<?php echo DGURLPATH . 'assets/other/';?>img/vsliderknob.png" value="0" min="-30" max="30" step="1" basewidth="24" baseheight="128" knobwidth="24" knobheight="24" ditchLength="100" tooltip="Slider5"></webaudio-slider>
                    <webaudio-slider id="slider6" class="col-xs-2" Units="Db" style="" src="<?php echo DGURLPATH . 'assets/other/';?>img/vsliderbody.png" knobsrc="<?php echo DGURLPATH . 'assets/other/';?>img/vsliderknob.png" value="-25" min="-30" max="30" step="1" basewidth="24" baseheight="128" knobwidth="24" knobheight="24" ditchLength="100" units="%" tooltip="Slider6"></webaudio-slider>
                    <div class="col-xs-12 switches">
                        <webaudio-switch id="switch2" style="" src="<?php echo DGURLPATH . 'assets/other/';?>img/switch_toggle.png" value="0" height="45" width="45" tooltip="EQ In"></webaudio-switch>
                    </div>
                </div>

                <div class="distortion-visualisations module col-xs-12 hidden">
                	<h4>Distortion Functions</h4>
                    <div style="color:white;" class="transfer-function col-xs-12 col-md-6">
                    	<div class="canvas-container">
                        	<canvas id="distoDrawerCanvas1" style="margin-right:10px;left-margin:10px;" width="100" height="100"></canvas>
                        	<canvas id="signalDrawerCanvas1" width="100" height="100"></canvas>
                    	</div>
                        <select style="" id="distorsionMenu1"></select>
                    </div>
                    <div style="color:white;" class="transfer-function col-xs-12 col-md-6">
                    	<div class="canvas-container">
                        	<canvas id="distoDrawerCanvas2" style="margin-right:10px;left-margin:10px;" width="100" height="100"></canvas>
                        	<canvas id="signalDrawerCanvas2" width="100" height="100"></canvas>
                    	</div>
                        <select style="" id="distorsionMenu2"></select>
                    </div>
                </div>
            </div> <!-- End amp -->

            <div class="eq-section">
                <div class="frequencyslider module col-xs-12">
                    <div class="controls">
                        <label>lowShelf1 Freq</label>
                        <input id="lowShelf1FreqSlider" type="range" value="720" step="1" min="500" max="1000" oninput="amp.changeLowShelf1FrequencyValue(this.value, 0);"></input>
                        <output id="lowShelf1Freq">720 Hz</output>
                    </div>
                    <div class="controls">
                        <label>lowShelf1 Gain</label>
                        <input id="lowShelf1GainSlider" type="range" value="-6" step="0.1" min="-10" max="0" oninput="amp.changeLowShelf1GainValue(this.value, 0);"></input>
                        <output id="lowShelf1Gain">-6 dB</output>
                    </div>
                    <div class="controls">
                        <label>lowShelf2 Freq</label>
                        <input id="lowShelf2FreqSlider" type="range" value="320" step="1" min="300" max="400" oninput="amp.changeLowShelf2FrequencyValue(this.value, 0);"></input>
                        <output id="lowShelf2Freq">320 Hz</output>
                    </div>
                    <div class="controls">
                        <label>lowShelf2 Gain</label>
                        <input id="lowShelf2GainSlider" type="range" value="-6" step="0.1" min="-12" max="10" oninput="amp.changeLowShelf2GainValue(this.value, 0);"></input>
                        <output id="lowShelf2Gain">-5 dB</output>
                    </div>

                    <div class="controls">
                        <label>Stage 1 Gain</label>
                        <input id="preampStage1GainSlider" type="range" value="0.1" step="0.01" min="0" max="10" oninput="amp.changePreampStage1GainValue(this.value, 0);"></input>
                        <output id="preampStage1Gain">1</output>
                    </div>

                    <div class="controls">
                        <label>HighPass1 freq</label>
                        <input id="highPass1FreqSlider" type="range" value="6" step="1" min="5" max="7" oninput="amp.changeHighPass1FrequencyValue(this.value, 0);"></input>
                        <output id="highPass1Freq">6 Hz</output>
                    </div>
                    <div class="controls">
                        <label>HighPass1 Q</label>
                        <input id="highPass1QSlider" type="range" value="0.7071" step="0.001" min="0" max="5" oninput="amp.changeHighPass1QValue(this.value, 0);"></input>
                        <output id="highPass1Q">0.7071</output>
                    </div>

                    <div class="controls">
                        <label>lowShelf3 Freq</label>
                        <input id="lowShelf3FreqSlider" type="range" value="720" step="1" min="300" max="1000" oninput="amp.changeLowShelf3FrequencyValue(this.value, 0);"></input>
                        <output id="lowShelf3Freq">720 Hz</output>
                    </div>
                    <div class="controls">
                        <label>lowShelf3 Gain</label>
                        <input id="lowShelf3GainSlider" type="range" value="-6" step="0.1" min="-10" max="0" oninput="amp.changeLowShelf3GainValue(this.value, 0);"></input>
                        <output id="lowShelf3Gain">-6 dB</output>
                    </div>

                    <div class="controls">
                        <label>Stage 2 Gain</label>
                        <input id="preampStage2GainSlider" type="range" value="1" step="0.01" min="0" max="10" oninput="amp.changePreampStage2GainValue(this.value, 0);"></input>
                        <output id="preampStage2Gain">1</output>
                    </div>

 

                    <p style="display:none;">DRIVE : distorsion levels</p>
                    <div class="controls">
                        <label>K1</label>
                        <input id="K1slider" type="range" value="4" step="0.1" min="0" max="10" oninput="amp.changeDistorsionValues(this.value, 0);"></input>
                        <output id="k0">4</output>
                    </div>

                    <div class="controls">
                        <label>K2</label>
                        <input id="K2slider" type="range" value="4" step="0.1" min="0" max="10" oninput="amp.changeDistorsionValues(this.value, 1);"></input>
                        <output id="k1">4</output>
                    </div>

                </div>

                <div class="cab-impulse-section module col-xs-12">
                	<?php echo do_shortcode('[lock-content-fill-logged-out-users]'); ?>
                    <h4>Cabinet impulse</h4> 
                    <div class="col-md-3">
                    	<div class="cab-impulse-slider">
                        <label for="convolverCabinetSlider">Cab IR Mix</label>
                        <output id="cabinetGainOutput">2</output>
                        <input type="range" min="0" max="10" step="0.1" value="7.5" id="convolverCabinetSlider" oninput="amp.changeRoom(this.value);"/>
                    </div>
                    </div>
                    <div class="col-md-9">
                    	<select id="cabinetImpulses"></select>
                	</div>

                </div>

                <div class="reverb-impulse-section module col-xs-12">
                	<?php echo do_shortcode('[lock-content-fill-logged-out-users]'); ?>
                    <h4>Reverb</h4>
                    <webaudio-knob id="Knob7" src="<?php echo DGURLPATH . 'assets/other/';?>img/Prophet.png" class="col-md-3 col-xs-12" value="2" min="0" max="10" step="0.1" diameter="69" sprites="99" tooltip="Reverb"></webaudio-knob>
                    <div class="col-md-9">
                    	<select id="reverbImpulses"></select>
                	</div>
                </div>
        
	            <div class="compressor-section col-xs-12 module hidden">
	            	<div class="comp">
	            		<h4>Compressor</h4>
	                	<webaudio-knob class="col-md-3 col-xs-12" id="Knob11" step="1" src="<?php echo DGURLPATH . 'assets/other/';?>img/Prophet.png" value="-12" min="-100" max="0" diameter="69" sprites="99" tooltip="Comp Input"></webaudio-knob>
	                	<div class="col-md-9 col-xs-12">
	                		<div id="gain-reduction">
	                			Gain Reduction: 0 DB
	                		</div>
	                	</div>
	                </div>
	            </div>

                <div class="onoffswitch module col-xs-12 hidden">
                    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch"  onclick="amp.changeOversampling(this);">
                    <label class="onoffswitch-label" for="myonoffswitch">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>


            </div>
            <!-- end eq --> 

        </div>
        <!-- End large container -->
        <div class="events col-xs-12 module" id="events" style="background: black;"></div>


        </div>  <!-- End of total amp section -->


        <div id="help-button" class="hints-button">
	        	<span>Help</span>
	        </div>
	        <div id="help-info" class="hints-slide">
	        	<span id="close-help-info" class="close-hints-tab"></span>
	        	<div class="scroll-container container">
	        		<h3>Need A Hand?</h3>
	        		<h4>The audio player isn't working</h4>
	        		<p>Clicking this button <span class="restart-context">here</span> should help to fix that.</p>

	        		<h4>My Guitar Isn't Working</h4>
	        		<p>You may have not accepted the microphone input permissions.
	        		Check your browser's permission to see if our domain name has been allowed to access the microphone.</p>
	        		<p>You will also need to make sure you have selected the correct input for your computer. Start by making sure you are getting an input signal in your operating system before troubleshooting here.</p>

	        		<h4>There's a lag on my tone</h4>
	        		<p>You can ease that a bit by setting your audio driver to 16bit / 44.1khz however there is always going to be a delay in time from the signal running through the chain.
	        			<br>We are working to reduce this lag as much as possible.</p>

	        		<h4>I'm getting an unpleasant crackling sound</h4>
	        		<p>Your signal is probably clipping. This means the volume of your signal has exceeded the digital limits.
	        		<br>Reduce the input gain if your input signal is clipping or alternatively reduce the output gain if it is the output.</p>

	        		<h4>Something else not covered?</h4>
	        		<p>We would very much encourage you to give us feedback using the form at the bottom of the page.<br>
	        		We will continue working to make this as great as possible</p>
	        	</div>
	        </div>

	        <?php if (current_user_can('administrator')) : ?>

	            <a class="gem-button small gem-button-style-flat gem-button-text-weight-normal gem-button-icon-position-left" href="original/" target="_blank">Original Amp</a>

	            <button class="current-preset-button gem-button small gem-button-style-flat gem-button-text-weight-normal gem-button-icon-position-left" style="" type="button" onclick="amp.printCurrentAmpValues();">Print Settings</button>

	            <button class="current-preset-button gem-button small gem-button-style-flat gem-button-text-weight-normal gem-button-icon-position-left" style="" type="button" onclick="resumeAudioContext()">Reload</button>

	        <?php endif ?>

		<?php

		// Print amp footer settings
		$this->amp_footer();

		// Return results
		return ob_get_clean();

	}

	public function amp_footer() {

		?>

		<script type='text/javascript'>

	        jQuery('.restart-context').click(function(){
            	resumeAudioContext();
            });

			//Open help slider
			jQuery( "#help-button" ).click(function() {   
			    if( jQuery('#help-info').hasClass("show")) {
			        jQuery('#help-info').removeClass( "show" );
			    }
			    else {
			       	jQuery('#help-info').addClass( "show" );
			    }
			});

			// Close help slider
			jQuery( "#close-help-info" ).click(function() {   
			    if( jQuery('#help-info').hasClass("show")) {
			        jQuery('#help-info').removeClass( "show" );
			    }
			    else {
			       	jQuery('#help-info').addClass( "show" );
			    }
			});

		</script>

		<script type="text/javascript">
            // Binding avec le vrai ampli
            // Volume
            var volumeOutputPreAmp = document.querySelector("#Knob1");
            volumeOutputPreAmp.addEventListener("change", function (evt) {
                if(amp !== undefined)
                    amp.changeOutputGain(evt.target.value);
            });

            // Master Volume
            var masterVolume = document.querySelector("#Knob2");
            masterVolume.addEventListener("change", function (evt) {
                if(amp !== undefined)
                    amp.changeMasterVolume(evt.target.value);
            });

            // Drive
            var drive = document.querySelector("#Knob3");
            drive.addEventListener("change", function (evt) {
                if(amp !== undefined)
                    amp.changeDrive(evt.target.value);
            });

            // Bass
            var bass = document.querySelector("#Knob4");
            bass.addEventListener("change", function (evt) {
                if(amp !== undefined)
                    amp.changeBassFilterValue(evt.target.value);
            });

            // Middle
            var mid = document.querySelector("#Knob5");
            mid.addEventListener("change", function (evt) {
                if(amp !== undefined)
                    amp.changeMidFilterValue(evt.target.value);
            });

            // Treble
            var treble = document.querySelector("#Knob6");
            treble.addEventListener("change", function (evt) {
                if(amp !== undefined)
                    amp.changeTrebleFilterValue(evt.target.value);
            });

            // Reverb
            var reverb = document.querySelector("#Knob7");
            reverb.addEventListener("change", function (evt) {
                if(amp !== undefined)
                    amp.changeReverbGain(evt.target.value);
            });

            // Presence 
            var presence = document.querySelector("#Knob8");
            presence.addEventListener("change", function (evt) {
                if(amp !== undefined)
                    amp.changePresenceFilterValue(evt.target.value);
            });

            // input gain
            var inputGain = document.querySelector("#Knob9");
            inputGain.addEventListener("change", function (evt) {
                if(amp !== undefined)
                    amp.changeInputGainValue(evt.target.value);
            });

        	// output gain
            var outputGain = document.querySelector("#Knob10");
            outputGain.addEventListener("change", function (evt) {
                if(amp !== undefined)
                    amp.changeOutputGainValue(evt.target.value);
            });

            // Comp Input
            var compThresh = document.querySelector("#Knob11");
            compThresh.addEventListener("change", function (evt) {
                if(amp !== undefined)
                    amp.changeCompThresh(evt.target.value);
            });

            // Equalizer
            for (var i = 1; i < 7; i++) {

                createListenerForEQ(i);
            }

            function createListenerForEQ(i) {
                var eq = document.querySelector("#slider" + i);
                eq.addEventListener("change", function (evt) {
                    if(amp !== undefined)
                        amp.eq.changeGain(evt.target.value, i - 1);
                });
                eq.addEventListener("click", function (evt) {
                	ga('send', 'event', 'Online Amp', 'click', 'Clicked EQ Slider');
                });
            }

            // On / Off switch
            var onOff = document.querySelector("#switch1");
            onOff.addEventListener("change", function (evt) {
                if(amp === undefined) return;
                
                var state = {};
                if (evt.target.value == 1) {
                    state.checked = false;
                } else {
                    state.checked = true;
                }
                amp.bypass(state);
                ga('send', 'event', 'Online Amp', 'click', 'Clicked Bypass Amp');
            });



            // EQ on/off switch
            var eqOnOff = document.querySelector("#switch2");
            eqOnOff.addEventListener("change", function (evt) {
                var state = {};
                if (evt.target.value == 1) {
                    state.checked = false;
                } else {
                    state.checked = true;
                }
                amp.bypassEQ(state);
                ga('send', 'event', 'Online Amp', 'click', 'Clicked Toggle Eq');
            });

            //Boost / overdrive switch
            var boostOnOff = document.querySelector("#toggleBoost");
            boostOnOff.addEventListener("change", function (evt) {
                var state = {};
                if (evt.target.value == 1) {
                    state.checked = false;
                } else {
                    state.checked = true;
                }
                amp.boostOnOff(state);
                ga('send', 'event', 'Online Amp', 'click', 'Clicked Amp Boost / Overdrive');
            });

            // Restart audio context
            function resumeAudioContext() {
                audioContext.resume();
                console.log('Audio Context Restarted');
            	ga('send', 'event', 'Online Amp', 'click', 'Clicked Restart Audio Context');
            }

            // Clicked on audio player
            var playerGA = document.querySelector("#player");
            playerGA.addEventListener("play", function () {
            	console.log('clicked audio');
                document.getElementById('player').muted = false;
                audioContext.resume();
            	ga('send', 'event', 'Online Amp', 'click', 'Clicked Play Audio Player');
            });

            // Clicked on audio player
            var guitarGA = document.querySelector("#toggleGuitarIn");
            guitarGA.addEventListener("click", function () {
            	ga('send', 'event', 'Online Amp', 'click', 'Clicked Activate Guitar');
            	console.log('clicked activate guitar');
            });


            // Clicked on audio player
            var helpButton = document.querySelector("#help-button");
            helpButton.addEventListener("click", function () {
            	ga('send', 'event', 'Online Amp', 'click', 'Clicked Help Button');
            	console.log('clicked help button');
            });
        </script>

		<?php

	}

}
/**
 *
 *
 *	Future dev: if option is set to active then init class
 *
 *
*/
$dg_amp = new DG_Guitar_Amp();