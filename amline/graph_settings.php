<?php
 /**
 * graph_settings.php
 *
 * @package IEMS
 * @subpackage AMCharts [AMLine]
 * @name Export
 * @author Antanas Marcelionis <a href="amcharts.com">amcharts.com</a>
 * @copyright Copyright Antanas Marcelionis.
 * @version 2.0
 * @access public
 *
 * @abstract This is mainly here for reference -- it is not used by IEMS.
 *
 */
// Value between [] brackets, for example [#FFFFFF] shows default value which is used if this parameter is not set -->
// This means, that if you are happy with this value, you can delete this line at all and reduce file size -->
// value or explanation between () brackets shows the range or type of values you should use for this parameter -->
$labelFrequency = 12;
$xGridCount = 288;

?>
	<settings> 
	  <width></width>                                             // [] (Number) if empty, will be equal to width of your flash movie -->
	  <height></height>                                           // [] (Number) if empty, will be equal to height of your flash movie -->
	
	  <data_type></data_type>                                     // [xml] (xml / csv) -->
	  <type></type>                                               // [line] (line, stacked, 100% stacked) -->  
	  <csv_separator></csv_separator>                             // [;] (string) csv file data separator (you need it only if you are using csv file for your data) -->
	  <skip_rows></skip_rows>                                     // [0] (Number) if you are using csv data type, you can set the number of rows which should be skipped here -->       
	  <font></font>                                               // [Arial] (font name) use device fonts, such as Arial, Times New Roman, Tahoma, Verdana... -->
	  <text_size></text_size>                                     // [11] (Number) text size of all texts. Every text size can be set individually in the settings below -->
	  <text_color></text_color>                                   // [#000000] (hex color code) main text color. Every text color can be set individually in the settings below-->
	  <decimals_separator>.</decimals_separator>                   // [,] (string) decimal separator. Note, that this is for displaying data only. Decimals in data xml file must be separated with dot -->
	  <thousands_separator>,</thousands_separator>                 // [ ] (string) thousand separator -->
	  <redraw>true</redraw>                                           // [false] (true / false) if your chart\'s width or height is set in percents, and redraw is set to true, the chart will be redrawn then screen size changes -->
																  // Legend, buttons labels will not be repositioned if you set your x and y values for these objects -->  
	  <reload_data_interval></reload_data_interval>               // [0] (Number) how often data should be reloaded (time in seconds) If you are using this feature I strongly recommend to turn off zoom function (set <zoomable>false</zoomable>) -->
	  <add_time_stamp></add_time_stamp>                           // [false] (true / false) if true, a unique number will be added every time flash loads data. Mainly this feature is useful if you set reload _data_interval >0 -->
	  <precision></precision>                                     // [2] (Number) shows how many numbers should be shown after comma for calculated values (percents, used only in stacked charts) --> 
	  <connect></connect>                                         // [false] (true / false) whether to connect points if y data is missing -->
	  <hide_bullets_count></hide_bullets_count>                   // [] (Number) if there are more then hideBulletsCount points on the screen, bullets can be hidden, to avoid mess. Leave empty, or 0 to show bullets all the time. This rule doesn\'t influence if custom bullet is defined near y value, in data file -->
	  <export_image_file></export_image_file>                     // [] (filename) If you set filename here context menu (then user right clicks on flash movie) "Export as image" will appear. This will allow user to export chart as an image. For php users, use amline/export.php, for .net - amline/export.aspx -->  
																  // Note, that this works only on a web server -->
	  <link_target></link_target>                                 // [] (_blank, _top ...) -->
	  
	  <background>                                                // BACKGROUND -->
		<color></color>                                           // [#FFFFFF] (hex color code) -->
		<alpha>0</alpha>                                           // [0] (0 - 100) use 0 if you are using custom swf or jpg for background -->
		<border_color></border_color>                             // [#FFFFFF] (hex color code) -->
		<border_alpha></border_alpha>                             // [0] (0 - 100) -->
		<file></file>                                             // [] (filename) swf or jpg file of a background. Do not use progressive jpg file, it will be not visible with flash player 7 -->
																  // The chart will look for this file in amline_path folder (amline_path is set in HTML) -->
	  </background>
	  
	 
	  <plot_area>                                                 // PLOT AREA (the area between axes) -->
		<color>#CDCDCD</color>                                           // [#FFFFFF](hex color code) -->
		<alpha>100</alpha>                                           // [0] (0 - 100) if you want it to be different than background color, use bigger than 0 value -->
		<border_color></border_color>                             // [#000000] (hex color code) -->                                        
		<border_alpha></border_alpha>                             // [0] (0 - 100) -->
		<margins>                                                 // plot area margins -->
		  <left>40</left>                                           // [60](Number) --> 
		  <top>0</top>                                             // [60](Number) --> 
		  <right>0</right>                                         // [60](Number) --> 
		  <bottom>50</bottom>                                       // [80](Number) --> 
		</margins>
	  </plot_area>
	
	  <scroller>
		<enabled>true</enabled>                                       // [true] (true / false) whether to show scroller when chart is zoomed or not -->
		<y></y>                                                   // [] (Number) Y position of scroller. If not set here, will be displayed above plot area -->    
		<color></color>                                           // [#DADADA] (hex color code) scrollbar color -->
		<alpha></alpha>                                           // [100] (Number) scrollbar alpha -->
		<bg_color></bg_color>                                     // [#F0F0F0] (hex color code) scroller background color -->
		<bg_alpha></bg_alpha>                                     // [100] (Number) scroller background alpha -->
		<height></height>                                         // [10] (Number) scroller height -->    
	  </scroller>
	  
	  <grid>                                                      // GRID -->
		<x>                                                       // vertical grid -->
		  <enabled>true</enabled>                                     // [true] (true / false) -->                                                     
		  <color></color>                                         // [#000000] (hex color code) -->
		  <alpha></alpha>                                         // [15] (0 - 100) -->
		  <dashed></dashed>                                       // [false](true / false) -->
		  <dash_length></dash_length>                             // [5] (Number) -->  
		  <approx_count><?php echo $xGridCount ?></approx_count>                           // [4] (Number) approximate number of gridlines -->
		</x>
		<y_left>                                                  // horizontal grid, Y left axis. Visible only if there is at least one graph assigned to left axis -->
		  <enabled></enabled>                                     // [true] (true / false) -->          
		  <color></color>                                         // [#000000] (hex color code) -->
		  <alpha></alpha>                                         // [15] (0 - 100) -->
		  <dashed></dashed>                                       // [false] (true / false) -->
		  <dash_length></dash_length>                             // [5] (Number) -->
		  <approx_count></approx_count>                           // [10] (Number) approximate number of gridlines -->
		</y_left>
		<y_right>                                                 // horizontal grid, Y right axis. Visible only if there is at least one graph assigned to right axis -->
		  <enabled></enabled>                                     // [true] (true / false) -->          
		  <color></color>                                         // [#000000] (hex color code) -->
		  <alpha></alpha>                                         // [15] (0 - 100) -->
		  <dashed></dashed>                                       // [false] (true / false) -->
		  <dash_length></dash_length>                             // [5] (Number) -->
		  <approx_count></approx_count>                           // [10] (Number) approximate number of gridlines -->
		</y_right>        
	  </grid>
	  
	  <values>                                                    // VALUES -->
		<x>                                                       // x axis -->
		  <enabled></enabled>                                     // [true] (true / false) -->
		  <rotate>90</rotate>                                       // [0] (0 - 90) angle of rotation. If you want to rotate by degree from 1 to 89, you must have font.swf file in fonts folder -->      
		  <frequency><?php echo $labelFrequency ?></frequency>                                 // [1] (Number) how often values should be placed, 1 - near every gridline, 2 - near every second gridline... -->
		  <skip_first>true</skip_first>                               // [false] (true / false) to skip or not first value -->
		  <skip_last>true</skip_last>                                 // [false] (true / false) to skip or not last value -->
		  <color></color>                                         // [text_color] (hex color code) -->
		  <text_size>10</text_size>                                 // [text_size] (Number) -->    
		</x>
		<y_left>                                                  // y left axis -->
		  <enabled></enabled>                                     // [true] (true / false) -->    
		  <rotate></rotate>                                       // [0] (0 - 90) angle of rotation. If you want to rotate by degree from 1 to 89, you must have font.swf file in fonts folder -->
		  <min></min>                                             // [] (Number) minimum value of this axis. If empty, this value will be calculated automatically. -->
		  <max></max>                                             // [] (Number) maximum value of this axis. If empty, this value will be calculated automatically -->
		  <strict_min_max></strict_min_max>                       // [false] (true / false) by default, if your values are bigger then defined max (or smaller then defined min), max and min is changed so that all the chart would fit to chart area. If you don\'t want this, set this option to false. -->
		  <frequency></frequency>                                 // [1] (Number) how often values should be placed, 1 - near every gridline, 2 - near every second gridline... -->
		  <skip_first></skip_first>                               // [true] (true / false) to skip or not first value -->
		  <skip_last></skip_last>                                 // [false] (true / false) to skip or not last value -->
		  <color></color>                                         // [text_color] (hex color code) --> 
		  <text_size>10</text_size>                                 // [text_size] (Number) -->
		  <unit></unit>                                           // [] (text) unit which will be added to values on y axis-->
		  <unit_position></unit_position>                         // [right] (left / right) -->
		  <integers_only></integers_only>                         // [false] (true / false) if set to true, values with decimals will be omitted -->      
		</y_left>
		<y_right>                                                 // y right axis -->
		  <enabled></enabled>                                     // [true] (true / false) -->    
		  <rotate></rotate>                                       // [0] (0 - 90) angle of rotation. If you want to rotate by degree from 1 to 89, you must have font.swf file in fonts folder -->
		  <min></min>                                             // [] (Number) minimum value of this axis. If empty, this value will be calculated automatically -->
		  <max></max>                                             // [] (Number) maximum value of this axis. If empty, this value will be calculated automatically -->    
		  <strict_min_max></strict_min_max>                       // [false] (true / false) by default, if your values are bigger then defined max (or smaller then defined min), max and min is changed so that all the chart would fit to chart area. If you don\'t want this, set this option to false. -->
		  <frequency></frequency>                                 // [1] (Number) how often values should be placed, 1 - near every gridline, 2 - near every second gridline... -->
		  <skip_first></skip_first>                               // [true] (true / false) to skip or not first value -->
		  <skip_last></skip_last>                                 // [false] (true / false) to skip or not last value -->
		  <color></color>                                         // [text_color] (hex color code) -->
		  <text_size></text_size>                                 // [text_size] (Number) -->
		  <unit></unit>                                           // [] (text) unit which will be added to values on y axis-->
		  <unit_position></unit_position>                         // [right] (left / right) -->
		  <integers_only></integers_only>                         // [false] (true / false) if set to true, values with decimals will be omitted -->            
		</y_right>
	  </values>
	  
	  <axes>                                                      // axes -->
		<x>                                                       // X axis -->
		  <color></color>                                         // [#000000] (hex color code) -->
		  <alpha></alpha>                                         // [100] (0 - 100) -->
		  <width></width>                                         // [2] (Number) line width, 0 for hairline -->
		  <tick_length></tick_length>                             // [7] (Number) -->
		</x>
		<y_left>                                                  // Y left axis, visible only if at least one graph is assigned to this axis -->
		  <color></color>                                         // [#000000] (hex color code) -->
		  <alpha></alpha>                                         // [100] (0 - 100) -->
		  <width></width>                                         // [2] (Number) line width, 0 for hairline -->
		  <tick_length></tick_length>                             // [7] (Number) -->
		</y_left>
		<y_right>                                                 // Y right axis, visible only if at least one graph is assigned to this axis -->
		  <color></color>                                         // [#000000] (hex color code) -->
		  <alpha></alpha>                                         // [100] (0 - 100) -->
		  <width></width>                                         // [2] (Number) line width, 0 for hairline -->
		  <tick_length></tick_length>                             // [7] (Number) -->
		</y_right>
	  </axes>  
	  
	  <indicator>                                                 // INDICATOR -->
		<enabled></enabled>                                       // [true] (true / false) -->
		<zoomable></zoomable>                                     // [true] (true / false) -->
		<color>#CDCDCD</color>                                           // [#BBBB00] (hex color code) line and x balloon background color -->
		<line_alpha></line_alpha>                                 // [100] (0 - 100) -->
		<selection_color>#FF6701</selection_color>                       // [#BBBB00] (hex color code) -->
		<selection_alpha></selection_alpha>                       // [25] (0 - 100) -->
		<x_balloon_enabled></x_balloon_enabled>                   // [true] (true / false) -->
		<x_balloon_text_color></x_balloon_text_color>             // [text_color] (hex color code) -->
		<y_balloon_text_size></y_balloon_text_size>               // [text_color] (hex color code) -->  
		<y_balloon_on_off></y_balloon_on_off>                     // [true] (true / false) whether it is possible to turn on/off y balloon by clicking on graphs or legend. Works only if indicator is enabled -->
		<one_y_balloon></one_y_balloon>                           // [false] (true / false) if you set it to true, only one y balloon will be visible at a time -->             
	  </indicator>
		
	  <legend>                                                    // LEGEND -->
		<enabled>false</enabled>                                       // [true] (true / false) -->
		<x></x>                                                   // [] (Number) if empty, will be equal to left margin -->
		<y></y>                                                   // [] (Number) if empty, will be 20px below x axis values -->
		<width></width>                                           // [] (Number) if empty, will be equal to plot area width -->
		<max_columns></max_columns>                               // [] (Number) the maximum number of columns in the legend --> 
		<color></color>                                           // [#FFFFFF] (hex color code) background color -->
		<alpha></alpha>                                           // [0] (0 - 100) background alpha -->
		<border_color></border_color>                             // [#000000] (hex color code) border color -->
		<border_alpha></border_alpha>                             // [0] (0 - 100) border alpha -->
		<text_color></text_color>                                 // [text_color] (hex color code) -->   
		<text_color_hover></text_color_hover>                     // [#BBBB00] (hex color code) -->    
		<text_size></text_size>                                   // [text_size] (Number) -->
		<spacing></spacing>                                       // [10] (Number) vertical and horizontal gap between legend entries -->
		<margins></margins>                                       // [0] (Number) legend margins (space between legend border and legend entries, recommended to use only if legend border is visible or background color is different from chart area background color) -->    
		<graph_on_off></graph_on_off>                             // [true] (true / false) if true, color box gains "checkbox" function - it is possible to make graphs visible/invisible by clicking on this checkbox -->
		<key>                                                     // KEY (the color box near every legend entry) -->
		  <size></size>                                           // [16] (Number) key size-->
		  <border_color></border_color>                           // [] (hex color code) leave empty if you don\'t want to have border-->
		  <key_mark_color></key_mark_color>                       // [#FFFFFF] (hex color code) key tick mark color -->
		</key>
		<values>                                                  // VALUES -->          
		  <enabled>true</enabled>                                 // [false] (true / false) whether to show values near legend entries or not -->
		  <width>44</width>                                       // [80] (Number) width of text field for value -->
		  <align>left</align>                                     // [right] (right / left) -->
		  <text><![CDATA[: ${value}]]></text>                     // [{value}] ({title} {value} {series} {description} {percents}) You can format any text: {value} will be replaced with value, {description} - with description and so on. You can add your own text or html code too. -->
		 </values>    
	  </legend>  
	  
	  <zoom_out_button>
		<x></x>                                                   // [] (Number) x position of zoom out button, if not defined, will be aligned to right of plot area -->
		<y></y>                                                   // [] (Number) y position of zoom out button, if not defined, will be aligned to top of plot area -->
		<color></color>                                           // [#BBBB00] (hex color code) background color -->
		<alpha></alpha>                                           // [0] (0 - 100) background alpha -->
		<text_color></text_color>                                 // [text_color] (hex color code) button text and magnifying glass icon color -->
		<text_color_hover></text_color_hover>                     // [#BBBB00] (hex color code) button text and magnifying glass icon roll over color -->    
		<text_size></text_size>                                   // [text_size] (Number) button text size -->
		<text></text>                                             // [Show all] (text) -->    
	  </zoom_out_button> 
	   
	  <help>                                                      // HELP button and balloon -->  
		<button>                                                  // help button is only visible if balloon text is defined -->
		  <x></x>                                                 // [] (Number) x position of help button, if not defined, will be aligned to right of chart area -->
		  <y></y>                                                 // [] (Number) y position of help button, if not defined, will be aligned to top of chart area -->
		  <color></color>                                         // [#000000] (hex color code) background color -->
		  <alpha></alpha>                                         // [100] (0 - 100) background alpha -->
		  <text_color></text_color>                               // [#FFFFFF] (hex color code) button text color -->
		  <text_color_hover></text_color_hover>                   // [#BBBB00](hex color code) button text roll over color -->    
		  <text_size></text_size>                                 // [] (Number) button text size -->
		  <text></text>                                           // [?] (text) -->                                 
		</button>    
		<balloon>                                                 // help balloon -->
		  <color></color>                                         // [#000000] (hex color code) background color -->
		  <alpha></alpha>                                         // [100] (0 - 100) background alpha -->
		  <width></width>                                         // [300] (Number) -->
		  <text_color></text_color>                               // [#FFFFFF] (hex color code) button text color -->
		  <text_size></text_size>                                 // [] (Number) button text size -->
		  <text><![CDATA[]]></text>                               // [] (text) some html tags may be used (supports <b>, <i>, <u>, <font>, <br/>. Enter text between []: <![CDATA[your <b>bold</b> and <i>italic</i> text]]>-->
		</balloon>    
	  </help> 
	  
	  <strings>
		<no_data></no_data>                                       // [No data for selected period] (text) if data for selected period is missing, this message will be displayed -->
		<export_as_image></export_as_image>                       // [Export as image] (text) text for right click menu -->
		<error_in_data_file></error_in_data_file>                 // [Error in data file] (text) this text is displayed if there is an error in data file or there is no data in file. "There is no data" means that there should actually be at least one space in data file. If data file will be completly empty, it will display "error loading file" text -->
		<collecting_data></collecting_data>                       // [Collecting data] (text) this text is displayed while exporting chart to an image -->
		<wrong_zoom_value></wrong_zoom_value>                     // [Incorrect values] (text) this text is displayed if you set zoom through JavaScript and entered from or to value was not find between series -->    
	  </strings>
	  
	  <labels>                                                    // LABELS -->
																  // you can add as many labels as you want. Some html tags supported: <b>, <i>, <u>, <font>, <a href=""> -->
		<label>
		  <x></x>                                                 // [0] (Number) -->
		  <y>10</y>                                               // [0] (Number) -->
		  <rotate></rotate>                                       // [false] (true / false) -->
		  <width></width>                                      // [] (Number) if empty, will stretch from left to right untill label fits -->
		  <align>center</align>                                   // [left] (left / center / right) -->  
		  <text_color></text_color>                               // [text_color] (hex color code) button text color -->
		  <text_size></text_size>                                 // [text_size](Number) button text size -->
		  <text>                                                  // [] (text) html tags may be used (supports <b>, <i>, <u>, <font>, <a href="">, <br/>. Enter text between []: <![CDATA[your <b>bold</b> and <i>italic</i> text]]>-->
			<![CDATA[<b></b>]]>
		  </text>        
		</label>    
	  
	  </labels>
	  
	  
	  <graphs>                                                    // GRAPHS SETTINGS. These settings can also be specified in data file, as attributes of <graph>, in this case you can delete everything from <graphs> to </graphs> (including) -->
																  // It is recommended to have graph settings here if you don\'t want to mix data with other params -->
																  // copy <graph>...</graph> (including) as many times as many graphs you have and edit settings individually -->
																  // if graph settings are defined both here and in data file, the ones from data file are used -->
		<graph gid="1">                                           // if you are using XML data file, graph "gid" must match graph "gid" in data file -->
																
		  <axis>left</axis>                                       // [left] (left/ right) indicates which y axis should be used -->
		  <title></title>                                  // [] (graph title) -->
		  <color>#175A87</color>                                  // [] (hex color code) if not defined, uses colors from this array: #FF0000, #0000FF, #00FF00, #FF9900, #CC00CC, #00CCCC, #33FF00, #990000, #000066 -->
		  <color_hover></color_hover>                             // [#BBBB00] (hex color code) -->
		  <line_alpha></line_alpha>                               // [100] (0 - 100) -->
		  <line_width></line_width>                               // [0] (Number) 0 for hairline -->                                    
		  <fill_alpha></fill_alpha>                               // [0] (0 - 100) if you want the chart to be area chart, use bigger than 0 value -->
		  <fill_color></fill_color>                               // [grpah.color] (hex color code) -->
		  <balloon_color></balloon_color>                         // [graph color] (hex color code) leave empty to use the same color as graph -->
		  <balloon_alpha></balloon_alpha>                         // [100] (0 - 100) -->      
		  <balloon_text_color></balloon_text_color>               // [#FFFFFF] (hex color code) -->
		  <bullet></bullet>                                       // [] (square, round, filename.swf) can be used predefined bullets (square and round) or loaded custom bullets. Leave empty if you don\'t want to have bullets at all -->
																  // The chart will look for this file in amline_path folder (amline_path is set in HTML) -->
		  <bullet_size></bullet_size>                             // [6](Number) affects only predefined (square and round) bullets, does not change size of custom loaded bullets -->
		  <bullet_color></bullet_color>                           // [graph color] (hex color code) affects only predefined (square and round) bullets, does not change color of custom loaded bullets. Leave empty to use the same color as graph  -->
		  <bullet_alpha></bullet_alpha>                           // [graph alpha] (hex color code) Leave empty to use the same alpha as graph -->      
		  <hidden></hidden>                                       // [false] (true / false) vill not be visible until you check corresponding checkbox in the legend -->
		  <selected></selected>                                   // [true] (true / false) if true, balloon indicating value will be visible then roll over plot area -->
		  <balloon_text>
			<![CDATA[]]>                                          // [<b>{value}</b><br>{description}] ({title} {value} {series} {description} {percents}) You can format any balloon text: {title} will be replaced with real title, {value} - with value and so on. You can add your own text or html code too. -->
		  </balloon_text>      
		  <vertical_lines></vertical_lines>                       // [false] (true / false) whether to draw vertical lines or not. If you want to show vertical lines only (without the graph, set line_alpha to 0 -->
		</graph>
		
		<graph gid="2">                                           // if you are using XML data file, graph "gid" must match graph "gid" in data file -->
																
		  <axis>left</axis>                                       // [left] (left/ right) indicates which y axis should be used -->
		  <title></title>                                  // [] (graph title) -->
		  <color>#970008</color>                                  // [] (hex color code) if not defined, uses colors from this array: #FF0000, #0000FF, #00FF00, #FF9900, #CC00CC, #00CCCC, #33FF00, #990000, #000066 -->
		  <color_hover></color_hover>                             // [#BBBB00] (hex color code) -->
		  <line_alpha></line_alpha>                               // [100] (0 - 100) -->
		  <line_width></line_width>                               // [0] (Number) 0 for hairline -->                                    
		  <fill_alpha></fill_alpha>                               // [0] (0 - 100) if you want the chart to be area chart, use bigger than 0 value -->
		  <fill_color></fill_color>                               // [grpah.color] (hex color code) -->
		  <balloon_color></balloon_color>                         // [graph color] (hex color code) leave empty to use the same color as graph -->
		  <balloon_alpha></balloon_alpha>                         // [100] (0 - 100) -->      
		  <balloon_text_color></balloon_text_color>               // [#FFFFFF] (hex color code) -->
		  <bullet></bullet>                                       // [] (square, round, filename.swf) can be used predefined bullets (square and round) or loaded custom bullets. Leave empty if you don\'t want to have bullets at all -->
																  // The chart will look for this file in amline_path folder (amline_path is set in HTML) -->
		  <bullet_size></bullet_size>                             // [6](Number) affects only predefined (square and round) bullets, does not change size of custom loaded bullets -->
		  <bullet_color></bullet_color>                           // [graph color] (hex color code) affects only predefined (square and round) bullets, does not change color of custom loaded bullets. Leave empty to use the same color as graph  -->
		  <bullet_alpha></bullet_alpha>                           // [graph alpha] (hex color code) Leave empty to use the same alpha as graph -->      
		  <hidden></hidden>                                       // [false] (true / false) vill not be visible until you check corresponding checkbox in the legend -->
		  <selected></selected>                                   // [true] (true / false) if true, balloon indicating value will be visible then roll over plot area -->
		  <balloon_text>
			<![CDATA[]]>                                          // [<b>{value}</b><br>{description}] ({title} {value} {series} {description} {percents}) You can format any balloon text: {title} will be replaced with real title, {value} - with value and so on. You can add your own text or html code too. -->
		  </balloon_text>      
		  <vertical_lines></vertical_lines>                       // [false] (true / false) whether to draw vertical lines or not. If you want to show vertical lines only (without the graph, set line_alpha to 0 -->
		</graph>
	  </graphs>  
	</settings>