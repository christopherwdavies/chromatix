<?php
/**
 * Email Footer for the Default Template
 *
 * For modifying this template, please see: http://docs.restrictcontentpro.com/article/1738-template-files
 *
 * @package     Restrict Content Pro
 * @subpackage  Templates/Emails/Footer
 * @copyright   Copyright (c) 2017, Restrict Content Pro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.7
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline.
$template_footer = "
	border-top:0;
	-webkit-border-radius:3px;
";

$credit = "
	border:0;
	color: #000000;
	font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
	font-size:12px;
	line-height:125%;
	text-align:center;
";
?>
														</div>
													</td>
                                                </tr>
                                            </table>
                                            <!-- End Content -->
                                        </td>
                                    </tr>
                                </table>
                                <!-- End Body -->
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>